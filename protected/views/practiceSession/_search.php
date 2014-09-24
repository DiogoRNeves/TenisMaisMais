<?php
/* @var $this PracticeSessionController */
/* @var $model PracticeSession */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'practiceSessionID'); ?>
		<?php echo $form->textField($model,'practiceSessionID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'coachID'); ?>
		<?php echo $form->textField($model,'coachID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'clubID'); ?>
		<?php echo $form->textField($model,'clubID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'activePracticeSession'); ?>
		<?php echo $form->textField($model,'activePracticeSession'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'startTime'); ?>
		<?php echo $form->textField($model,'startTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'endTime'); ?>
		<?php echo $form->textField($model,'endTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'groupLevel'); ?>
		<?php echo $form->textField($model,'groupLevel'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dayOfWeek'); ?>
		<?php echo $form->textField($model,'dayOfWeek'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->