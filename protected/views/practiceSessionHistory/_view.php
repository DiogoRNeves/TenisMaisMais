<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('practiceSessionHistoryID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->practiceSessionHistoryID),array('view','id'=>$data->practiceSessionHistoryID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('startTime')); ?>:</b>
	<?php echo CHtml::encode($data->startTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('endTime')); ?>:</b>
	<?php echo CHtml::encode($data->endTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('coachID')); ?>:</b>
	<?php echo CHtml::encode($data->coachID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('clubID')); ?>:</b>
	<?php echo CHtml::encode($data->clubID); ?>
	<br />


</div>