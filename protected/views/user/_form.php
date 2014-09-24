<?php
/* @var $this UserController */
/* @var $user User */
/* @var $contact Contact */
/* @var $sponsor Sponsor */
/* @var $form TbActiveForm */
/* @var $clubHasUser ClubHasUser */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'user-form',
        'type' => 'horizontal',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => true,
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($user); ?>

    <?php echo $form->textFieldRow($user, 'name', array('size' => 45, 'maxlength' => 45, 'class' => 'span5')); ?>

    <?php
    if (isset($sponsor) && $sponsor->hasNotNullAttributes()) {
        echo CHelper::select2Row($form, $sponsor, 'relationshipType', SponsorAthleteRelationshipType::model()->getListData());
    }
    ?>

    <?php
    echo $form->datePickerRow($user, 'birthDate', array('options' => array(
            'format' => "yyyy-mm-dd",
            'startDate' => "-90y",
            'endDate' => "-1y",
            'startView' => 2,
            'autoclose' => true
    )));
    ?>

    <?php echo $form->textFieldRow($user, 'federationNumber', array('size' => 45, 'maxlength' => 45, 'class' => 'span5')); ?>

    <?php 
    if ($user->isNewRecord || ($user->scenario == 'activation' ? false : User::model()->findByPk(Yii::app()->user->id)->canUpdateUserLevels($user))): ?>
        <?php echo CHelper::select2Row($form, $user, 'coachLevelID', CoachLevel::model()->getListData()); ?>

        <?php
        $url = CHTML::link('O que é o Nível do Jogador?', Yii::app()->params['playerLevelURL'], array('target' => '_blank'));
        echo CHelper::select2Row($form, $user, 'playerLevelID', PlayerLevel::model()->getListData(), false, $url);
        ?>

    <?php endif; ?>
    <?php echo $form->textFieldRow($contact, 'cellularPhone', array('size' => 45, 'maxlength' => 45, 'class' => 'span5')); ?>

    <?php echo $form->textFieldRow($contact, 'workPhone', array('size' => 45, 'maxlength' => 45, 'class' => 'span5')); ?>

    <?php echo $form->textFieldRow($contact, 'email', array('size' => 45, 'maxlength' => 45, 'class' => 'span5')); ?>

    <?php echo $form->textFieldRow($contact, 'fax', array('size' => 45, 'maxlength' => 45, 'class' => 'span5')); ?>

    <?php echo $form->textFieldRow($contact, 'website', array('size' => 45, 'maxlength' => 45, 'class' => 'span5')); ?>

    <?php if ($user->canChangePassword()): ?>
        <p class="note"><?php echo 'Mudar a password:' ?></p>
        <?php
        if ($user->scenario != 'activation') {
            echo $form->passwordFieldRow($user, 'oldPassword', array('size' => 45, 'maxlength' => 45, 'class' => 'span5'));
        }
        ?>
        <?php echo $form->passwordFieldRow($user, 'newPassword', array('size' => 45, 'maxlength' => 45, 'class' => 'span5')); ?>
        <?php echo $form->passwordFieldRow($user, 'newPasswordRepeated', array('size' => 45, 'maxlength' => 45, 'class' => 'span5')); ?>
    <?php endif; ?>

    <!-- hidden fields from the create user action -->
    <div hidden="true">
        <?php if (isset($clubHasUser) && $clubHasUser->hasNotNullAttributes()): ?>
            <?php echo $form->textField($clubHasUser, 'clubID') ?>
            <?php echo $form->textField($clubHasUser, 'userTypeID') ?>
        <?php endif; ?>
        <?php if (isset($sponsor) && $sponsor->hasNotNullAttributes()): ?>
            <?php echo $form->textField($sponsor, 'athleteID') ?>
            <?php echo $form->textField($sponsor, 'startDate') ?>
        <?php endif; ?>
    </div>
    <!-- end of hidden fields from the create user action -->

    <div class="form-actions">
        <?php CHelper::echoSubmitButton($this, $user); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->