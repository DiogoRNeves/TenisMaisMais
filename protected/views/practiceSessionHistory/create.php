<?php
$this->breadcrumbs=array(
	'Practice Session Histories'=>array('index'),
	'Create',
);

$this->menu=array(
array('label'=>'List PracticeSessionHistory','url'=>array('index')),
array('label'=>'Manage PracticeSessionHistory','url'=>array('admin')),
);
?>

<h1>Create PracticeSessionHistory</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>