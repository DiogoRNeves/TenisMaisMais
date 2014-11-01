<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
        ));
?>

<?php echo $form->textFieldRow($model, 'practiceSessionHistoryID', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'startTime', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'endTime', array('class' => 'span5')); ?>

<?php echo $form->datepickerRow($model, 'date', array('options' => array(), 'htmlOptions' => array('class' => 'span5'))); ?>

<?php echo $form->textFieldRow($model, 'coachID', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'clubID', array('class' => 'span5')); ?>

<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => 'Search',
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
