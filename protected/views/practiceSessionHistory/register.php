<?php
$this->breadcrumbs=array(
	'Assiduidade'=>array('index'),
	'Registar',
);

//$this->menu=$this->getMenuOptions();
	?>

	<h1>Registar assiduidade aos treinos</h1>

<?php echo $this->renderPartial('_searchPracticeSession',array('model'=>$model)); ?>