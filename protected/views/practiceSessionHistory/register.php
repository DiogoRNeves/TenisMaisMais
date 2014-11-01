<?php
$this->breadcrumbs=array(
	'Assiduidade'=>array('index'),
	'Registar',
);

	$this->menu=array(
	array('label'=>'List PracticeSessionHistory','url'=>array('index')),
	array('label'=>'Create PracticeSessionHistory','url'=>array('create')),
	array('label'=>'View PracticeSessionHistory','url'=>array('view','id'=>$model->practiceSessionHistoryID)),
	array('label'=>'Manage PracticeSessionHistory','url'=>array('admin')),
	);
	?>

	<h1>Update PracticeSessionHistory <?php echo $model->practiceSessionHistoryID; ?></h1>

<?php echo $this->renderPartial('_searchPracticeSession',array('model'=>$model)); ?>