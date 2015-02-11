<?php
/* @var $this CompetitivePlanController
 * @var $model AthleteGroup
 * @var $federationTournamentSearch FederationTournament
 */

$this->breadcrumbs=array(
	'Competitive Plan'=>array('/competitivePlan'),
	'View',
);

$this->menu = array(
	array('label' => 'Adicionar torneio', 'url' => '#', 'linkOptions'=>array(
			'data-toggle' => 'modal',
			'data-target' => '#searchTournament',
		)
	),
	array('label' => 'Editar Atletas do Plano', 'url' => '#', 'linkOptions'=>array(
		'data-toggle' => 'modal',
		'data-target' => '#competitivePlan',
		)
	),
	array('label' => 'Eliminar Plano Competitivo', 'url' => '#'),
);

?>
<h1>Plano Competitivo: <?php echo $model->friendlyName; ?></h1>

<?php

$this->widget(
	'booster.widgets.TbTabs',
	array(
		'type' => 'tabs', // 'tabs' or 'pills'
		'tabs' => array(/*
			array(
				'label' => 'Calendário',
				'content' => $this->renderPartial('_calendar', array('model' => $model), true),
				'active' => true
			),*/
			array(
				'label' => 'Lista de Torneios',
				'content' => $this->renderPartial('_tournamentPlanList', array('model' => $model), true),
				'active' => true
			),
			array(
				'label' => 'Atletas Abrangidos',
				'content' => $this->renderPartial('_athleteList', array('model' => $model), true),
				//'active' => true),
			),
		),
	)
);

$this->renderPartial('_searchTournament', array('model' => $model,
	'federationTournamentSearch' => $federationTournamentSearch));

$this->renderPartial('_form', array('model' => $model));
