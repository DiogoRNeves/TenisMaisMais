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

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // cancelledDueToRain needs to be a boolean
            array('cancelledDueToRain', 'boolean'),
            array('date,clubID,coachID,practiceSessionID,athletesAttended,athletesJustifiedUnnatendance,
            athletesInjustifiedUnnatendance,cancelled','safe'),
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
        //TODO: actually save the information
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
        $result = array();
        foreach ($practiceSession->athletes as $athlete) {
            $result[] = $athlete->primaryKey;
        }
        return $result;
    }

    public function setupAttendance()
    {
        //TODO: if submit source is not cancel switch, do nothing
        $athleteIds = $this->getPracticeSessionAthleteIds();
        if ($this->cancelled) {
            $this->athletesJustifiedUnnatendance = array_unique(array_merge(
                $this->athletesAttended === null ? array() : $this->athletesAttended,
                $athleteIds === null ? array() : $athleteIds,
                $this->athletesJustifiedUnnatendance === null ? array() : $this->athletesJustifiedUnnatendance));
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

    private function isAttendedPlayersValid()
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

}
