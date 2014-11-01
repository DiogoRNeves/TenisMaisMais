<?php

/**
 * This is the model class for table "User".
 *
 * The followings are the available columns in table 'User':
 * @property integer $userID
 * @property integer $contactID
 * @property integer $homeID
 * @property string $name
 * @property string $password
 * @property string $birthDate
 * @property string $federationNumber
 * @property integer $coachLevelID
 * @property integer $playerLevelID
 * @property integer $activated
 * @property string $activationHash
 * @property integer $activationMailSent whether the activation mail was sent or not
 *
 * The followings are the available model relations:
 * @property ClubHasUser[] $clubHasUsers
 * @property CompetitiveResultHistory[] $competitiveLosses
 * @property CompetitiveResultHistory[] $competitiveVictories
 * @property MainCoach[] $mainCoaches
 * @property MainCoach[] $athletesCoached
 * @property PracticeSession[] $coachPracticeSessions
 * @property PracticeSession[] $athletePracticeSessions
 * @property PracticeSessionHistory[] $practiceSessionHistories
 * @property PracticeSessionHistory[] $practiceSessionHistories1
 * @property Sponsor[] $sponsors
 * @property Sponsor[] $sponsoredAthletes
 * @property Contact $contact
 * @property Home $home
 * @property PlayerLevel $playerLevel
 * @property CoachLevel $coachLevel
 * @property Club $clubsManaged The clubs managed by this user
 * @property Club $clubs Clubs related to this user
 * @property Club $coachClubs Clubs this user is a coach at
 * @property Club $athleteClubs Clubs this user is an athlete at
 * @property PracticeSession[] $activeAthletePracticeSessions the active practice sessions as an athlete for this user
 * 
 * @property String $newPassword Password in plain text, from form
 * @property String $oldPassword Password in plain text, from form
 * @property String $newPasswordRepeated Password in plain text, from form
 */
class User extends CExtendedActiveRecord {

    //TODO: handle save for password changing
    public $newPassword, $oldPassword, $newPasswordRepeated;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'User';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('contactID, homeID, coachLevelID, playerLevelID, activated', 'numerical', 'integerOnly' => true),
            array('name, federationNumber, newPassword', 'length', 'max' => 45),
            array('activationHash', 'length', 'max' => 512),
            array('contactID', 'unique'),
            array('birthDate', 'type', 'type' => 'date', 'message' => 'Data inválida!', 'dateFormat' => 'yyyy-MM-dd'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('userID, birthDate, contactID, homeID, name, password, birthDate, federationNumber, coachLevelID, playerLevel, activated, activationHash', 'safe', 'on' => 'search'),
            array('newPassword',
                'match', 'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{4,}$/',
                'message' => 'As passswords têm que ter pelo menos uma minúscula, uma maiúscula, '
                . 'um número, 4 caracteres e não podem conter espaços.'),
            array('newPasswordRepeated', 'compare', 'compareAttribute' => 'newPassword',
                'message' => 'A nova password tem que ser igual.'),
            array('birthDate, federationNumber', 'default', 'value' => NULL),
            array('newPassword, newPasswordRepeated, birthDate', 'required', 'on' => 'activation'),
            array('oldPassword', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'clubHasUsers' => array(self::HAS_MANY, 'ClubHasUser', 'userID'),
            'competitiveLosses' => array(self::HAS_MANY, 'CompetitiveResultHistory', 'loserUserID'),
            'competitiveVictories' => array(self::HAS_MANY, 'CompetitiveResultHistory', 'winnerUserID'),
            'athletesCoached' => array(self::HAS_MANY, 'MainCoach', 'coachID'),
            'mainCoaches' => array(self::HAS_MANY, 'MainCoach', 'athleteID'),
            'coachPracticeSessions' => array(self::HAS_MANY, 'PracticeSession', 'coachID'),
            'athletePracticeSessions' => array(self::MANY_MANY, 'PracticeSession', 'PracticeSessionHasAthlete(athleteID, practiceSessionID)'),
            'coachPracticeSessionsHistory' => array(self::HAS_MANY, 'PracticeSessionHistory', 'coachID'),
            'athletePracticeSessionsHistory' => array(self::MANY_MANY, 'PracticeSessionHistory', 'PracticeSessionHistoryHasAthlete(athleteID, practiceSessionHistoryID)'),
            'sponsoredAthletes' => array(self::MANY_MANY, 'User', 'Sponsor(sponsorID, athleteID)', 'order'=>'name ASC'),
            'sponsors' => array(self::MANY_MANY, 'User', 'Sponsor(athleteID, sponsorID)', 'order'=>'name ASC'),
            'contact' => array(self::BELONGS_TO, 'Contact', 'contactID'),
            'home' => array(self::BELONGS_TO, 'Home', 'homeID'),
            'playerLevel' => array(self::BELONGS_TO, 'PlayerLevel', 'playerLevelID'),
            'coachLevel' => array(self::BELONGS_TO, 'CoachLevel', 'coachLevelID'),
            'clubsManaged' => array(self::HAS_MANY, 'Club', 'adminUserID'),
            'clubs' => array(self::MANY_MANY, 'Club', 'ClubHasUser(userID, clubID)'),
            'activeAthletePracticeSessions' => array(self::MANY_MANY, 'PracticeSession', 'PracticeSessionHasAthlete(athleteID, practiceSessionID)',
                'condition' => 'activePracticeSession = :active', 'params' => array(':active' => true), 'order' => 'dayOfWeek ASC'),
            'coachClubs' => array(self::MANY_MANY, 'Club', 'ClubHasUser(userID, clubID)',
                'condition' => 'userTypeID = :cid', 'params' => array(':cid' => UserType::model()->getCoach()->primaryKey)),
            'athleteClubs' => array(self::MANY_MANY, 'Club', 'ClubHasUser(userID, clubID)',
                'condition' => 'userTypeID = :cid', 'params' => array(':cid' => UserType::model()->getAthlete()->primaryKey)),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'userID' => 'Utilizador',
            'contactID' => 'Contacto',
            'homeID' => 'Casa',
            'name' => 'Nome',
            'password' => 'Password',
            'birthDate' => 'Data de Nascimento',
            'federationNumber' => 'Licença FPT',
            'coachLevelID' => 'Nível de Treinador',
            'playerLevelID' => 'Nível de Jogador',
            'activated' => 'Ativado',
            'activationHash' => 'Hash de Ativação',
            'newPassword' => 'Password Nova',
            'sponsors' => 'Patrocinadores',
            'sponsoredAthletes' => 'Atletas Patrocinados',
            'newPasswordRepeated' => 'Repetir Password Nova',
            'oldPassword' => 'Password Antiga'
        );
    }

    /**
     * This method must be implemented in order for {@link getListData} to work
     * @return string the attribute name to get Text Filed data from
     */
    public function getListDataTextField() {
        return 'name';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Returns the user model that contains the contact info passed as an argument.
     * 
     * @param string $contactToSearch content of the contact to search
     * @return User the static model class of the user found. Null if none is found
     */
    public static function findByContactInfo($contactToSearch) {
        /** @var $contact User[] Users found * */
        $contact = Contact::model()->searchAllAttributes($contactToSearch);
        return count($contact) == 0 ? null :
                User::model()->findByAttributes(
                        array(
                            Contact::model()->tableSchema->primaryKey => $contact[0]->primaryKey
        ));
    }

    /**
     * Hashes the user password before saving it to persistance
     * @return boolean whether the model was saved successfully or not
     */
    public function beforeSave() {
        if (!empty($this->newPassword)) {
            $this->hashPassword();
        }
        return true;
    }

    /**
     * Generates the activation hash and saves it into the model
     */
    public function generateActivationHash() {
        $this->activationHash = CPasswordHelper::generateSalt();
    }

    /**
     * Hashes the password and saves into the model
     */
    protected function hashPassword() {
        $this->password = CPasswordHelper::hashPassword($this->newPassword);
    }

    /**
     * Activates an user into the model
     */
    public function activate() {
        $this->activated = 1;
    }

    /**
     * Deactivates an user into the model
     */
    public function deActivate() {
        $this->activated = 0;
    }

    /**
     * Checks if an user is activated
     * @return boolean Whether the user is active or not
     */
    public function isActivated() {
        return $this->activated == 1;
    }

    /**
     * Gets all the possible admin users. At the moment that means all users, but
     * it should be all coaches of a given club.
     * @return CActiveRecord all possible admin users
     */
    public function getPossibleAdminUsers() {
        return $this->findAll();
    }

    /**
     * Checks if this model is an athlete
     * @return boolean
     */
    public function isAthlete() {
        return $this->isUserType(UserType::model()->getAthlete());
    }

    /**
     * Checks if this model is a coach
     * @return boolean
     */
    public function isCoach() {
        return $this->isUserType(UserType::model()->getCoach());
    }

    /**
     * @param Club $club
     * @return boolean
     */
    public function isCoachAt($club) {
        foreach ($club->coaches as $coach) {
            if ($this->isUser($coach)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if this model is a sponsor
     * @return boolean
     */
    public function isSponsor() {
        return $this->isUserType(UserType::model()->getSponsor());
    }

    /**
     * Checks the usertypes primary keys assotiated with this model
     * @return array the user types primary key for this instance of the model
     */
    public function getUserTypesPK() {
        $result = array();
        foreach ($this->getUserTypes() as $userType) {
            array_push($result, $userType->primaryKey);
        }
        return $result;
    }

    /**
     * Checks the usertypes assotiated with this model
     * @return array the user types for this instance of the model
     */
    public function getUserTypes() {
        $result = array();
        $allUserTypes = UserType::model()->findAll();
        foreach ($allUserTypes as $userType) {
            if ($this->isUserType($userType)) {
                array_push($result, $userType);
            }
        }
        return $result;
    }

    /**
     * Compiles a string with the usertype names
     * @return string a string with the user names separated by commas
     */
    public function getUserTypeNames() {
        /* @var $userType UserType */
        $result = "";
        foreach ($this->getUserTypes() as $userType) {
            $result .= $result == "" ? "" : ", ";
            $result .= $userType->name;
        }
        return $result;
    }

    /**
     * Checks if this model is of the given UserType
     * @param UserType $userType
     * @return boolean
     */
    protected function isUserType($userType) {
        $condition = new CDbCriteria();
        if ($userType->name == 'patrocinador') {
            $condition->compare('sponsorID', $this->userID);
            $results = Sponsor::model()->find($condition);
        } else {
            $condition->compare('userID', $this->userID);
            $condition->compare('UserTypeID', $userType->userTypeID);
            $results = ClubHasUser::model()->find($condition);
        }
        return isset($results) ? $results->exists() : false;
    }

    /**
     * Checks if the user is a club admin.
     * @return bool 
     */
    public function isClubAdmin() {
        $clubs = $this->clubsManaged;
        return count($clubs) > 0;
    }

    /**
     * 
     * @param Club $club
     * @return boolean
     */
    public function isClubAdminOf($club) {
        if ($club->adminUser == null) {
            return false;
        }
        return $club->adminUser->isUser($this);
    }

    public function getDetailViewData() {
        return array_merge_recursive($this->attributes, isset($this->contact) ? $this->contact->attributes : array(), array('userTypes' => $this->getUserTypeNames()));
    }

    /**
     * 
     * @param string $hash the hash to validate
     * @return bool whether the user can be activated or not
     */
    public function canBeActivated($hash) {
        return $this->isActivationHash($hash) && !$this->isActivated();
    }

    /**
     * 
     * @param string $hash the hash to validate
     * @return bool
     */
    public function isActivationHash($hash) {
        return $this->activationHash === $hash;
    }

    /**
     * Finds all user of the given type(s).
     * @param UserType[] $userTypes The user types to find
     * @param array $criteria the filters to be applied in form array('userType' => array('pkValues')).
     * @return User[] the users found
     */
    public function findByType($userTypes, $criteria) {
        //TODO: write function
        return $this->findAll();
    }

    /**
     * Returns all practice session events related with this user to be fed to FullCalendar plugin
     * @param string $start
     * @param string $end
     * @return PracticeSession[] 
     */
    public function getFullCalendarPracticeSessionEvents($start) {
        $results = array();
        $loggedUser = User::model()->findByPk(Yii::app()->user->id);
        /* @var $practiceSession PracticeSession */
        $sessions = array_merge($this->coachPracticeSessions, $this->athletePracticeSessions);
        foreach ($sessions as $practiceSession) {
            if ($practiceSession->activePracticeSession) {
                $calendarEvent = array_merge($practiceSession->attributes, array(
                    'formAthletes' => $practiceSession->getAthleteIDs(),
                    'start' => $practiceSession->getCalendarStartTime($start),
                    'end' => $practiceSession->getCalendarEndTime($start),
                    'title' => implode(', ', $practiceSession->getAthleteNames()),
                    'editable' => $this->canScheduleBeUpdated($loggedUser),
                ));
                array_push($results, $calendarEvent);
            }
        }
        return $results;
    }

    /**
     * Returns a sample practice session for this user in order to generate the model form to the calendar
     * @return PracticeSession
     */
    public function getSamplePracticeSession() {
        $practiceSession = new PracticeSession;
        $practiceSession->clubID = $this->getClubID();
        $practiceSession->club = Club::model()->findByPk($practiceSession->clubID);
        $practiceSession->coachID = $this->userID;
        $practiceSession->coach = $this;
        return $practiceSession;
    }

    public function getClubID() {
        return count($this->clubs) == 0 ? null : $this->clubs[0]->primaryKey;
    }

    /**
     * 
     * @param UserType $userType the user type to find
     * @return User[]
     */
    public function getRelatedUsers($userType) {
        if ($userType == null) {
            $result = $this->getRelatedAthletes();
        }
        switch ($userType->name) {
            case 'atleta':
                $result = $this->getRelatedAthletes();
                break;
            case 'treinador':
                $result = $this->getRelatedCoaches();
                break;
            case 'patrocinador':
                $result = $this->getRelatedSponsors();
                break;
            default:
                $result = null;
                break;
        }
        return $result == null ? null : array_unique($result);
    }

    /**
     * 
     * @return User[] the related Athletes
     */
    public function getRelatedAthletes() {
        return array_unique(array_merge($this->getCoachedAthletes(), $this->sponsoredAthletes));
    }

    public function getCoachedAthletes() {
        /* @var $club Club */
        $result = array();
        foreach ($this->coachClubs as $club) {
            $result = array_merge($result, $club->athletes);
        }
        return $result;
    }

    /**
     * 
     * @return User[] the related Coaches
     */
    public function getRelatedCoaches() {
        /* @var $club Club */
        $result = array();
        foreach ($this->coachClubs as $club) {
            $result = array_merge($result, $club->coaches);
        }
        return $result;
    }

    /**
     * TODO
     * @return User[] the related Sponsors
     */
    public function getRelatedSponsors() {
        //TODO: write proper code
        return User::model()->findAll();
    }

    public function getOtherPracticeSessionUserLinks() {
        /* @var $users User[] */
        $users = array();
        if ($this->isCoach()) {
            $users = array_merge($users, $this->getSameClubCoaches());
        }
        if ($this->isSponsor()) {
            $users = array_merge($users, $this->sponsoredAthletes);
        }
        $result = array();
        foreach (array_unique($users) as $user) {
            /* @var $user User */
            $result[] = $user->generatePracticeLink();
        }
        return $result;
    }

    public function getSameClubCoaches() {
        $result = array();
        /* @var $coaches User[] */
        $coaches = array();
        foreach ($this->clubs as $club) {
            $coaches = array_merge($coaches, $club->coaches);
        }
        foreach ($coaches as $coach) {
            if ($coach->primaryKey !== $this->primaryKey) {
                $result[] = $coach;
            }
        }
        return $result;
    }

    public function generatePracticeLink() {
        return array('label' => $this->name, 'url' => array('index', 'userID' => $this->primaryKey));
    }

    /**
     * 
     * @return boolean
     */
    public function canListUsers() {
        return $this->canListAthletes() || $this->canListCoaches();
    }

    /**
     * 
     * @return boolean
     */
    public function canViewUser() {
        $user = User::model()->findByPk($this->getSuperglobalUserID());
        return $this->canUpdateUser() || $user->isSponsorOf($this);
    }

    public function getSuperglobalUserID() {
        if (isset($_GET['userID'])) {
            return $_GET['userID'];
        }
        if (isset($_GET['id'])) {
            return $_GET['id'];
        }
        return false;
    }

    /**
     * 
     * @return boolean
     */
    public function canUpdateUser($userID = null) {
        $userID = $userID == null || $userID instanceof CWebUser ? $this->getSuperglobalUserID() : $userID;
        $user = User::model()->findByPk($userID);
        return $this->isUser($user) || $this->isSponsorOf($user) || $this->isCoachOf($user) || $this->coachesInSameClubOf($user);
    }

    /**
     * 
     * @return boolean
     */
    public function canCreateUsers() {
        if (!isset($_REQUEST['ClubHasUser'], $_REQUEST['ClubHasUser']['clubID'])) {
            return false;
        }
        $club = Club::model()->findByPk($_REQUEST['ClubHasUser']['clubID']);
        return $this->isClubAdminOf($club) || $this->isCoachAt($club);
    }

    /**
     * 
     * @return boolean
     */
    public function isSystemAdmin() {
        return $this->primaryKey == 1;
    }

    public function canListCoaches() {
        return $this->isSystemAdmin() || $this->isCoach() || $this->isClubAdmin();
    }

    public function canListAthletes() {
        return $this->isSystemAdmin() || $this->canListCoaches() || $this->isSponsor();
    }

    /**
     * 
     * @param User $user
     * @return boolean
     */
    public function isCoachOf($user) {
        foreach ($this->coachClubs as $club) {
            if ($club->hasAthlete($user)) {
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
    public function isSponsorOf($user) {
        foreach ($user->sponsors as $sponsor) {
            if ($sponsor->primaryKey === $this->primaryKey) {
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
    public function isUser($user) {
        return $this->primaryKey == $user->primaryKey;
    }

    public function canListClubs() {
        return $this->isSystemAdmin() || $this->isClubAdmin() || $this->isCoach();
    }

    public function canViewClub() {
        /* @var $club Club */
        $club = Club::model()->findByPk($_GET['id']);
        return $this->isSystemAdmin() || $this->canUpdateClub() || $club->hasAthlete($this) || $club->hasSponsor($this);
    }

    public function canUpdateClub() {
        /* @var $club Club */
        $club = Club::model()->findByPk($_GET['id']);
        return $this->isSystemAdmin() || $club->hasCoach($this) || $club->isAdmin($this);
    }

    public function coachesInSameClubOf($user) {
        foreach ($this->coachClubs as $coachClub) {
            if ($coachClub->hasCoach($user)) {
                return true;
            }
        }
        return false;
    }

    public function canChangePassword() {
        if ($this->scenario == 'activation') {
            return true;
        }
        return !$this->isNewRecord && ($this->isUser(User::model()->findByPk(Yii::app()->user->id)) || $this->isSystemAdmin());
    }

    public function isPasswordChangeOK() {
        if (isset($this->newPassword)) {
            if ($this->scenario == 'activation') {
                return true;
            }
            if (isset($this->oldPassword)) {
                if (CPasswordHelper::verifyPassword($this->oldPassword, $this->password)) {
                    return true;
                }
                $this->addError('oldPassword', 'A password introduzida está incorreta.');
                return false;
            }
            return false;
        }
        return true;
    }

    /**
     * 
     * @return boolean
     */
    public function canUpdateUserLevels($user) {
        return $this->canUpdateUser($user->primaryKey) && !$this->isUser($user) && !$this->isSponsorOf($user);
    }

    public function sendAthleteChangesToPracticeMail($fromName = null) {
        return $this->sendAthletePracticeSessionMail("Alterações no horário de treinos", $fromName);
    }

    public function sendAthletAddedToPracticeMail($fromName = null) {
        return $this->sendAthletePracticeSessionMail("Novo treino adicionado", $fromName);
    }

    public function sendAthletRemovedFromPracticeMail($fromName = null) {
        return $this->sendAthletePracticeSessionMail("Treino removido", $fromName);
    }

    private function sendAthletePracticeSessionMail($subject, $fromName = null) {
        $message = $this->getAthleteScheduleMailBody();
        if ($this->sendMail($message, $subject, $fromName)) {
            foreach ($this->sponsors as $sponsor) {
                $sponsor->sendMail($message, $subject . " ($this->name)", $fromName);
            }
        }
        return false;
    }

    public function sendMail($message, $subject, $fromName = null) {
        if (!isset($this->contact)) { return true; }
        if ($fromName == null) { $fromName = Yii::app()->name; }
        $userEmailAddress = $this->contact->email;
        if ($userEmailAddress !== NULL) {
            $mail = new YiiMailer();
            $mail->setView('activation');
            $mail->setData(array('message' => $message,
                'name' => $this->name, 'description' => $subject));
            $mail->setFrom(Yii::app()->params['adminEmail'], $fromName);
            $mail->setTo(array($userEmailAddress => $this->name));
            $mail->setSubject($subject);
            if ($mail->send()) {
                Yii::app()->user->setFlash('mailSent', array(true, "E-mail de enviado para $this->name!"));
                return true;
            }
            Yii::app()->user->setFlash('mailSent', array(false, "Não foi possível enviar o mail para $this->name."));
            return false;
        }
        return true;
    }

    public function getAthleteScheduleMailBody() {
        $htmlText = "O novo horário é:" . CHtml::tag('br') . CHtml::tag('br');
        $tableHtml = PracticeSession::model()->getMailTableHeader();
        foreach ($this->activeAthletePracticeSessions as $practiceSession) {
            $tableHtml .= $practiceSession->toMailTableRow();
        }
        return $htmlText . CHtml::tag('table', array('class' => 'schedule'), $tableHtml);
    }

    /**
     * 
     * @param User $updater
     * @return boolean
     */
    public function canScheduleBeUpdated($updater) {
        return $updater->isAdminOfCoach($this) ||
                ($this->isCoach() && ($this->isUser($updater) || $updater->isSystemAdmin()));
    }

    /**
     * 
     * @param User $updater
     * @return boolean
     */
    public function isAdminOfCoach($coach) {
        foreach ($coach->coachClubs as $club) {
            if ($this->isClubAdminOf($club)) {
                return true;
            }
        }
        return false;
    }

    public function getQuickActions() {
        $actions = array();
        if ($this->isCoach()) {
            foreach ($this->coachClubs as $club) {
                $clubActions = $club->getClubActions($this);
                if ($clubActions != array()) {
                    $actions[] = array(
                        'label' => $club->name,
                        'itemOptions' => array('class' => 'nav-header')
                    );
                    $actions = array_merge($actions, $club->getClubActions($this));
                }
            }
        }
        return $actions;
    }

}
