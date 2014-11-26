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
        $user = User::getLoggedInUser();
        return array(
            array('allow',
                'actions' => array('index'), //all logged in users can go to index. index only redirects according to usertype
                'users' => array('@'),
            ),
            array('allow', // allow admin to all actions
                'actions' => array('register', 'list', 'admin'),
                'users' => array('@'),
                'expression' => array($user, 'isSystemAdmin'),
            ),
            array('allow',
                'actions' => array('list'),
                'users' => array('@'),
                'expression' => array($user, 'canListAttendance'),
            ),
            array('allow',
                'actions' => array('register'),
                'users' => array('@'),
                'expression' => array($user, 'canRegisterAttendance'),
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
			$model->setDefaults();
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
        } else {
			$model->setDefaults();
			$model->loadHistoryFromDB();
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
        $model = new PracticeSessionHistoryHasAthlete('search');
        $model->practiceSessionHistory = new PracticeSessionHistory('search');
        $model->showCancelled = false;
        $model->practiceSessionHistory->date = (new DateTime())->format('Y-m');
        if (isset($_GET['PracticeSessionHistoryHasAthlete'])) {
            $model->attributes = $_GET['PracticeSessionHistoryHasAthlete'];
        }
        if (isset($_GET['PracticeSessionHistory'])) {
            $model->practiceSessionHistory->attributes = $_GET['PracticeSessionHistory'];
        }
        if ($model->practiceSessionHistory->clubID === null) {
            /** @var User $athlete */
            $athlete = User::model()->findByPk($athleteID);
            $model->practiceSessionHistory->clubID = $athlete->athleteClubs[0]->primaryKey;
        }
        $model->athleteID = $athleteID;
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