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

    public function save()
    {
        if ($this->autoSubmit) { return false; }
        //TODO: actually save the information in the database
        return true;
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
        } else if (count($this->athletesAttended) < 1 || !$this->isAttendedPlayersValid()) {
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
     * TODO: Test this method
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
        $this->athletesAttended = $athleteIDs[PracticeSessionAttendanceType::getAttended()];
        $this->athletesJustifiedUnnatendance = $athleteIDs[PracticeSessionAttendanceType::getJustifiedUnnatended()];
        $this->athletesInjustifiedUnnatendance = $athleteIDs[PracticeSessionAttendanceType::getInjustifiedUnnatended()];
        return true;
    }

    private function getPracticeSessionHistory()
    {
        //TODO: actually get it. this method is called {@see loadHistoryFromDB()}
        return null;
    }

}
