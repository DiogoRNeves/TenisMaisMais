<?php
/* @var $this ContactController */
/* @var $model Contact */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'contact-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => true,
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'cellularPhone'); ?>
        <?php echo $form->textField($model, 'cellularPhone', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'cellularPhone'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'workPhone'); ?>
        <?php echo $form->textField($model, 'workPhone', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'workPhone'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->emailField($model, 'email', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'fax'); ?>
        <?php echo $form->textField($model, 'fax', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'fax'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'website'); ?>
        <?php echo $form->urlField($model, 'website', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'website'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->