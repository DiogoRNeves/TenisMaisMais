<?php
/* @var $this ClubController */
/* @var $model Club */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'club-form',
        'type' => 'horizontal',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => true,
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary(array($model, $model->home, $model->contact)); ?>
    
    <?php echo $form->textFieldRow($model, 'name'); ?>
    
    <?php echo $form->textFieldRow($model->contact, 'email'); ?>
    
    <?php echo $form->textFieldRow($model->home, 'phoneNumber'); ?>
    
    <?php echo $form->textFieldRow($model->home, 'address'); ?>
    
    <?php echo $form->textFieldRow($model->home, 'postCode'); ?>
    
    <?php echo $form->textFieldRow($model->home, 'city'); ?>
    
    <?php echo CHelper::select2Row($form, $model, 'adminUserID', $model->getAdminUserOptions()); ?>

    <div class="form-actions">
        <?php CHelper::echoSubmitButton($form, $model); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->