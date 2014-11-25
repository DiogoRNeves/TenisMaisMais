<?php

/**
 * This is the model class for table "PracticeSession".
 *
 * The followings are the available columns in table 'PracticeSession':
 * @property integer $practiceSessionID
 * @property integer $coachID
 * @property integer $clubID
 * @property integer $activePracticeSession
 * @property string $startTime
 * @property string $endTime
 * @property integer $groupLevel
 * @property integer $dayOfWeek friday is 5
 *
 * The followings are the available model relations:
 * @property Club $club
 * @property User $coach
 * @property User[] $athletes
 * @property PlayerLevel $playerLevel
 * 
 * The following are variables:
 * @property int[] $formAthletes
 */
class PracticeSession extends CExtendedActiveRecord {

    public $formAthletes;
    /* @property Athlete[] $athletesToNotify */
    private $athletesToNotify = array();

    /**
     * @param Club $club
     * @param User $coach
     * @return int
     */
    public static function getCurrentPracticeSessionID($club, $coach)    {
        $today = CHelper::getTodayDate();
        /** @var PracticeSession $practiceSession */
        foreach ($club->getPracticeSessions($today, $coach) as $practiceSession) {
            if ($practiceSession->isGoingOn()) {
                return $practiceSession->primaryKey;
            }
        }
        return null;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'PracticeSession';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('coachID, clubID, startTime, endTime, dayOfWeek', 'required'),
            array('coachID, clubID, activePracticeSession, groupLevel, dayOfWeek', 'numerical', 'integerOnly' => true),
            array('practiceSessionID', 'safe', 'on' => 'ajaxEdit'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('practiceSessionID, coachID, clubID, activePracticeSession, startTime, endTime, groupLevel, dayOfWeek', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'club' => array(self::BELONGS_TO, 'Club', 'clubID'),
            'coach' => array(self::BELONGS_TO, 'User', 'coachID', 'order' => 'name'),
            'athletes' => array(self::MANY_MANY, 'User', 'PracticeSessionHasAthlete(practiceSessionID, athleteID)',
                'order' => 'name'),
            'playerLevel' => array(self::BELONGS_TO, 'PlayerLevel', 'groupLevel'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'practiceSessionID' => 'Sessão de Treino',
            'coachID' => 'Treinador',
            'clubID' => 'Clube',
            'activePracticeSession' => 'Sessão de Treino Ativa',
            'startTime' => 'Hora de Início',
            'endTime' => 'Hora de Fim',
            'groupLevel' => 'Nível do Grupo',
            'dayOfWeek' => 'Dia da Semana',
            'formAthletes' => 'Atletas',
            'athletes' => 'Atletas',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('practiceSessionID', $this->practiceSessionID);
        $criteria->compare('coachID', $this->coachID);
        $criteria->compare('clubID', $this->clubID);
        $criteria->compare('activePracticeSession', $this->activePracticeSession);
        $criteria->compare('startTime', $this->startTime, true);
        $criteria->compare('endTime', $this->endTime, true);
        $criteria->compare('groupLevel', $this->groupLevel);
        $criteria->compare('dayOfWeek', $this->dayOfWeek);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PracticeSession the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function isHappen($date)
    {
        return CHelper::getDayOfWeek($date) == $this->dayOfWeek;
    }

    /**
     * @param $coach User
     * @return mixed
     */
    public function isCoachedBy($coach)
    {
        return $coach->isUser($this->coach);
    }

    /**
     * Updates the formAthlete value to match the athletes. This allows the form to load properly.
     */
    protected function afterFind() {
        $this->formAthletes = $this->getAthleteIDs();
        parent::afterFind();
    }

    /**
     * Updates the athlete value to match the formAthletes. This allows the form to save properly.
     */
    public function afterSave() {
        parent::afterSave();
        // remove old athletes
        $this->compileAthletesToNotify();
        foreach ($this->athletes as $athlete) {
            if (!in_array($athlete->primaryKey, $this->formAthletes)) {
                $this->removeAthlete($athlete);
            }
        }
        // add new athletes
        if (!is_array($this->formAthletes)) {
            $this->formAthletes = array($this->formAthletes);
        }
        foreach ($this->formAthletes as $formAthleteID) {
            $formAthlete = User::model()->findByPk($formAthleteID);
            if (!in_array($formAthlete, $this->athletes)) {
                $this->addAthlete($formAthlete);
            }
        }
        $this->notifyRemainingAthletes();
        $this->notifyClubAdmin();
        $this->refresh();
    }

    private function notifyRemainingAthletes() {
        $fromName = $this->club->name;
        if ($this->hasAttributesChanged()) {
            foreach ($this->athletesToNotify as $athlete) {
                $athlete->sendAthleteChangesToPracticeMail($fromName);
                $this->removeFromAthletesToNotify($athlete);
            }
        }
    }

    private function notifyClubAdmin() {
        /* @var $loggedUser User */
        $loggedUser = User::model()->findByPk(Yii::app()->user->id);
        if (!$loggedUser->isUser($this->club->adminUser)) {
            $this->club->adminUser->sendMail($this->getPracticeSessionChangedAdminMailText(), "Alteração em horário de " . $this->coach->name . " (" . $this->club->name . ")");
        }
    }

    public function hasAttributesChanged() {
        /* @var $savedInDB PracticeSession */
        $savedInDB = PracticeSession::model()->findByPk($this->primaryKey);
        return !($this->startTime === $savedInDB->startTime &&
                $this->endTime === $savedInDB->endTime &&
                $this->activePracticeSession === $savedInDB->activePracticeSession &&
                $this->dayOfWeek === $savedInDB->dayOfWeek);
    }

    /**
     * Adds an athlete to this practice session.
     * @param User $athlete the athlete to add to practice.
     * @return boolean wheter the operation was successfull or not.
     */
    public function addAthlete($athlete) {
        $practiceSessionHasAthlete = new PracticeSessionHasAthlete();
        $practiceSessionHasAthlete->practiceSessionID = $this->primaryKey;
        $practiceSessionHasAthlete->athleteID = $athlete->primaryKey;
        if ($practiceSessionHasAthlete->save()) {
            $athlete->sendAthletAddedToPracticeMail($this->club->name);
            $this->removeFromAthletesToNotify($athlete);
            return true;
        }
        return false;
    }

    /**
     * Removes an athlete to this practice session.
     * @param User $athlete the athlete to add to practice.
     * @return boolean wheter the operation was successfull or not.
     */
    public function removeAthlete($athlete) {
        /* @var $criteria CDbCriteria */
        $criteria = new CDbCriteria();
        $criteria->compare('athleteID', $athlete->primaryKey);
        $criteria->compare($this->tableSchema->primaryKey, $this->primaryKey);
        /* @var $practiceSessionHasAthlete PracticeSessionHasAthlete */
        $practiceSessionHasAthlete = PracticeSessionHasAthlete::model()->find($criteria);
        if ($practiceSessionHasAthlete->delete()) {
            $athlete->sendAthletRemovedFromPracticeMail($this->club->name);
            $this->removeFromAthletesToNotify($athlete);
            return true;
        }
        return false;
    }

    public function getCoachLink() {
        $result = CHelper::getObjectsLinks($this->coach, 'name');
        $result['label'] = 'Treinador';
        return $result;
    }

    public function getClubLink() {
        $result = CHelper::getObjectsLinks($this->club, 'name');
        $result['label'] = 'Club';
        return $result;
    }

    public function getCalendarStartTime($start) {
        return $this->convertoToCalendarTime($start, $this->startTime);
    }

    public function getCalendarEndTime($start) {
        return $this->convertoToCalendarTime($start, $this->endTime);
    }

    public function convertoToCalendarTime($start, $time) {
        $eventStart = new DateTime($start . ' ' . $time);
        $eventStart->add(new DateInterval('P' . ($this->dayOfWeek - 1) . 'D'));
        return $eventStart->format(DateTime::ISO8601);
    }

    public function getAthleteNames() {
        return $this->getAthleteAttribute('name');
    }

    public function getAthleteIDs() {
        return $this->getAthleteAttribute(User::model()->tableSchema->primaryKey);
    }

    public function getAthleteAttribute($attribute) {
        $result = array();
        foreach ($this->athletes as $athlete) {
            array_push($result, $athlete->$attribute);
        }
        return $result;
    }

    /**
     * 
     * @param User $athleteToNotify
     */
    public function removeFromAthletesToNotify($athleteToNotify) {
        foreach ($this->athletesToNotify as $key => $athlete) {
            if ($athleteToNotify->isUser($athlete)) {
                unset($this->athletesToNotify[$key]);
                break;
            }
        }
    }

    public function compileAthletesToNotify() {
        $this->athletesToNotify = array();
        foreach ($this->athletes as $athlete) {
            if (!in_array($athlete->primaryKey, $this->formAthletes)) {
                $this->athletesToNotify[] = $athlete;
            }
        }
        $this->athletesToNotify = array_merge($this->athletesToNotify, $this->athletes);
    }

    public function toMailString() {
        return $this->getDayOfWeekString() . ": " . $this->startTime . " - " . $this->endTime .
                " (" . $this->coach->name . ")";
    }

    public function getDayOfWeekString() {
        switch ($this->dayOfWeek) {
            case 6: return "Sábado";
            case 7: return "Domingo";
            default: return ($this->dayOfWeek + 1) . "ª feira";
        }
    }

    public function toMailTableRow() {
        $rowHtml = CHtml::tag('td', array(), $this->getDayOfWeekString()) .
                CHtml::tag('td', array(), CHelper::timeToString($this->startTime)) .
                CHtml::tag('td', array(), CHelper::timeToString($this->endTime)) .
                CHtml::tag('td', array(), $this->coach->name) .
                CHtml::tag('td', array(), $this->club->name);
        return CHtml::tag('tr', array(), $rowHtml);
    }

    public function getMailTableHeader() {
        $rowHtml = CHtml::tag('td', array(), $this->getAttributeLabel('dayOfWeek')) .
                CHtml::tag('td', array(), $this->getAttributeLabel('startTime')) .
                CHtml::tag('td', array(), $this->getAttributeLabel('endTime')) .
                CHtml::tag('td', array(), $this->getAttributeLabel('coachID')) .
                CHtml::tag('td', array(), $this->getAttributeLabel('clubID'));
        return CHtml::tag('tr', array(), $rowHtml);
    }

    private function getPracticeSessionChangedAdminMailText() {
        $link = Yii::app()->createAbsoluteUrl("practiceSession/index", array(
            'userID' => $this->coach->primaryKey,
        ));
        $htmlLink = CHtml::link($link, $link, array('target' => '_blank'));
        return CHtml::tag('p', array(), "Foi efetuada uma alteração no treino de " . $this->getDayOfWeekString() .
                        " que começava às " . CHelper::timeToString($this->startTime) .
                        ". Para ver o novo horário visite") . CHtml::tag('p', array(), $htmlLink);
    }

    public function getListDataTextField() {
        return CHelper::timeIntervalString($this->startTime, $this->endTime);
    }

    private function isGoingOn()
    {
        $today = CHelper::getTodayDate();
        $startTime = CHelper::newDateTime($today . " " . $this->startTime)->getTimestamp();
        $endTime = CHelper::newDateTime($today . " " . $this->endTime)->getTimestamp();
        $currentTime = CHelper::getNow()->getTimestamp();
        return $startTime < $currentTime && $currentTime < $endTime;
    }

}
