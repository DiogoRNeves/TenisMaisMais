<?php
/* @var $form TbActiveForm */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
));
?>

<?php /** @var PracticeSessionHistory $model */
echo $form->datePickerRow($model, 'date', array('options' => array('endDate' => 'today'))); ?>

<?php
/* @var $loggedInUser User */
$loggedInUser = User::getLoggedInUser();
$coachInOneClub = count($loggedInUser->coachClubs) === 1;
?>



<?php
echo CHelper::select2Row($form, $model, 'clubID', $loggedInUser->getClubsCoachedOptions(),
    false, null, null, !$coachInOneClub, $loggedInUser->clubs[0]->primaryKey);
?>

<?php
/* TODO: when the coach is an administrator in more than one club this should make an ajax call to the
get the coaches of the selected club it can still default to the logged in user */
echo CHelper::select2Row($form, $model, 'coachID', $loggedInUser->getAdminedCoachesOptions(),
    false, null, null, $loggedInUser->isClubAdmin(), $loggedInUser->primaryKey);
?>

<?php echo CHelper::select2Row($form, PracticeSession::model(), 'practiceSessionID', null); ?>

<div class="athleteAttendance">
    <?php echo $form->toggleButtonRow(new PracticeSessionHistoryRegistryForm(), 'cancelledDueToRain', array(
        'enabledLabel' => 'SIM',
        'disabledLabel' => 'NÃO',
        'value' => false,
        //TODO: Write a proper handler for change
        'onChange' => 'js:function($el, status, e){alert("changed")}',
    )); ?>
    <?php echo CHelper::select2Row($form, new PracticeSessionHistoryRegistryForm(), 'athletesAttended',
        $loggedInUser->getCoachedAthletesOptions(), true); ?>
</div>

<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => 'Registar',
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
