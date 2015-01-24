<?php
/* @var $this PracticeSessionController */
/* @var $model PracticeSession */
/* @var $form TbActiveForm */
?>

<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
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

<p class="note">Os campos com <span class="required">*</span> são obrigatórios.</p>

<?php echo $form->errorSummary($model); ?>

<div style="display: none">
    <?php echo $form->textFieldGroup($model, 'practiceSessionID'); ?>
    <?php echo CHelper::select2Row($form, $model, 'coachID', $model->club->getCoachesListData()); ?>
</div>

<?php echo CHelper::select2Row($form, $model, 'formAthletes', $model->club->getAthletesListData(), true); ?>

<?php echo CHelper::select2Row($form, $model, 'groupLevel', PlayerLevel::model()->getListData()); ?>

<?php echo $form->textFieldGroup($model, 'startTime'); ?>

<?php echo $form->textFieldGroup($model, 'endTime'); ?>

<div class="well" style="display: none">
    <?php echo $form->textFieldGroup($model, 'dayOfWeek'); ?>
    <?php echo $form->textFieldGroup($model, 'clubID', array('class' => 'ignoreField')); ?>
</div>

<?php $this->endWidget(); ?><!-- form -->