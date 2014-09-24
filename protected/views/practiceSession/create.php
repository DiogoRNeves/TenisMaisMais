<?php
/* @var $this PracticeSessionController */
/* @var $model PracticeSession */

$this->breadcrumbs=array(
	'Practice Sessions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PracticeSession', 'url'=>array('index')),
	array('label'=>'Manage PracticeSession', 'url'=>array('admin')),
);
?>

<h1>Create PracticeSession</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>