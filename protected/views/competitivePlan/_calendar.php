<?php
/**
 * Created by PhpStorm.
 * User: diogoneves
 * Date: 24/01/15
 * Time: 02:38
 */

$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/moment.js');

$this->widget('ext.EFullCalendar.EFullCalendar', array(
        //'themeCssFile'=>'cupertino/jquery-ui.min.css',
        'id' => 'competitivePlanCalendar',
        'lang' => 'pt',
        'options' => array(
            //'header' => array(
            //	'left' => null,
            //	'center' => null,
            //	'right' => null
            //),
            //'firstDay' => 1, //monday
            //'columnFormat' => array('week' => 'dddd'),
            'selectable' => false,
            //'select' => '',
            //'eventClick' => new CJavaScriptExpression("function(calEvent, jsEvent, view) { showForm(calEvent, true); }"),
            'events' => array()
            //	'url' => $this->createUrl('practiceSession/assync'),
            //	'data' => array('userID' => 1),
            //	'editable' => false,
            //	'error' => new CJavaScriptExpression("function() { alert('there was an error fetching the data'); }"),
        ),
    )
);