<?php
$this->breadcrumbs=array(
	'Practice Session Histories',
);

$this->menu=array(
array('label'=>'Registar Assiduidade','url'=>array('register')),
array('label'=>'Mais Opções (ADMIN)','url'=>array('admin')),
);
?>

<?php $this->widget('bootstrap.widgets.TbListView',array(
'dataProvider'=>$dataProvider,
'itemView'=>'_view',
)); ?>
