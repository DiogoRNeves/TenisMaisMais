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
				'actions' => array('create', 'index', 'view', 'update'),
				'users' => array('@'),
				'expression' => array($user, 'isSystemAdmin'),
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
		$this->render('create');
	}


	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionUpdate($competitivePlanID)
	{
		$this->render('update');
	}

	public function actionView($competitivePlanID)
	{
		$this->render('view');
	}

	public function actionList()
	{
		$this->render('list');
	}
}