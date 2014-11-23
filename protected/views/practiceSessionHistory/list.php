<?php
/** @var CActiveDataProvider $dataProvider */

$this->breadcrumbs=array(
	'Practice Session Histories',
);

$this->menu=array(
array('label'=>'Registar Assiduidade','url'=>array('register')),
array('label'=>'Mais Opções (ADMIN)','url'=>array('admin')),
);
?>

<?php
//TODO get jQuery BootGrid here!
$dataProvider->pagination = false;
$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        'practiceSessionHistory.date',
        'practiceSessionHistory.startTime',
        'practiceSessionHistory.endTime',
        'athlete.name',
        'attendanceType.description'
    ),
));
?>
