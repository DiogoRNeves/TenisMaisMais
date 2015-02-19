<?php
/* @var $model AthleteGroup */
/* @var $this CompetitivePlanController */
/* @var $dataProvider CArrayDataProvider */

$this->breadcrumbs=array(
	'Competitive Plan',
);

$addMenu = array(
    array('label' => 'Criar Plano', 'url' => '#', 'linkOptions' => array(
        'data-toggle' => 'modal',
        'data-target' => '#competitivePlan',
    )));

$this->menu = User::getLoggedInUser()->canCreateCompetitivePlan() ? $addMenu : null;
?>
<h1>Planos Competitivos</h1>

<p>
	<?php
	$labels = AthleteGroup::model()->attributeLabels();
	$gridColumns = array(
		array(
			'name' => 'friendlyName',
			'header' => $labels['friendlyName'],
			'type' => 'raw',
			'value' => 'CHtml::link($data->friendlyName, Yii::app()->createUrl("'.$this->id.'/view",array("id"=>$data->primaryKey)))',
		),
		'minAge::'.$labels['minAge'],
		'maxAge::'.$labels['maxAge'],
		array(
			'name' => 'minPlayerLevelID',
			'header' => $labels['minPlayerLevelID'],
			'value' => '$data->minPlayerLevel === null ? null : $data->minPlayerLevel->playerLevel',
		),
		array(
			'name' => 'maxPlayerLevelID',
			'header' => $labels['maxPlayerLevelID'],
			'value' => '$data->maxPlayerLevel === null ? null : $data->maxPlayerLevel->playerLevel',
		),
		array(
			'name' => 'includeMale',
			'header' => $labels['includeMale'],
			'value' => '$data->includeMale ? "Sim" : "Não"',
		),
		array(
			'name' => 'includeFemale',
			'header' => $labels['includeFemale'],
			'value' => '$data->includeFemale ? "Sim" : "Não"',
		),
	);

	if (count(User::getLoggedInUser()->clubs) > 1) {
		$gridColumns[] = array(
				'name' => 'clubID',
				'header' => Club::model()->getAttributeLabel(Club::model()->tableSchema->primaryKey),
				'value' => '$data->club->name',
			);
	}

	$this->widget('booster.widgets.TbExtendedGridView', array(
		'id' => 'athlete-group-list',
		'responsiveTable' => true,
		'fixedHeader' => true,
		'headerOffset' => 50,
		'dataProvider' => $dataProvider,
		'type' => 'striped',
		'columns' => $gridColumns,
		//'filter' => new AthleteGroup,
	));
	?>
</p>

<?php $this->renderPartial('_form', array('model' => $model)); ?>
