<?php
/* @var $this ContactController */
/* @var $data Contact */
?>

<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('contactID')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->contactID), array('view', 'id' => $data->contactID)); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('cellularPhone')); ?>:</b>
    <?php echo CHtml::encode($data->cellularPhone); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('workPhone')); ?>:</b>
    <?php echo CHtml::encode($data->workPhone); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
    <?php echo CHtml::encode($data->email); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('fax')); ?>:</b>
    <?php echo CHtml::encode($data->fax); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('website')); ?>:</b>
    <?php echo CHtml::encode($data->website); ?>
    <br />


</div>