<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>

    <div class="row">
        <?php echo $form->label($model, 'userID'); ?>
        <?php echo $form->textField($model, 'userID'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'contactID'); ?>
        <?php echo $form->textField($model, 'contactID'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'homeID'); ?>
        <?php echo $form->textField($model, 'homeID'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 45, 'maxlength' => 45)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'birthDate'); ?>
        <?php echo $form->textField($model, 'birthDate'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'federationNumber'); ?>
        <?php echo $form->textField($model, 'federationNumber', array('size' => 45, 'maxlength' => 45)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'coachLevel'); ?>
        <?php echo $form->textField($model, 'coachLevel'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'playerLevel'); ?>
        <?php echo $form->textField($model, 'playerLevel'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'activated'); ?>
        <?php echo $form->textField($model, 'activated'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'activationHash'); ?>
        <?php echo $form->textField($model, 'activationHash', array('size' => 60, 'maxlength' => 512)); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->