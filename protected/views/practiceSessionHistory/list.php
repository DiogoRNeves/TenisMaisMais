<?php
/** @var PracticeSessionHistoryHasAthlete $model */
/** @var PracticeSessionController $this */


$this->breadcrumbs=array(
	'Practice Session Histories',
);

$this->menu=array(
array('label'=>'Registar Assiduidade','url'=>array('register')),
array('label'=>'Mais Opções (ADMIN)','url'=>array('admin')),
);
?>

<?php
/** @var TbActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type' => 'horizontal',
    'method' => 'GET',
    ));
?>

<?php

/** @var User $athlete */
$athlete = User::model()->findByPk($model->athleteID);
if (count($athlete->athleteClubs)>1) {
    echo $form->select2Row($model, 'clubID',  array(
        'data' => $athlete->getAthleteClubsOptions(),
        'options' => array(
            "placeholder" => "Selecione clube",
        ),
        'events' => array(
            'change' => 'js:function($el, status, e){$("#'.$form->getId().'").submit();}',
        ),
    ));
}
?>

<?php /** @var PracticeSessionHistoryRegistryForm $model */
$datePicker = $form->datePickerRow($model, 'date', array(
    'options' => array(
        'format' => "yyyy-mm",
        'endDate' => 'today',
        'viewMode' => "months",
        'minViewMode' => "months",
    ),
    'events' => array(
        'change' => 'js:function($el, status, e){$("#'.$form->getId().'").submit();}',
    ),
));
echo $datePicker;?>

<?php $this->endWidget(); ?>

<?php
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'responsiveTable' => true,
    'dataProvider' => $model->search(),
    'type' => 'striped',
    'columns' => array(
        'practiceSessionHistory.date',
        'practiceSessionHistory.timeString',
        array(
            //'name' => 'club.name',
            'header' => 'Clube',
            //'filter' => CHtml::activeTextField($model, 'clubName'),
            'value' => '$data->club->name',
        ),
        'attendanceType.listDataTextField',
    ),
));
?>
