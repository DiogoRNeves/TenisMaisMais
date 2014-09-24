<?php

class PracticeSessionController extends Controller {

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
                'actions' => array('admin', 'index', 'assync', 'delete'),
                'users' => array('@'),
                'expression' => array($user, 'isSystemAdmin'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index'),
                'users' => array('@'),
                'expression' => array($user, 'canViewUser'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('assync', 'delete'),
                'users' => array('@'),
                //'expression' => array($user, 'canUpdatePracticeSession'), TODO: Work this out
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionAssync() {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET':
                $result = $this->getAjaxGetResult();
                break;
            default:
                $result = $this->getAjaxPostResult();
                break;
        }
        header('Content-Type: application/json');
        echo CJavaScript::jsonEncode($result);
    }

    protected function getAjaxGetResult() {
        if (!isset($_GET['start'], $_GET['end'], $_GET['userID'])) {
            echo CJavaScript::jsonEncode(array('status' => 403, 'message' => 'params missing'));
            Yii::app()->end();
        }
        /* @var $user User */
        $user = User::model()->findByPk($_GET['userID']);
        return $user->getFullCalendarPracticeSessionEvents($_GET['start']);
    }

    protected function getAjaxPostResult() {
        if ($_POST['PracticeSession'][PracticeSession::model()->tableSchema->primaryKey] != '') {
            $practiceSession = $this->loadModel($_POST['PracticeSession'][PracticeSession::model()->tableSchema->primaryKey]);
        } else {
            $practiceSession = new PracticeSession();
        }
        $practiceSession->attributes = $_POST['PracticeSession'];
        $this->performAjaxValidation($practiceSession);
        $practiceSession->validate();
        if (isset($_POST['PracticeSession']['formAthletes'])) {
            $practiceSession->formAthletes = $_POST['PracticeSession']['formAthletes'];
        } else {
            $practiceSession->addError('athletes', 'There must be at least an athete on practice!');
        }
        $saved = !$practiceSession->hasErrors() ? $practiceSession->save(false) : false;
        return array(
            'status' => $saved ? 400 : 401,
            'message' => $saved ? 'saved to DB' : 'unable to save on DB',
            'errors' => $practiceSession->getErrorsString(),
        );
    }

    /**
     * Deactivates a practiceSession.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        /* @var $model PracticeSession */
        $model = $this->loadModel($id);
        $model->activePracticeSession = 0;
        $deleted = $model->save();
        $result = array(
            'status' => $deleted ? 400 : 401,
            'message' => $deleted ? 'deleted Practice Session ' . $id . ' from DB' : 'Could not delete from DB',
        );
        header('Content-Type: application/json');
        echo CJavaScript::jsonEncode($result);
    }

    /**
     * Lists all models.
     */
    public function actionIndex($userID) {
        /* @var $user User */
        $user = User::model()->findByPk($userID);
        if ($user == null) {
            throw new CHttpException('404', "The requested page does not exist.");
        }
        if (!($user->isCoach() || $user->isAthlete())) {
            throw new CHttpException('403', 'Functionality available only for coaches and athletes.');
        }
        $dataProvider = new CActiveDataProvider('PracticeSession');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'user' => $user,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new PracticeSession('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PracticeSession']))
            $model->attributes = $_GET['PracticeSession'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return PracticeSession the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = PracticeSession::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param PracticeSession $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'practice-session-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
