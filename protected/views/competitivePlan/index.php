<?php
/* @var $this CompetitivePlanController */

$this->breadcrumbs=array(
	'Competitive Plan',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	<?php echo CHtml::link('Testes', array($this->id . "/view", 'competitivePlanID' => 1)); ?>
</p>
