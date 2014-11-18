<?php

/**
 * PracticeSessionHistoryRegistryForm class.
 * PracticeSessionHistoryRegistryForm is the data structure for keeping
 * the information about the attendance registry. It is used by the 'register' action of 'PracticeSessionHistory'.
 *
 * @property PracticeSessionHistory $practiceSessionHistory
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

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // cancelledDueToRain needs to be a boolean
            array('cancelledDueToRain', 'boolean'),
            array('date,clubID,coachID,practiceSessionID,athletesAttended,athletesJustifiedUnnatendance,
            athletesInjustifiedUnnatendance,cancelled,autoSubmit','safe'),
        );
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
            'athletesJustifiedUnnatendance' => 'Ausências Justificadas (compensáveis)',
            'athletesInjustifiedUnnatendance' => 'Ausências Injustificadas (não compensáveis)',
            'cancelled' => 'Treino cancelado (chuva, etc)',
        );
    }


    //public static function model($className = __CLASS__) {
    //    return parent::model($className);
    //}

    /**
     *
     * Saves the information in the form to the database.
     * @return bool
     */
    public function save()
    {
        if ($this->autoSubmit) { return false; }
        $this->getPracticeSessionHistory();
        if ($this->practiceSessionHistory === null) {
            $this->practiceSessionHistory = new PracticeSessionHistory();
            $this->practiceSessionHistory->date = $this->date;
            $this->practiceSessionHistory->clubID = $this->clubID;
            $this->practiceSessionHistory->coachID = $this->coachID;
            $practiceSession = $this->getPracticeSession();
            $this->practiceSessionHistory->startTime = $practiceSession->startTime;
            $this->practiceSessionHistory->endTime = $practiceSession->endTime;
            if (!$this->practiceSessionHistory->save()) {
                return false;
            }
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
        if (!$this->autoSubmit || (isset($this->practiceSessionHistory) && !$this->practiceSessionHistory->isNewRecord)) {
            return null;
        }
        $athleteIds = $this->getPracticeSessionAthleteIds();
        if ($this->cancelled) {
            $this->athletesJustifiedUnnatendance = $athleteIds;
            $this->athletesAttended = array();
        } else {
            $this->athletesAttended = $athleteIds;
            $this->athletesJustifiedUnnatendance = array();
        }
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
     * @return PracticeSessionHistory
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
        if (in_array($athleteID, $this->athletesAttended)) {
            return PracticeSessionAttendanceType::getAttended()->primaryKey;
        }
        if (in_array($athleteID, $this->athletesJustifiedUnnatendance)) {
            return PracticeSessionAttendanceType::getJustifiedUnnatended()->primaryKey;
        }
        if (in_array($athleteID, $this->athletesInjustifiedUnnatendance)) {
            return PracticeSessionAttendanceType::getInjustifiedUnnatended()->primaryKey;
        }
        throw new CException('O atleta ' . User::model()->findByPk($athleteID)->name .
            ' foi submetido com um tipo de assiduidade inválido');
    }

    private function getAthletesWithSubmittedAttendance()
    {
        return CHelper::mergeArrays(array($this->athletesAttended, $this->athletesJustifiedUnnatendance,
            $this->athletesInjustifiedUnnatendance));
    }

}
