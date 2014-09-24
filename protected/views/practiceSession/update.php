<?php
/* @var $this PracticeSessionController */
/* @var $model PracticeSession */

$this->breadcrumbs=array(
	'Practice Sessions'=>array('index'),
	$model->practiceSessionID=>array('view','id'=>$model->practiceSessionID),
	'Update',
);

$this->menu=array(
	array('label'=>'List PracticeSession', 'url'=>array('index')),
	array('label'=>'Create PracticeSession', 'url'=>array('create')),
	array('label'=>'View PracticeSession', 'url'=>array('view', 'id'=>$model->practiceSessionID)),
	array('label'=>'Manage PracticeSession', 'url'=>array('admin')),
);
?>

<h1>Update PracticeSession <?php echo $model->practiceSessionID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>