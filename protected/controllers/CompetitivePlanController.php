<?php

class CompetitivePlanController extends Controller
{
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
		//TODO: correct this rules
		$user = User::getLoggedInUser();
		return array(
			array('allow', // allow admin user to perform 'admin' actions
				'actions' => array('create', 'index', 'view', 'update', 'addTournament', 'removeTournament', 'deactivate'),
				'users' => array('@'),
				//'expression' => array($user, 'isSystemAdmin'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array('index'),
				'users' => array('@'),
				//'expression' => array($user, 'canListClubs'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array('view'),
				'users' => array('@'),
				//'expression' => array($user, 'canViewClub'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array('update', 'create'),
				'users' => array('@'),
				//'expression' => array($user, 'canUpdateClub'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionCreate()
	{
		/* @var AthleteGroup $model */
		$model = new AthleteGroup;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		$result = array('error' => 'no action');

		if (isset($_POST['AthleteGroup'])) {
			$model->attributes = $_POST['AthleteGroup'];
			$result = $model->save() ? $model : array('error' => $model->getErrorsString());
		}
		header('Content-Type: application/json');
		echo CJSON::encode($result);
		Yii::app()->end();
	}


	public function actionIndex()
	{
		$model = new AthleteGroup;
		$loggedUser = User::getLoggedInUser();
		if (count($loggedUser->coachClubs) === 1) {
			$model->clubID = $loggedUser->coachClubs[0];
		}

		$dataProvider = $loggedUser->searchAthleteGroup();

		$model->friendlyName = 'Plano Competitivo #' . ($dataProvider->getTotalItemCount() + 1);
		$model->includeMale = true;
		$model->includeFemale = true;

		$this->render('index', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionUpdate($id)
	{
		/* @var AthleteGroup $model */
		$model = $this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		$result = array('no action');

		if (isset($_POST['AthleteGroup'])) {
			$model->attributes = $_POST['AthleteGroup'];
			$result = array($model->save() ? 'saved' : 'error');
		}

		echo CJSON::encode($result);
		Yii::app()->end();
	}

	public function actionView($id)
	{
		$model = $this->loadModel($id);
		$federationTournamentSearch = new FederationTournament('search');
		$federationTournamentSearch->unsetAttributes();
		$temp = Yii::app()->request->getParam('FederationTournament');
		if ($temp === null) {
			$federationTournamentSearch->ageBands = $model->getAgeBandIDs();
			$federationTournamentSearch->searchDateRange = CHelper::getTodayDate() . " a " . CHelper::getTodayDate("Y-m-t");
		} else {
			$federationTournamentSearch->attributes = $temp;
		}
		$this->render('view', array('model' => $model,
			'federationTournamentSearch' => $federationTournamentSearch));
	}

	public function actionAddTournament($federationTournamentID, $athleteGroupID) {
		$model = new CompetitivePlan;
		$model->federationTournamentID = $federationTournamentID;
		$model->athleteGroupID = $athleteGroupID;
		header('Content-Type: application/json');
		echo CJSON::encode($model->save() ? FederationTournament::model()->findByPk($federationTournamentID) : array('error' => $model->getErrorsString()));
		Yii::app()->end();
	}

	public function actionRemoveTournament($federationTournamentID, $athleteGroupID) {
		/** @var CompetitivePlan $model */
		$model = CompetitivePlan::model()->findByAttributes(array(
			'federationTournamentID' => $federationTournamentID,
			'athleteGroupID' => $athleteGroupID,
		));
		header('Content-Type: application/json');
		echo CJSON::encode($model->delete() ? FederationTournament::model()->findByPk($federationTournamentID) : array('error' => $model->getErrorsString()));
		Yii::app()->end();
	}

	public function actionDeactivate($id) {
		/** @var AthleteGroup $model */
		$model = AthleteGroup::model()->findByPk($id);
		if ($model === null) { throw new CHttpException(404,"AthleteGroup $id not found.");	}
		$model->active = false;
		if ($model->save()) {
			$this->redirect(array($this->id . '/index'));
		}
		throw new CHttpException(500,"AthleteGroup $id could not be deleted.");
	}

	public function actionList()
	{
		$this->render('list');
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param $id
	 * @return AthleteGroup
	 * @throws CHttpException
	 * @internal param the $integer ID of the model to be loaded
	 */
	public function loadModel($id) {
		$model = AthleteGroup::model()->findByPk($id);
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
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'athlete-group-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function getFormAction()
	{
		$test = $this->action->id === 'view' ? 'update' : 'create';
		return $test;
	}
}