<?php
/* @var $this CompetitivePlanController
 * @var $model AthleteGroup
 * @var $federationTournamentSearch FederationTournament
 */

$this->breadcrumbs=array(
	'Plano Competitivo'=>array('/competitivePlan'),
	'Ver',
);

$editMenu = array(
    array('label' => 'Adicionar torneio', 'url' => '#', 'linkOptions' => array(
        'data-toggle' => 'modal',
        'data-target' => '#searchTournament',
    )),
    array('label' => 'Editar Atletas do Plano', 'url' => '#', 'linkOptions' => array(
        'data-toggle' => 'modal',
        'data-target' => '#competitivePlan',
    )),
    array('label' => 'Eliminar Plano Competitivo', 'url' => '#', 'linkOptions' => array(
        'onclick' => "js:bootbox.confirm({
			title: 'Eliminar Plano Competitivo',
			message: 'Tem a certeza que quer eliminar este Plano Competitivo?',
			buttons: {
				'cancel': {
					label: 'Não eliminar',
					className: 'btn-default pull-left'
				},
				'confirm': {
					label: 'Sim, eliminar',
					className: 'btn-danger pull-right'
				}
			},
			callback: function(result) {
				if (result) {
					 window.location = '" . $this->createUrl('deactivate', array('id' => $model->athleteGroupID)) . "';
				}
			}
		})",
    )),
);

$canEditAthleteGroup = User::getLoggedInUser()->canEditAthleteGroup($model);

$this->menu = $canEditAthleteGroup ? $editMenu : null;

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

if ($canEditAthleteGroup) {
    $this->renderPartial('_searchTournament', array('model' => $model,
        'federationTournamentSearch' => $federationTournamentSearch));

    $this->renderPartial('_form', array('model' => $model));
}
