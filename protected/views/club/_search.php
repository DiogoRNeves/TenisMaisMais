<?php
/* @var $this ClubController */
/* @var $model Club */
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
        <?php echo $form->label($model, 'clubID'); ?>
        <?php echo $form->textField($model, 'clubID'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 45, 'maxlength' => 45)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'homeID'); ?>
        <?php echo $form->textField($model, 'homeID'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'contactID'); ?>
        <?php echo $form->textField($model, 'contactID'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'adminUserID'); ?>
        <?php echo $form->textField($model, 'adminUserID'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->