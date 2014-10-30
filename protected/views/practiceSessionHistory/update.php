<?php
$this->breadcrumbs=array(
	'Practice Session Histories'=>array('index'),
	$model->practiceSessionHistoryID=>array('view','id'=>$model->practiceSessionHistoryID),
	'Update',
);

	$this->menu=array(
	array('label'=>'List PracticeSessionHistory','url'=>array('index')),
	array('label'=>'Create PracticeSessionHistory','url'=>array('create')),
	array('label'=>'View PracticeSessionHistory','url'=>array('view','id'=>$model->practiceSessionHistoryID)),
	array('label'=>'Manage PracticeSessionHistory','url'=>array('admin')),
	);
	?>

	<h1>Update PracticeSessionHistory <?php echo $model->practiceSessionHistoryID; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>