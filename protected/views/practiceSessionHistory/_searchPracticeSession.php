<?php
/* @var $form TbActiveForm */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
        ));
?>


<?php echo $form->textFieldRow($model, 'startTime', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'endTime', array('class' => 'span5')); ?>

<?php echo $form->datepickerRow($model, 'date', array('options' => array(), 'htmlOptions' => array('class' => 'span5'))); ?>

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
//TODO: when the coach is an administrator in more than one club this should make an ajax call to the
//get the coaches of the selected club
//it can still default to the logged in user
echo CHelper::select2Row($form, $model, 'coachID', $loggedInUser->getAdminedCoachesOptions(), 
        false, null, null, $loggedInUser->isClubAdmin(), $loggedInUser->primaryKey);
?>

<div class="form-actions">
<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'label' => 'Procurar',
));
?>
</div>

<?php $this->endWidget(); ?>
