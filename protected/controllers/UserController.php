<?php

class UserController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        $user = User::getLoggedInUser();
        return array(
            array('allow', //allow guest users to activate an account
                'actions' => array('activate'),
                'users' => array('Guest')
            ),
            array('allow', // allow system admin to perform all actions
                'actions' => array('admin', 'index', 'view', 'update', 'create', 'removeFromClub'),
                'users' => array('@'),
                'expression' => array($user, 'isSystemAdmin'),
            ),
            array('allow', // allow authenticated user to perform actions
                'actions' => array('index'),
                'users' => array('@'),
                'expression' => array($user, 'canListUsers'),
            ),
            array('allow', // allow authenticated user to perform actions
                'actions' => array('view'),
                'users' => array('@'),
                'expression' => array($user, 'canViewUser'),
            ),
            array('allow', // allow authenticated user to perform actions
                'actions' => array('update'),
                'users' => array('@'),
                'expression' => array($user, 'canUpdateUser'),
            ),
            array('allow', // allow authenticated user to perform actions
                'actions' => array('create'),
                'users' => array('@'),
                'expression' => array($user, 'canCreateUsers'),
            ),
            array('allow', // allow authenticated user to perform actions
                'actions' => array('removeFromClub'),
                'users' => array('@'),
                'expression' => array($user, 'canRemoveUser'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * If activation, logs in and redirects to home.
     */
    public function actionCreate() {
        $models = array(
            'user' => new User,
            'contact' => new Contact,
            'clubHasUser' => new ClubHasUser(),
            'sponsor' => new Sponsor(),
        );

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($models);

        $this->loadValidateAndSave($models);

        $this->render('create', $models);
    }

    /**
     * Loads the models from the post/get superglobals and saves them to persitence, then
     * redirects to 'view' page if successfull. If activation, logs in and redirects to home.
     * 
     * @param array $models
     */
    protected function loadValidateAndSave($models) {
        /**
         * Must use $_REQUEST beacuse this arrays can come from GET (link to create action)
         * or POST (form submitting)
         */
        if (isset($_REQUEST['ClubHasUser'])) {
            $models['clubHasUser']->attributes = $_REQUEST['ClubHasUser'];
        }
        if (isset($_REQUEST['Sponsor'])) {
            $models['sponsor']->attributes = $_REQUEST['Sponsor'];
        }
        if (isset($_POST['User'], $_POST['Contact'])) {
            $models['user']->attributes = $_POST['User'];
            $models['contact']->attributes = $_POST['Contact'];
            if ($this->isRequiredFieldsFilled($models)) {
                $this->handleSave($models);
            }
        }
    }

    /**
     * Return true if all models are ready to be saved
     * @param CExtendedActiveRecord[] $models the models
     * @return boolean true if all models are ready to be saved to persitance
     */
    protected function isRequiredFieldsFilled($models) {
        /** @var User $user */
        $user = $models['user'];
        $userOK = $user->validate() && $user->isPasswordChangeOK();
        $contactOK = $models['contact']->validate();
        if (isset($models['sponsor'])) {
            /** @var Sponsor $sponsor */
            $sponsor = $models['sponsor'];
            $attributes = $sponsor->getAttributeNamesExcept(array('sponsorID'));
            $sponsorOK = ($sponsor->hasNotNullAttributes() ?
                $sponsor->validate($attributes) : true);
        } else {
            $sponsorOK = true;
        }
        return $userOK && $contactOK && $sponsorOK;
    }

    /**
     * Saves the models to persitence, allows the activation of the user
     *  and redirects to 'view' page if successfull. If activation, logs in and redirects to home.
     * 
     * @param CExtendedActiveRecord[] $models
     */
    protected function handleSave($models) {
        if ($models['user']->save(false)) {
            if ($models['contact']->hasNotNullAttributes() && $models['contact']->save(false)) {
                $this->relateContactWithUser($models);
            }
            if (isset($models['clubHasUser'])) {
                $this->saveClubHasUser($models);
            }
            if (isset($models['sponsor'])) {
                $this->saveSponsor($models);
            }
            /** @var User $user */
            $user = $models['user'];
            if ($user->scenario == 'activation') {
                $this->handleActivation($user);
            }
            $this->allowActivation($user);
            $this->redirect(array('view', 'id' => $models['user']->userID));
        }
    }

    /**
     * relates a user with a club
     * @param CExtendedActiveRecord[] $models assotiative array containing 'user' to be saved into 'clubHasUser'
     */
    protected function saveSponsor($models) {
        if ($this->isNewModelNotNull($models['sponsor'])) {
            $models['sponsor']->sponsorID = $models['user']->userID;
            $models['sponsor']->save();
        }
    }

    /**
     * Checks if this is a new model and is not null
     * @param CActiveRecord $model
     * @return boolean 
     */
    protected function isNewModelNotNull($model) {
        return $model !== null && $model->isNewRecord;
    }

    /**
     * relates a contact with a user
     * @param CExtendedActiveRecord[] $models assotiative array containing 'contact' to be saved into 'user'
     */
    protected function relateContactWithUser($models) {
        if (empty($models['user']->contact)) {
            $models['user']->contactID = $models['contact']->contactID;
            $models['user']->save(false);
            $models['user']->contact = $models['contact'];
        }
    }

    /**
     * relates a user with a club
     * @param CExtendedActiveRecord[] $models assotiative array containing 'user' to be saved into 'clubHasUser'
     */
    protected function saveClubHasUser($models) {
        if ($this->isNewModelNotNull($models['clubHasUser'])) {
            $models['clubHasUser']->startDate = date('Y-m-d');
            $models['clubHasUser']->userID = $models['user']->userID;
            $models['clubHasUser']->save();
        }
    }

    /**
     * handles a user activation and redirects to site/index
     * @param User $user 
     */
    protected function handleActivation($user) {
        $user->activate();
        $user->save(false);
        $identity = new UserIdentity($user->name, $user->password, $user->userID);
        $identity->login();
        $this->redirect(array('site/index'));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $models = array('user' => $this->loadModel($id));
        $models['contact'] = isset($models['user']->contact) ? $models['user']->contact : new Contact();

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($models);

        $this->renderUpdate($models);
    }

    /**
     * Renders the 'update' view
     * 
     * @param array $models
     */
    protected function renderUpdate($models) {
        $this->loadValidateAndSave($models);

        $this->render('update', array(
            'user' => $models['user'],
            'contact' => $models['contact'],
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $currentUser = User::model()->getLoggedInUser();
        /* @var $userType UserType */
        $userType = isset($_GET['userType']) ? UserType::model()->findByPk($_GET['userType']) : null;
        if ($userType != null) {
            if ($userType->isAthlete() && !$currentUser->canListAthletes()) {
                throw new CHttpException('403', 'Não pode listar atletas');
            }
            if ($userType->isCoach() && !$currentUser->canListCoaches()) {
                throw new CHttpException('403', 'Não pode listar treinadores');
            }
        }
        $filter = isset($_GET['name']) && $_GET['name'] !== "" ?
                array('name' => filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING)) : null;
        $data = $currentUser->getRelatedUsers($userType, $filter);
        $dataProvider = new CArrayDataProvider($data == null ? array() : $data);
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'userType' => $userType,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Activates a user.
     * @param String $activationHash
     * @param int $userID
     * @throws CHttpException
     */
    public function actionActivate($activationHash, $userID) {
        /** @var CExtendedActiveRecord[] $models */
        $models = array('user' => $this->loadModel($userID));
        $models['contact'] = $models['user']->contact;
        $models['user']->scenario = 'activation';

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($models);

        /** @var User $user */
        $user = $models['user'];
        if ($user->isActivated()) {
            throw new CHttpException(403, "O utilizador " . $user->name . " já foi ativado.");
        }
        if (!$user->isActivationHash($activationHash)) {
            throw new CHttpException(403, "Link incorreto para o utilizador " . $user->name . ".");
        }
        if ($user->canBeActivated($activationHash)) {
            $this->renderUpdate($models);
        }
    }

    public function actionRemoveFromClub($userID, $clubID) {
        $model = new ClubHasUser();
        $model->userID = $userID;
        $model->clubID = $clubID;
        $deleted = null;
        /** @var User $user */
        $user = User::model()->findByPk($userID);
        /** @var Club $club */
        $club = Club::model()->findByPk($clubID);
        $loggedInUser = User::getLoggedInUser();
        if (!($loggedInUser->isCoachOf($user) || $loggedInUser->isClubAdminOf($club))) {
            throw new CHttpException(303, 'Apenas treinadores de ' . $user->name . ' ou administradores de ' . $club->name .
                ' podem efetuar esta ação.');
        }
        if (isset($_POST['confirmedDeletion']) && $_POST['confirmedDeletion']) {
            $model = ClubHasUser::model()->findByAttributes(array(
                'userID' => $userID,
                'clubID' => $clubID,
                'userTypeID' => $_POST['ClubHasUser']['userTypeID'],
            ));
            if ($model === null) {
                throw new CHttpException(404, $user->name . ' não pertence a ' . $club->name);
            }
            $deleted = $model->delete();
            if ($deleted) {
                $this->render('removalFromClubConfirmed', array(
                    'deleted' => $deleted,
                    'userName' => $user->name,
                    'clubName' => $club->name,
                ));
                return true;
            }
        }
        if ($model === null) {
            throw new CHttpException(404, $user->name . ' não pertence a ' . $club->name);
        }
        $this->render('removeFromClub', array(
            'model' => $model,
            'deleted' => $deleted,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return User the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CActiveRecord[] $models the models to be validated
     */
    protected function performAjaxValidation($models) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($models);
            Yii::app()->end();
        }
    }

    /**
     * Compiles the operations to be presented to the user at the side menu in the views
     * rendered by this controller.
     * @param User $user the User model
     * @return array the array with the operations for the side menu
     */
    public function getSideMenuItems($user) {
        //initializing result array with common options
        //something like array('label' => 'Manage User', 'url' => array('admin')),
        $result = array(); //no common items at the moment
        if ($user instanceof User) {
            //options for non detailed view actions only (false)
            //options for detailed view actions only (true)
            $this->loadSideMenuOptions($result, $user, $user->primaryKey !== NULL);
        }
        return $result;
    }

    /**
     * @param $user User
     * @return array
     */
    protected function getDetailedViewsOptions($user) {
        //arrays of arguments to be passed as query string
        $sponsorAttributes = array(
            'athleteID' => $user->primaryKey,
            'startDate' => date('Y-m-d'),
        );
        $athlete = UserType::model()->getAthlete()->primaryKey;
        $coach = UserType::model()->getCoach()->primaryKey;
        $sponsor = UserType::model()->getSponsor()->primaryKey;
        $result = array(
            'addNewSponsor' => array(
                'option' => array(
                    'label' => 'Adicionar Patrocinador',
                    'url' => array('create', 'Sponsor' => $sponsorAttributes)
                ),
                'detailedView' => true,
                'selectedUserTypesAllowed' => array($athlete),
                'loggedInUserTypesAllowed' => array($coach)),
            'editAthlete' => array(
                'option' => array(
                    'label' => 'Editar Atleta',
                    'url' => array('update', 'id' => $user->userID, 'userTypeId' => $athlete)
                ),
                'detailedView' => true,
                'selectedUserTypesAllowed' => array($athlete),
                'loggedInUserTypesAllowed' => array($coach, $sponsor, $athlete)),
            'editCoach' => array(
                'option' => array(
                    'label' => 'Editar Treinador',
                    'url' => array('update', 'id' => $user->userID, 'userTypeId' => $coach)
                ),
                'detailedView' => true,
                'selectedUserTypesAllowed' => array($coach),
                'loggedInUserTypesAllowed' => array($coach)),
            'editSponsor' => array(
                'option' => array(
                    'label' => 'Editar Patrocinador',
                    'url' => array('update', 'id' => $user->userID, 'userTypeId' => $sponsor)
                ),
                'detailedView' => true,
                'selectedUserTypesAllowed' => array($sponsor),
                'loggedInUserTypesAllowed' => array($coach)),
            'viewPracticeSchedule' => array(
                'option' => array('label' => 'Ver Horário',
                    'url' => array('practiceSession/index', 'userID' => $user->userID)
                ),
                'detailedView' => true,
                'selectedUserTypesAllowed' => array($athlete),
                'loggedInUserTypesAllowed' => array($coach, $athlete, $sponsor)),
            'viewPracticeAttendance' => array(
                'option' => array('label' => 'Ver Assiduidade',
                    'url' => array('practiceSessionHistory/list', 'athleteID' => $user->userID)
                ),
                'detailedView' => true,
                'selectedUserTypesAllowed' => array($coach, $athlete),
                'loggedInUserTypesAllowed' => array($coach, $athlete, $sponsor)),
        );
        /** @var Club $club */
        foreach ($user->clubs as $club) {
            $result[] = array(
                'option' => array(
                    'label' => 'Desassociar de ' . $club->name,
                    'url' => array('user/removeFromClub', 'userID' => $user->userID, 'clubID' => $club->primaryKey)
                ),
                'detailedView' => true,
                'selectedUserTypesAllowed' => array($athlete, $coach),
                'loggedInUserTypesAllowed' => array(),
                'adminOfClub' => $club->primaryKey,
            );
        }
        return $result;
    }

    /**
     * Pushes the desired operation arrays into the given array
     * @param array $result the array that gets the results pushed to
     * @param User $user the User model being rendered
     * @param boolean $detailedView whether to fetch the detailed view operations (true)
     * or the non detailed ones (false)
     */
    public function loadSideMenuOptions(&$result, $user, $detailedView) {
        if (!Yii::app()->user->isGuest) {
            $loggedInUser = User::model()->getLoggedInUser();
            $loggedInUserTypes = $loggedInUser->getUserTypesPk();
            $loggedInManagedClubs = $loggedInUser->clubsManaged;
            $selectedUserTypes = $user->getUserTypesPK();
            foreach ($this->getDetailedViewsOptions($user) as $detailOption) {
                if ($this->sideMenuOptionIsVisible($detailOption, $detailedView, $loggedInUserTypes,
                    $selectedUserTypes, $loggedInManagedClubs)) {
                    array_push($result, $detailOption['option']);
                }
            }
        }
    }

    protected function sideMenuOptionIsVisible($detailOption, $detailedView, $loggedInUserTypes, $selectedUserTypes, $loggedInManagedClubs) {
        //check if this option is OK for this action (create, update, view, index, ...)
        $isSystemAdmin = User::model()->getLoggedInUser()->isSystemAdmin();
        $action = $detailOption['detailedView'] == $detailedView;
        //check if this option is OK for the selected user (update/1, update/4, ...)
        if (count($selectedUserTypes) != 0) {
            $selectUser = CHelper::arraysIntersect($detailOption['selectedUserTypesAllowed'], $selectedUserTypes);
        } else if ($isSystemAdmin) {
            $selectUser = true;
        } else {
            throw new CHttpException('403', 'This user has no UserType defined!');
        }
        //check if this option is OK for the logged in user
        $loggedInUser = $isSystemAdmin ? true :
            (isset($detailOption['adminOfClub']) ?
                CHelper::inArray($detailOption['adminOfClub'],$loggedInManagedClubs) :
                CHelper::arraysIntersect($detailOption['loggedInUserTypesAllowed'], $loggedInUserTypes));
        return $action && $selectUser && $loggedInUser;
    }

    /**
     * @param User $user
     */
    public function allowActivation($user) {
        $mailActivator = new MailActivation();
        $mailActivator->user = $user;
        $mailActivator->allowActivation();
    }

}
