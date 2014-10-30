<?php
$this->breadcrumbs=array(
	'Practice Session Histories',
);

$this->menu=array(
array('label'=>'Create PracticeSessionHistory','url'=>array('create')),
array('label'=>'Manage PracticeSessionHistory','url'=>array('admin')),
);
?>

<h1>Practice Session Histories</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
'dataProvider'=>$dataProvider,
'itemView'=>'_view',
)); ?>
