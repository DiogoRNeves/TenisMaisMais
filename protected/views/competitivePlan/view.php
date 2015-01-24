<?php
/* @var $this CompetitivePlanController */

$this->breadcrumbs=array(
	'Competitive Plan'=>array('/competitivePlan'),
	'View',
);

$this->menu = array(
	array('label' => 'Adicionar torneio', 'url' => 'javascript: alert("show search tounament modal now");'),
	array('label' => 'Editar Atletas do Plano', 'url' => 'javascript: alert("show update form modal now");'),
);

?>
<h1>Plano Competitivo: <?php echo $this->id . '/' . $this->action->id; ?></h1>

<?php

$this->widget(
	'booster.widgets.TbTabs',
	array(
		'type' => 'tabs', // 'tabs' or 'pills'
		'tabs' => array(
			'tab1' => array(
				'label' => 'Calendário',
				'content' => $this->renderPartial('_calendar', array(), true),
				'active' => true
			),
			'tab2' => array(
				'label' => 'Lista',
				'content' => $this->renderPartial('_tournamentPlanList', array(), true),
				//'active' => true
			),
		),
	)
);
