<?php

/**
 * PracticeSessionHistoryRegistryForm class.
 * PracticeSessionHistoryRegistryForm is the data structure for keeping
 * the information about the attendance registry. It is used by the 'register' action of 'PracticeSessionHistory'.
 *
 * @property string $date
 * @property int $clubID
 * @property int $coachID
 * @property int $practiceSessionID
 * @property int[] $athletesAttended
 * @property int[] $athletesJustifiedUnnatendance
 * @property int[] $athletesInjustifiedUnnatendance
 * @property bool $cancelled
 * @property bool $autoSubmit
 * @property PracticeSessionHistory $practiceSessionHistory
 * @property bool $clickedCancel
 */
class PracticeSessionHistoryRegistryForm extends CFormModel {

    public $date;
    public $clubID;
    public $coachID;
    public $practiceSessionID;
    public $athletesAttended;
    public $athletesJustifiedUnnatendance;
    public $athletesInjustifiedUnnatendance;
    public $cancelled;
    public $autoSubmit;
    public $practiceSessionHistory;
    public $clickedCancel;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // cancelledDueToRain needs to be a boolean
            array('cancelled', 'boolean'),
            array('date,clubID,coachID,practiceSessionID,athletesAttended,athletesJustifiedUnnatendance,
            athletesInjustifiedUnnatendance,cancelled,autoSubmit,clickedCancel','safe'),
            array('athletesAttended', 'allAthletesReferenced'),
            array('athletesAttended', 'noAthleteRepetitions'),
            array('athletesJustifiedUnnatendance', 'caseCancelled'),
        );
    }

    public function caseCancelled($attribute) {
        if ( $this->cancelled && count($this->athletesAttended) > 0) {
            $this->addError($attribute, "No caso de aula cancelada, não podem haver atletas presentes");
            //move athletes to unjustified absence
            $this->athletesJustifiedUnnatendance = CHelper::mergeArrays(array(
                $this->athletesAttended, $this->athletesInjustifiedUnnatendance
            ));
            $this->athletesInjustifiedUnnatendance = array();
            return false;
        }
        return true;
    }

    public function allAthletesReferenced($attribute) {
        $notRegisteredAthletes = array();
        foreach ($this->getPracticeSessionAthleteIds() as $athleteID) {
            if (!in_array($athleteID, $this->getAthletesWithSubmittedAttendance())) {
                $notRegisteredAthletes[] = $athleteID;
            }
        }
        if (count($notRegisteredAthletes) > 0) {
            $athletesNameString = User::getAttributeStringFromIDs($notRegisteredAthletes, 'name');
            $this->addError($attribute, "Tem que registar o(s) atleta(s) $athletesNameString");
            return false;
        }
        return true;
    }

    public function noAthleteRepetitions($attribute) {
        //TODO write method
        $attendanceCount = array();
        $athletesWithMultipleAttendance = array();
        foreach ($this->getAthletesWithSubmittedAttendance() as $athleteID) {
            $attendanceCount[$athleteID] = 0;
            if (CHelper::inArray($athleteID, $this->athletesAttended)) { $attendanceCount[$athleteID]++; }
            if (CHelper::inArray($athleteID, $this->athletesJustifiedUnnatendance)) { $attendanceCount[$athleteID]++; }
            if (CHelper::inArray($athleteID, $this->athletesInjustifiedUnnatendance)) { $attendanceCount[$athleteID]++; }
            if ($attendanceCount[$athleteID] > 1) {
                $athletesWithMultipleAttendance[] = $athleteID;
            }
        }
        if (count($athletesWithMultipleAttendance) > 0) {
            $athletesNameString = User::getAttributeStringFromIDs($athletesWithMultipleAttendance, 'name');
            $this->addError($attribute, "O(s) atleta(s) $athletesNameString aparece(m) com mais do que um tipo de assiduidade");
            return false;
        }
        return true;
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'date' => PracticeSessionHistory::model()->getAttributeLabel('date'),
            'clubID' => PracticeSessionHistory::model()->getAttributeLabel('clubID'),
            'coachID' => PracticeSessionHistory::model()->getAttributeLabel('coachID'),
            'practiceSessionID' => PracticeSession::model()->getAttributeLabel('practiceSessionID'),
            'athletesAttended' => 'Presenças',
            'athletesJustifiedUnnatendance' => 'Ausências com compensação de treino',
            'athletesInjustifiedUnnatendance' => 'Ausências sem compensação de treino',
            'cancelled' => PracticeSessionHistory::model()->getAttributeLabel('cancelled'),
        );
    }

    /**
     *
     * Saves the information in the form to the database.
     * @return bool
     */
    public function save($validate = true)
    {
        if ($this->autoSubmit || ($validate && !$this->validate())) { return false; }
        $this->getPracticeSessionHistory();
        if ($this->practiceSessionHistory === null) {
            $this->practiceSessionHistory = new PracticeSessionHistory();
            $this->practiceSessionHistory->date = $this->date;
            $this->practiceSessionHistory->clubID = $this->clubID;
            $this->practiceSessionHistory->coachID = $this->coachID;
            $practiceSession = $this->getPracticeSession();
            $this->practiceSessionHistory->startTime = $practiceSession->startTime;
            $this->practiceSessionHistory->endTime = $practiceSession->endTime;
        }
        $this->practiceSessionHistory->cancelled = $this->cancelled;
        if (!$this->practiceSessionHistory->save()) {
            return false;
        }
        return $this->saveAthletesAttendanceToDB();
    }

    public function getPracticeSessionOptions()
    {
        /** @var Club $club */
        $club = Club::model()->findByPk($this->clubID);
        /** @var User $coach */
        $coach = User::model()->findByPk($this->coachID);
        return $club == null ? null : $club->getPracticeSessionOptions($this->date, $coach);
    }

    public function isPracticeSessionAllowed()
    {
        /** @var Club $club */
        $club = Club::model()->findByPk($this->clubID);
        /** @var User $coach */
        $coach = User::model()->findByPk($this->coachID);
        if ($club == null) {
            return false;
        }
        $practiceSessions = $club->getPracticeSessions($this->date, $coach);
        //set practice session if only one available and return that it is allowed
        if (count($practiceSessions) === 1) {
            $this->practiceSessionID = $practiceSessions[0]->primaryKey;
            return true;
        }
        foreach ($practiceSessions as $practiceSession) {
            if ($practiceSession->primaryKey === $this->practiceSessionID) {
                return true;
            }
        }
        return false;
    }

    public function getPracticeSessionAthleteIds()
    {
        $practiceSession = PracticeSession::model()->findByPk($this->practiceSessionID);
        return CHelper::getArrayOfAttribute($practiceSession->athletes, User::model()->getTableSchema()->primaryKey);
    }

    public function setupAttendance()
    {
        if (($this->existsOnDb() || $this->hasErrors()) && !$this->clickedCancel) { return null; }
        $athleteIds = $this->getPracticeSessionAthleteIds();
        if ($this->cancelled) {
            $this->athletesJustifiedUnnatendance = $athleteIds;
            $this->athletesAttended = array();
        } else {
            $this->athletesAttended = $athleteIds;
            $this->athletesJustifiedUnnatendance = array();
        }
        $this->athletesInjustifiedUnnatendance = array();
    }

    public function getPracticeSessionAthleteOptions()
    {
        $practiceSession = PracticeSession::model()->findByPk($this->practiceSessionID);
        return CHtml::listData($practiceSession->athletes, 'userID', 'name');
    }

    public function isAttendedPlayersValid()
    {
        if (count($this->athletesAttended) < 1) {
            return true;
        }
        foreach ($this->athletesAttended as $athleteAttendedID) {
            $valid = false;
            foreach ($this->getPracticeSessionAthleteIds() as $practiceSessionAthleteID) {
                if ($athleteAttendedID === $practiceSessionAthleteID) {
                    $valid = true;
                }
            }
            if (!$valid) {
                return false;
            }
        }
        return true;
    }

    /**
     *
     * @return bool
     */
    public function loadHistoryFromDB()
    {
        /** @var PracticeSessionHistory $practiceSessionHistory */
        $this->getPracticeSessionHistory();
        if ($this->practiceSessionHistory === null) {
            return false;
        }
        if (!$this->clickedCancel) {
            $this->cancelled = $this->practiceSessionHistory->cancelled;
        }
        //get PK to use on getArrayOfAttributes
        $userPK = User::model()->getTableSchema()->primaryKey;
        $athleteIDs = array();
        //loop through athlete array indexed by attendanceTypeID
        foreach ($this->practiceSessionHistory->getAthletesAttendanceType() as $key => $athletes) {
            $athleteIDs[$key] = CHelper::getArrayOfAttribute($athletes, $userPK);
        }
        //set the properties as needed
        $this->athletesAttended = $athleteIDs[PracticeSessionAttendanceType::getAttended()->primaryKey];
        $this->athletesJustifiedUnnatendance = $athleteIDs[PracticeSessionAttendanceType::getJustifiedUnnatended()->primaryKey];
        $this->athletesInjustifiedUnnatendance = $athleteIDs[PracticeSessionAttendanceType::getInjustifiedUnnatended()->primaryKey];
        return true;
    }

    /**
     *
     */
    private function getPracticeSessionHistory()
    {
        /** @var PracticeSession $practiceSession */
        $practiceSession = $this->getPracticeSession();
        $this->practiceSessionHistory = PracticeSessionHistory::model()->findByAttributes(array(
            'date' => $this->date,
            'clubID' => $this->clubID,
            'coachID' => $this->coachID,
            'startTime' => $practiceSession->startTime,
            'endTime' => $practiceSession->endTime,
        ));
    }

    private function getPracticeSession()
    {
        return PracticeSession::model()->findByPk($this->practiceSessionID);
    }

    /**
     * Converts the athletes attendance registered on the form to the DB format by inserting, deleting or updating every
     * database record (through active records, of course)
     * @return bool
     */
    private function saveAthletesAttendanceToDB()
    {
        $affectedAthleteIDs = array_unique(array_merge($this->getDbAthleteIds(), $this->getAthletesWithSubmittedAttendance()));
        foreach ($affectedAthleteIDs as $athleteID) {
            if (!$this->saveAthleteAttendance($athleteID)) {
                return false;
            }
        }
        return true;
    }

    private function saveAthleteAttendance($athleteID)
    {
        $practiceSessionHistoryHasAthlete = PracticeSessionHistoryHasAthlete::model()->findByAttributes(array(
            'practiceSessionHistoryID' => $this->practiceSessionHistory->primaryKey,
            'athleteID' => $athleteID,
        ));
        if (in_array($athleteID, $this->getAthletesWithSubmittedAttendance())) {
            if ($practiceSessionHistoryHasAthlete === null) {
                //create object and assign values
                $practiceSessionHistoryHasAthlete = new PracticeSessionHistoryHasAthlete();
                $practiceSessionHistoryHasAthlete->practiceSessionHistoryID = $this->practiceSessionHistory->primaryKey;
                $practiceSessionHistoryHasAthlete->athleteID = $athleteID;
            }
            //assign attendance type
            $practiceSessionHistoryHasAthlete->attendanceTypeID = $this->getAthleteSubmittedAttendanceTypeID($athleteID);
            return $practiceSessionHistoryHasAthlete->save();
        }
        return $practiceSessionHistoryHasAthlete->delete();
    }

    private function getDbAthleteIds() {
        return CHelper::getArrayOfAttribute($this->practiceSessionHistory->athletes,
            User::model()->tableSchema->primaryKey);
    }

    /**
     * @param $athleteID
     * @throws CException
     * @return int
     */
    private function getAthleteSubmittedAttendanceTypeID($athleteID)
    {
        if (CHelper::inArray($athleteID, $this->athletesAttended)) {
            return PracticeSessionAttendanceType::getAttended()->primaryKey;
        }
        if (CHelper::inArray($athleteID, $this->athletesJustifiedUnnatendance)) {
            return PracticeSessionAttendanceType::getJustifiedUnnatended()->primaryKey;
        }
        if (CHelper::inArray($athleteID, $this->athletesInjustifiedUnnatendance)) {
            return PracticeSessionAttendanceType::getInjustifiedUnnatended()->primaryKey;
        }
        throw new CException('O atleta ' . User::model()->findByPk($athleteID)->name .
            ' foi submetido com um tipo de assiduidade inválido');
    }

    /**
     * @return int[]
     */
    private function getAthletesWithSubmittedAttendance()
    {
        return CHelper::mergeArrays(array($this->athletesAttended, $this->athletesJustifiedUnnatendance,
            $this->athletesInjustifiedUnnatendance));
    }

    public function existsOnDb()
    {
        $this->getPracticeSessionHistory();
        return isset($this->practiceSessionHistory);
    }

}
