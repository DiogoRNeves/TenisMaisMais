<?php
/** @var PracticeSessionHistoryHasAthlete $model */
/** @var PracticeSessionController $this */


$this->breadcrumbs=array(
	'Registo de Assiduidade',
);

//$this->menu=$this->getMenuOptions();

$balanceString = $model->getAthletePracticeBalanceString();

?>

<h1>Assiduidade de <?php echo $model->athlete->name; ?></h1>

<?php
/** @var TbActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type' => 'horizontal',
    'method' => 'GET',
    ));

$javascript = "function doAjax() {
    $.fn.yiiGridView.update(
        'ajaxGridView',
        {data: $('#" . $form->getId() . "').serialize()}
    );
};";

Yii::app()->clientScript->registerScript('doAjax', $javascript);
?>

<?php
$gridViewColumns =  array(
    array(
        'header' => 'Data',
        'name' => 'practiceSessionHistory.date',
        'value' => '$data->practiceSessionHistory->getDateString()',
    ),
    array(
        'header' => 'Dia da Semana',
        'name' => 'practiceSessionHistory.weekDay',
        'value' => '$data->practiceSessionHistory->getWeekdayString()',
    ),
    'practiceSessionHistory.timeString',
    'attendanceType.listDataTextField',
    array(
        'name' => 'practiceSessionHistory.cancelled',
        'value' => '$data->practiceSessionHistory->cancelled ? "Sim" : "Não"',
    ),
);
/** @var User $athlete */
$athlete = $model->athlete;
if (count($athlete->athleteClubs)>1) {
    echo $form->select2Row($model->practiceSessionHistory, 'clubID',  array(
        'data' => $athlete->getAthleteClubsOptions(),
        'options' => array(
            "placeholder" => "Selecione clube",
        ),
        'events' => array(
            'change' => 'js:function($el, status, e){doAjax();}',
        ),
    ),
    array(
        'label' => 'Clube',
    ));
    $gridViewColumns[] = array(
            'header' => 'Clube',
            'name' => 'club.name',
            'value' => '$data->practiceSessionHistory->club->name',
        );
}

$gridViewColumns[] = array(
        'header' => 'Treinador',
        'name' => 'coach.name',
        'value' => '$data->practiceSessionHistory->coach->name',
    );
?>

<?php
echo $form->toggleButtonRow($model, 'showCancelled',
    array(
        'enabledLabel' => 'SIM',
        'disabledLabel' => 'NÃO',
        'value' => false,
        'onChange' => 'js:function($el, status, e){doAjax();}',
    ),
    array(
        'label' => 'Mostrar treinos cancelados',
    ));
?>

<?php
echo $form->datePickerRow($model->practiceSessionHistory, 'date', array(
        'options' => array(
            'format' => "yyyy-mm",
            'endDate' => 'today',
            'viewMode' => "months",
            'minViewMode' => "months",
            'autoclose' => true,
        ),
        'htmlOptions' => array(
            'placeholder' => 'Selecione Mês',
        ),
        'events' => array(
            'change' => 'js:function($el, status, e){doAjax();}',
        )
    ),
    array(
        'label' => 'Ano-Mês',
    )
);

?>

<?php $this->endWidget(); ?>

Saldo de treinos: <?php echo $balanceString; ?>

<?php

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'id' => 'ajaxGridView',
    'responsiveTable' => true,
    'fixedHeader' => true,
    'headerOffset' => 40,
    //'template' => "{extendedSummary}\n{items}\n{summary}",
    'dataProvider' => $model->search(),
    'type' => 'striped',
    'columns' => $gridViewColumns,
    'extendedSummary' => array(
        'title' => 'Resumo de assiduidade',
        'columns' => array(
            'attendanceType.listDataTextField' => array(
                'label'=>'Assiduidade',
                'types' => PracticeSessionAttendanceType::getTypesAndLabels(),
                'class'=>'TbPercentOfTypeGooglePieOperation',
                'chartOptions' => array(
                    'title' => 'Assiduidade',
                    'is3D' => true,
                ),
            ),
        ),
    ),
    'extendedSummaryOptions' => array(
        'class' => 'well pull-right',
        'style' => 'width:420px'
    ),
));
?>
