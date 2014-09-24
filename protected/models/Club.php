<?php

/**
 * This is the model class for table "Club".
 *
 * The followings are the available columns in table 'Club':
 * @property integer $clubID
 * @property string $name
 * @property integer $homeID
 * @property integer $contactID
 * @property integer $adminUserID
 *
 * The followings are the available model relations:
 * @property AthleteGroup[] $athleteGroups
 * @property Contact $contact
 * @property Home $home
 * @property User $adminUser
 * @property ClubHasUser[] $clubHasUsers
 * @property MainCoach[] $mainCoaches
 * @property PracticeSession[] $practiceSessions
 * @property PracticeSessionHistory[] $practiceSessionHistories
 * @property User[] $coaches
 * @property User[] $athletes
 */
class Club extends CExtendedActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'Club';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, homeID, contactID, adminUserID', 'required'),
            array('homeID, contactID, adminUserID', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('clubID, name, homeID, contactID, adminUserID', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        parent::relations();
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'athleteGroups' => array(self::HAS_MANY, 'AthleteGroup', 'clubID'),
            'contact' => array(self::BELONGS_TO, 'Contact', 'contactID'),
            'home' => array(self::BELONGS_TO, 'Home', 'homeID'),
            'adminUser' => array(self::BELONGS_TO, 'User', 'adminUserID'),
            'coaches' => array(self::MANY_MANY, 'User', 'ClubHasUser(clubID, userID)',
                'condition' => 'userTypeID = :cid', 'params' => array(':cid' => UserType::model()->getCoach()->primaryKey)),
            'athletes' => array(self::MANY_MANY, 'User', 'ClubHasUser(clubID, userID)',
                'condition' => 'userTypeID = :aid', 'params' => array(':aid' => UserType::model()->getAthlete()->primaryKey)),
            'clubHasUsers' => array(self::HAS_MANY, 'ClubHasUser', 'clubID'),
            'mainCoaches' => array(self::HAS_MANY, 'MainCoach', 'clubID'),
            'practiceSessions' => array(self::HAS_MANY, 'PracticeSession', 'clubID'),
            'practiceSessionHistories' => array(self::HAS_MANY, 'PracticeSessionHistory', 'clubID'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'clubID' => 'Clube',
            'name' => 'Nome',
            'homeID' => 'Casa',
            'contactID' => 'Contacto',
            'adminUserID' => 'Administrador',
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

        $criteria->compare('clubID', $this->clubID);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('homeID', $this->homeID);
        $criteria->compare('contactID', $this->contactID);
        $criteria->compare('adminUserID', $this->adminUserID);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Club the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Returns an array containing all possible admin users for this club.
     * 
     * @return array() associative array to be used in a CHTML::dropDownlList
     */
    public function getAdminUserOptions() {
        $data = $this->isNewRecord ? User::model()->getPossibleAdminUsers() :
                $this->getPossibleAdminUsers();
        return CHTML::listData($data, 'userID', 'name');
    }

    public function getPossibleAdminUsers() {
        return User::model()->findAll();
    }

    /**
     * 
     * @return array the list data that can be used in {@link dropDownList}, {@link listBox}, etc.
     */
    public function getAthletesListData() {
        return $this->getUsersListData($this->athletes);
    }

    /**
     * 
     * @return array the list data that can be used in {@link dropDownList}, {@link listBox}, etc.
     */
    public function getCoachesListData() {
        return $this->getUsersListData($this->coaches);
    }

    /**
     * 
     * @param User[] $users
     * @return array the list data that can be used in {@link dropDownList}, {@link listBox}, etc.
     */
    protected function getUsersListData($users) {
        if ($users == null) {
            return null;
        }
        /* @var $model User */
        $model = $users[0]->model();
        return CHtml::listData($users, $model->tableSchema->primaryKey, $model->getListDataTextField(), $model->getListDataGroupField());
    }

    /**
     * 
     * @param User $user
     * @return boolean
     */
    public function hasAthlete($user) {
        foreach ($this->athletes as $athlete) {
            if ($athlete->primaryKey === $user->primaryKey) {
                return true;
            }
        }
        return false;
    }

    /**
     * 
     * @param User $user
     * @return boolean
     */
    public function hasCoach($user) {
        foreach ($this->coaches as $coach) {
            if ($coach->primaryKey === $user->primaryKey) {
                return true;
            }
        }
        return false;
    }

    /**
     * 
     * @param User $user
     * @return boolean
     */
    public function hasSponsor($user) {
        foreach ($this->athletes as $athlete) {
            if ($user->isSponsorOf($athlete)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 
     * @param User $user
     * @return boolean
     */
    public function isAdmin($user) {
        return $this->adminUserID == $user->primaryKey;
    }

    /**
     * 
     * @param User $coach
     * @return boolean
     */
    public function addCoach($coach) {
        $clubHasUser = new ClubHasUser;
        $clubHasUser->clubID = $this->primaryKey;
        $clubHasUser->userID = $coach->primaryKey;
        $clubHasUser->userTypeID = UserType::model()->getCoach()->primaryKey;
        $clubHasUser->startDate = date('Y-m-d');
        return $clubHasUser->save();
    }

    /**
     * 
     * @param User $user
     * @return array
     */
    public function getClubActions($user) {
        if ($user == null) {
            return array();
        }
        $clubHasUserAthlete = array(
            'clubID' => $this->clubID,
            'userTypeID' => UserType::model()->getAthlete()->userTypeID
        );
        $clubHasUserCoach = $clubHasUserAthlete;
        $clubHasUserCoach['userTypeID'] = UserType::model()->getCoach()->userTypeID;
        $coachActions = array(array('label' => 'Novo Atleta', 'url' => array('user/create',
                    'ClubHasUser' => $clubHasUserAthlete)
        ));
        $adminOnlyActions = array(
            array('label' => 'Editar Clube', 'url' => array('update', 'id' => $this->clubID)),
            array('label' => 'Novo Treinador', 'url' => array('user/create',
                    'ClubHasUser' => $clubHasUserCoach)
            ),
        );
        if ($user->isClubAdminOf($this) || $user->isSystemAdmin()) {
            return array_merge($adminOnlyActions, $coachActions);
        }
        if ($user->isCoachAt($this)) {
            return $coachActions;
        }
        return array();
    }

}
