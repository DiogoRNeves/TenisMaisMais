<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'practice-session-history-form',
	'enableAjaxValidation'=>false,
)); ?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'practiceSessionHistoryID',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'startTime',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'endTime',array('class'=>'span5')); ?>

	<?php echo $form->datepickerRow($model,'date',array('options'=>array(),'htmlOptions'=>array('class'=>'span5')),array('prepend'=>'<i class="icon-calendar"></i>','append'=>'Click on Month/Year at top to select a different year or type in (mm/dd/yyyy).')); ?>

	<?php echo $form->textFieldRow($model,'coachID',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'clubID',array('class'=>'span5')); ?>

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
</div>

<?php $this->endWidget(); ?>
