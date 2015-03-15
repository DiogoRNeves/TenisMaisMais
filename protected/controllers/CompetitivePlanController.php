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
		return array(
			array('allow', // allow admin user
				'actions' => array('index'),
				'users' => array('@'),
			),
            array('allow', // allow admin user
                'actions' => array('view', 'downloadPDF'),
                'users' => array('@'),
                'expression' => array($this, 'canLoggedUserViewPlan'),
            ),
			array('allow', // allow authenticated user
				'actions' => array('create', 'update', 'addTournament', 'removeTournament', 'deactivate'),
				'users' => array('@'),
                'expression' => array($this, 'canLoggedUserEditPlan'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

    /**
     * @return AthleteGroup|null
     */
    public function getAthleteGroup() {
        $athleteGroupID = Yii::app()->request->getParam('id');
        if ($athleteGroupID === null) { return null; }
        /** @var AthleteGroup $athleteGroup */
        return AthleteGroup::model()->findByPk($athleteGroupID);
    }

    /**
     * @return bool
     */
    public function canLoggedUserViewPlan() {
        $athleteGroup = $this->getAthleteGroup();
        return $athleteGroup === null ? false : $athleteGroup->appliesTo(User::getLoggedInUser());
    }

    /**
     * @return bool
     */
    public function canLoggedUserEditPlan() {
        $athleteGroup = $this->getAthleteGroup();
        return $athleteGroup === null ? false : $athleteGroup->canBeUpdatedBy(User::getLoggedInUser());
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
        $federationTournamentSearch = new FederationTournament('search');
        $federationTournamentSearch->unsetAttributes();
        $temp = Yii::app()->request->getParam('FederationTournament');
        if ($temp === null) {
            $federationTournamentSearch->ageBands = $loggedUser->getAgeBandIDs();
            $federationTournamentSearch->searchDateRange = CHelper::getTodayDate() . " a " . CHelper::getTodayDate("Y-m-t");
        } else {
            $federationTournamentSearch->attributes = $temp;
        }

		$dataProvider = $loggedUser->searchAthleteGroup();

		$model->friendlyName = 'Plano Competitivo #' . ($dataProvider->getTotalItemCount() + 1);
		$model->includeMale = true;
		$model->includeFemale = true;

		$this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
            'federationTournamentSearch' => $federationTournamentSearch
        ));
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

        header('Content-Type: application/json');
		echo CJSON::encode($result);
		Yii::app()->end();
	}

	public function actionView($id)
	{
		$model = $this->loadModel($id);
		$temp = Yii::app()->request->getParam('AthleteGroup');
		if ($temp !== null) {
			$model->showPastEvents = $temp['showPastEvents'];
		}
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

	public function actionDownloadPdf($id, $showPastEvents) {
		/** @var AthleteGroup $model */
		$model = AthleteGroup::model()->findByPk($id);
		$model->showPastEvents = $showPastEvents;

		/*
		/** @var HTML2PDF $html2pdf *//*
		$html2pdf = Yii::app()->ePdf->HTML2PDF();
		$html2pdf->WriteHTML($this->renderPartial('_plansTournaments', array('model' => $model), true));
		$html2pdf->Output("Teste.pdf", 'D');
		*/

		$this->renderPartial('_pdf', array('model' => $model));

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
	 * @param CModel $model the model to be validated
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