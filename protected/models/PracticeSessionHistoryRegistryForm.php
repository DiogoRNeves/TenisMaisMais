<?php

/**
 * PracticeSessionHistoryRegistryForm class.
 * PracticeSessionHistoryRegistryForm is the data structure for keeping
 * the information about the attendance registry. It is used by the 'register' action of 'PracticeSessionHistory'.
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


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * TODO: Test this method. Called by {@see PracticeSessionController->actionRegister()}
     * Saves the information in the form to the database.
     * @return bool
     */
    public function save()
    {
        if ($this->autoSubmit) { return false; }
        $practiceSessionHistory = $this->getPracticeSessionHistory();
        if ($practiceSessionHistory === null) {
            $practiceSessionHistory = new PracticeSessionHistory();
            $practiceSessionHistory->date = $this->date;
            $practiceSessionHistory->clubID = $this->clubID;
            $practiceSessionHistory->coachID = $this->coachID;
            $practiceSession = $this->getPracticeSession();
            $practiceSessionHistory->startTime = $practiceSession->startTime;
            $practiceSessionHistory->endTime = $practiceSession->endTime;
            if (!$practiceSessionHistory->save()) {
                //TODO: somehow it enters this if and returns false. Check it out and fix it. Probably some validation rules being violated.
                //echo $practiceSessionHistory->getErrorsString(); // <------- usar isto
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
        if (!$this->autoSubmit) {
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
     * TODO: Test this method. Called by {@see PracticeSessionController->actionRegister()}
     * @return bool
     */
    public function loadHistoryFromDB()
    {
        /** @var PracticeSessionHistory $practiceSessionHistory */
        $practiceSessionHistory = $this->getPracticeSessionHistory();
        if ($practiceSessionHistory === null) {
            return false;
        }
        //get PK to use on getArrayOfAttributes
        $userPK = User::model()->getTableSchema()->primaryKey;
        $athleteIDs = array();
        //loop through athlete array indexed by attendanceTypeID
        foreach ($practiceSessionHistory->getAthletesAttendanceType() as $key => $athletes) {
            $athleteIDs[$key] = CHelper::getArrayOfAttribute($athletes, $userPK);
        }
        //set the properties as needed
        $this->athletesAttended = $athleteIDs[PracticeSessionAttendanceType::getAttended()->primaryKey];
        $this->athletesJustifiedUnnatendance = $athleteIDs[PracticeSessionAttendanceType::getJustifiedUnnatended()->primaryKey];
        $this->athletesInjustifiedUnnatendance = $athleteIDs[PracticeSessionAttendanceType::getInjustifiedUnnatended()->primaryKey];
        return true;
    }

    /**
     * TODO: Test this method. Called by {@see loadHistoryFromDB()) and {@see save()}
     * @return PracticeSessionHistory
     */
    private function getPracticeSessionHistory()
    {
        /** @var PracticeSession $practiceSession */
        $practiceSession = $this->getPracticeSession();
        return PracticeSessionHistory::model()->findByAttributes(array(
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
     * TODO: write this method. Called by {@see save()}
     * Converts the athletes attendance registered on the form to the DB format by inserting, deleting or updating every
     * database record (through active records, of course)
     * @return bool
     */
    private function saveAthletesAttendanceToDB()
    {
        return true;
    }

}
