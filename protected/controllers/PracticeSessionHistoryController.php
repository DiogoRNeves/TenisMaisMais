<?php

class PracticeSessionHistoryController extends Controller {

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
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'create', 'update', 'register', 'list'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
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
        $model = new PracticeSessionHistory;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['PracticeSessionHistory'])) {
            $model->attributes = $_POST['PracticeSessionHistory'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->practiceSessionHistoryID));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['PracticeSessionHistory'])) {
            $model->attributes = $_POST['PracticeSessionHistory'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->practiceSessionHistoryID));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Register session history and attendance.
     */
    public function actionRegister() {
        $model = new PracticeSessionHistoryRegistryForm;

    // Uncomment the following line if AJAX validation is needed
    $this->performAjaxValidation($model);

        if (isset($_GET['PracticeSessionHistoryRegistryForm'])) {
            $model->attributes = $_GET['PracticeSessionHistoryRegistryForm'];
            if (!$model->autoSubmit) {
                if ($model->validate() && $model->save(false)) {
                    Yii::app()->user->setFlash('savedPracticeSessionAttendance',
                        array(true, "Informação gravada com sucesso!"));
                } else {
                    Yii::app()->user->setFlash('savedPracticeSessionAttendance',
                        array(false, "Não foi possível gravar os dados."));
                }
            } elseif ($model->isPracticeSessionAllowed()) {
                $model->loadHistoryFromDB();
            }
        }

        $this->render('register', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
// we only allow deletion via POST request
            $this->loadModel($id)->delete();

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Redirects to correct action, depending on logged user type
     */
    public function actionIndex() {
        $loggedUser = User::getLoggedInUser();
        if ($loggedUser->isCoach()) {
            $this->redirect('register');
        }
        $athleteID = $loggedUser->isSponsor() ? $loggedUser->sponsoredAthletes[0]->primaryKey : $loggedUser->primaryKey;
        $this->redirect(array('list', 'athleteID' => $athleteID));
    }

    /**
     * Lists all models.
     * @param $athleteID
     */
    public function actionList($athleteID) {
        //$dataProvider = new CActiveDataProvider('PracticeSessionHistoryHasAthlete');
        //$criteria = new CDbCriteria();
        //$criteria->compare('athleteID', $athleteID);
        //$dataProvider->setCriteria($criteria);
        $model = new PracticeSessionHistoryHasAthlete('search');
        if (!empty($_GET['PracticeSessionHistoryHasAthlete'])) {
            $model->attributes = $_GET['PracticeSessionHistoryHasAthlete'];
        }
        $model->athleteID = $athleteID;
        //$model->clubName = "diog";
        $this->render('list', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new PracticeSessionHistory('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PracticeSessionHistory'])) {
            $model->attributes = $_GET['PracticeSessionHistory'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = PracticeSessionHistory::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'practice-session-history-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
