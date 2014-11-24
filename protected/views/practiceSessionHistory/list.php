<?php
/** @var PracticeSessionHistoryHasAthlete $model */
/** @var PracticeSessionController $this */


$this->breadcrumbs=array(
	'Registo de Assiduidade',
);

//$this->menu=$this->getMenuOptions();

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
        'value' => '$data->practiceSessionHistory->getDateString()',
    ),
    array(
        'header' => 'Dia da Semana',
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
$athlete = User::model()->findByPk($model->athleteID);
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
            'value' => '$data->club->name',
        );
}
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

<?php /** @var PracticeSessionHistoryRegistryForm $model */
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

<?php
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'id' => 'ajaxGridView',
    'responsiveTable' => true,
    //'template' => "{extendedSummary}\n{items}\n{summary}",
    'dataProvider' => $model->search(),
    'type' => 'striped',
    'columns' => $gridViewColumns,
    'extendedSummary' => array(
        'title' => 'Resumo de assiduidade',
        'columns' => array(
            'attendanceType.listDataTextField' => array(
                'label'=>'Tipo de assiduidade',
                'types' => PracticeSessionAttendanceType::getTypesAndLabels(),
                'class'=>'TbPercentOfTypeEasyPieOperation',
                'chartOptions' => array(
                    'barColor' => '#333',
                    'trackColor' => '#999',
                    'lineWidth' => 8 ,
                    'lineCap' => 'square'
                ),
            )
        )
    ),
    'extendedSummaryOptions' => array(
        'class' => 'well pull-right',
    //    'style' => 'width:300px'
    ),
));
?>
