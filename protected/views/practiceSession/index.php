<?php
/* @var $this PracticeSessionController */
/* @var $dataProvider CActiveDataProvider */
/* @var $user User */

$this->breadcrumbs = array(
    'Practice Sessions',
);

$this->menu = $user->getOtherPracticeSessionUserLinks();
?>

<h2>Horário de <?php echo $user->name; ?></h2>

<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/moment.js');
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-confirmation.js');
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/customFullCalendar.js');

$selectJS = new CJavaScriptExpression("function(start, end) { dSelect(start, end); }");
$loggedUser = User::model()->findByPk(Yii::app()->user->id);
$canWriteSchedule = $user->canScheduleBeUpdated($loggedUser);

$this->widget('ext.EFullCalendar.EFullCalendar', array(
    //'themeCssFile'=>'cupertino/jquery-ui.min.css',
    'id' => 'calendar',
    'lang' => 'pt',
    'options' => array(
        'header' => array(
            'left' => null,
            'center' => null,
            'right' => null
        ),
        'firstDay' => 1, //monday
        'columnFormat' => array('week' => 'dddd'),
        'defaultView' => 'agendaWeek',
        'selectable' => $canWriteSchedule,
        'select' => $canWriteSchedule ? $selectJS : '',
        'eventClick' => new CJavaScriptExpression("function(calEvent, jsEvent, view) { showForm(calEvent, true); }"),
        'eventDrop' => new CJavaScriptExpression('function(event, delta, revertFunc) { dEventChange(event, revertFunc); }'),
        'eventResize' => new CJavaScriptExpression('function(event, delta, revertFunc) { dEventChange(event, revertFunc); }'),
        //'eventRender' => new CJavaScriptExpression("function(eventData) { dEventRender(eventData); }"),
        //next line generates error. possibly because of jquery version?
        //'eventDataTransform' => new CJavaScriptExpression("function(eventData) { dEventDataTransform(eventData); }"),
        'events' => array(
            'url' => $this->createUrl('practiceSession/assync'),
            'data' => array('userID' => $user->userID),
            'editable' => $canWriteSchedule,
            'error' => new CJavaScriptExpression("function() { alert('there was an error fetching the data'); }"),
        ),
        'minTime' => '09:00:00',
        'maxTime' => '23:00:00',
        'axisFormat' => 'H:mm',
        'allDaySlot' => false,
    )
));

$this->renderPartial('_modalForm', array('user' => $user));

