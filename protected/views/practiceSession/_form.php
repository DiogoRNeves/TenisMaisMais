<?php
/* @var $this PracticeSessionController */
/* @var $model PracticeSession */
/* @var $form TbActiveForm */
?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'practice-session-form',
    'type' => 'horizontal',
    'action' => $this->createUrl('practiceSession/assync'),
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => true,
        ));
?>

<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

<div style="display: none">
    <?php echo $form->textFieldRow($model, 'practiceSessionID'); ?>
    <?php echo CHelper::select2Row($form, $model, 'coachID', $model->club->getCoachesListData()); ?>
</div>

<?php echo CHelper::select2Row($form, $model, 'formAthletes', $model->club->getAthletesListData(), true); ?>

<?php echo CHelper::select2Row($form, $model, 'groupLevel', PlayerLevel::model()->getListData()); ?>

<div class="well" style="display: none">
    <?php echo $form->textFieldRow($model, 'startTime'); ?>    
    <?php echo $form->textFieldRow($model, 'endTime'); ?>
    <?php echo $form->textFieldRow($model, 'dayOfWeek'); ?>
    <?php echo $form->textFieldRow($model, 'clubID', array('class' => 'ignoreField')); ?>
</div>

<?php $this->endWidget(); ?><!-- form -->