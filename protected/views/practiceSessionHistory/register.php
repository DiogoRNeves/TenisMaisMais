<?php
$this->breadcrumbs=array(
	'Assiduidade'=>array('index'),
	'Registar',
);

	$this->menu=array(
	array('label'=>'List PracticeSessionHistory','url'=>array('index')),
	array('label'=>'Manage PracticeSessionHistory','url'=>array('admin')),
	);
	?>

	<h1>Registar assiduidade aos treinos</h1>

<?php echo $this->renderPartial('_searchPracticeSession',array('model'=>$model)); ?>