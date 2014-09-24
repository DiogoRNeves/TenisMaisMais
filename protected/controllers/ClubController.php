<?php

class ClubController extends Controller {
    //@todo update this controller and respective _form.php in views folder
    //to also have information from the Home model

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
        $user = User::model()->findByPk(Yii::app()->user->id);
        return array(
            array('allow', // allow admin user to perform 'admin' actions
                'actions' => array('admin', 'create', 'index', 'view', 'update'),
                'users' => array('@'),
                'expression' => array($user, 'isSystemAdmin'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index'),
                'users' => array('@'),
                'expression' => array($user, 'canListClubs'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('view'),
                'users' => array('@'),
                'expression' => array($user, 'canViewClub'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('update'),
                'users' => array('@'),
                'expression' => array($user, 'canUpdateClub'),
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
     */
    public function actionCreate() {
        $club = new Club;
        $club->home = new Home;
        $club->contact = new Contact('club');

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation(array($club, $club->home, $club->contact));

        $this->loadValidateAndSave($club, $club->home, $club->contact);

        $this->render('create', array(
            'model' => $club,
        ));
    }

    /**
     * Loads the models from the post superglobal and saves them to persitence, then
     * redirects to 'view' page if successfull.
     * 
     * @param Club $club
     * @param Home $home
     * @param Contact $contact
     */
    protected function loadValidateAndSave($club, $home, $contact) {
        if (isset($_POST['Club'], $_POST['Home'], $_POST['Contact'])) {

            $club->attributes = $_POST['Club'];
            $home->attributes = $_POST['Home'];
            $contact->attributes = $_POST['Contact'];

            if ($home->validate() && $contact->validate()) {
                $this->handleSave($club, $home, $contact);
            }
        }
    }

    /**
     * Saves the models to persitence and redirects to 'view' page if successfull.
     * 
     * @param Club $club
     * @param Home $home
     * @param Contact $contact
     */
    protected function handleSave($club, $home, $contact) {
        if ($club->home->isNewRecord) {
            if ($home->save(false) && $contact->save(false)) {
                $club->homeID = $home->homeID;
                $club->contactID = $contact->contactID;
            }
        }
        if ($club->save()) {
            if (!$club->adminUser->isCoachAt($club)) {
                $club->addCoach($club->adminUser);
            }
            $this->redirect(array('view', 'id' => $club->clubID));
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $club = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation(array($club, $club->home, $club->contact));

        $this->loadValidateAndSave($club, $club->home, $club->contact);

        $this->render('update', array(
            'model' => $club,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        /* @var $currentUser User */
        $currentUser = User::model()->findByPk(Yii::app()->user->getId());
        $clubs = $currentUser->isSystemAdmin() ? Club::model()->findAll() : $currentUser->coachClubs;
        $dataProvider = new CArrayDataProvider($clubs == null ? array() : $clubs);
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Club('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Club']))
            $model->attributes = $_GET['Club'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Club the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Club::model()->findByPk($id);
        if ($model !== null) {
            $model->scenario = 'club';
        }
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Club[] $models the model to be validated
     */
    protected function performAjaxValidation($models) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'club-form') {
            echo CActiveForm::validate($models);
            Yii::app()->end();
        }
    }
    
    /**
     * 
     * @param Club $model
     * @param User $user The user who gets this actions. Defaults to logged in user
     * @return array
     */
    public function getDetailViewsMenu($model, $user = null) {
        if ($user == null) {
            if (Yii::app()->user->isGuest) {
                return null;
            }
            $user = User::model()->findByPk(Yii::app()->user->id);
        }
        return $model->getClubActions($user);
    }

}
