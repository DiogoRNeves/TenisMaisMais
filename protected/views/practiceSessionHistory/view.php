<?php
$this->breadcrumbs=array(
	'Practice Session Histories'=>array('index'),
	$model->practiceSessionHistoryID,
);

$this->menu=array(
array('label'=>'List PracticeSessionHistory','url'=>array('index')),
array('label'=>'Create PracticeSessionHistory','url'=>array('create')),
array('label'=>'Update PracticeSessionHistory','url'=>array('update','id'=>$model->practiceSessionHistoryID)),
array('label'=>'Delete PracticeSessionHistory','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->practiceSessionHistoryID),'confirm'=>'Are you sure you want to delete this item?')),
array('label'=>'Manage PracticeSessionHistory','url'=>array('admin')),
);
?>

<h1>View PracticeSessionHistory #<?php echo $model->practiceSessionHistoryID; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'practiceSessionHistoryID',
		'startTime',
		'endTime',
		'date',
		'coachID',
		'clubID',
),
)); ?>
