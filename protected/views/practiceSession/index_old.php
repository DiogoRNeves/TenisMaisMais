<?php
/* @var $this PracticeSessionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Practice Sessions',
);

$this->menu=array(
	array('label'=>'Create PracticeSession', 'url'=>array('create')),
	array('label'=>'Manage PracticeSession', 'url'=>array('admin')),
);
?>

<h1>Practice Sessions</h1>

<?php $this->widget('bootstrap.widgets.TbListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
