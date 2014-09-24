<?php
/* @var $this PracticeSessionController */
/* @var $data PracticeSession */
?>

<div class="well">

	<b><?php echo CHtml::encode($data->getAttributeLabel('practiceSessionID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->practiceSessionID), array('view', 'id'=>$data->practiceSessionID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('coachID')); ?>:</b>
	<?php echo CHtml::encode($data->coach->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('clubID')); ?>:</b>
	<?php echo CHtml::encode($data->club->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('activePracticeSession')); ?>:</b>
	<?php echo CHtml::encode($data->activePracticeSession); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('startTime')); ?>:</b>
	<?php echo CHtml::encode($data->startTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('endTime')); ?>:</b>
	<?php echo CHtml::encode($data->endTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('groupLevel')); ?>:</b>
	<?php echo CHtml::encode($data->playerLevel->getCompiledListText()); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('dayOfWeek')); ?>:</b>
	<?php echo CHtml::encode($data->dayOfWeek); ?>
	<br />

</div>