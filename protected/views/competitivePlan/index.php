<?php
/* @var $this CompetitivePlanController */

$this->breadcrumbs = array(
    'Plano Competitivo',
);
?>
<h1>Plano Competitivo</h1>

<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/moment.js');

$competitivePlanOutput = $this->widget('ext.EFullCalendar.EFullCalendar', array(
    //'themeCssFile'=>'cupertino/jquery-ui.min.css',
    'id' => 'competitivePlan',
    'lang' => 'pt',
    'options' => array(
    //'eventClick' => new CJavaScriptExpression("function(calEvent, jsEvent, view) { showForm(calEvent, true); }"),
    //'events' => array(
    //    'url' => $this->createUrl('practiceSession/assync'),
    //    'data' => array('userID' => $user->userID),
    //    'editable' => $canWriteSchedule,
    //    'error' => new CJavaScriptExpression("function() { alert('there was an error fetching the data'); }"),
    //),
    )
        ), true);

$this->widget('bootstrap.widgets.TbTabs', array(
    'type' => 'tabs',
    'tabs' => array(
        array(
            'label' => 'Home',
            'content' => 'Home Content'
        ),
        array(
            'label' => 'Calendário',
            'content' => $competitivePlanOutput,
            'active' => true
        ),
        array(
            'label' => 'Mais',
            'content' => 'Mais'
        ),
    )
));
