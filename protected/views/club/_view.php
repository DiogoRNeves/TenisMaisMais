<?php
/* @var $this ClubController */
/* @var $data Club */
?>

<div class="well">

    <b><?php echo CHtml::encode($data->getAttributeLabel('clubID')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id' => $data->clubID)); ?>
    <br />

    <?php if (!$data->isAttributeBlank('home')): ?>
        <?php if (!$data->home->isAttributeBlank('phoneNumber')): ?>
            <b><?php echo CHtml::encode($data->home->getAttributeLabel('phoneNumber')); ?>:</b>
            <?php echo CHtml::encode($data->home->phoneNumber); ?>
            <br />
        <?php endif ?>

        <?php if (!$data->home->isAttributeBlank('address')): ?>
            <b><?php echo CHtml::encode($data->home->getAttributeLabel('address')); ?>:</b>
            <?php echo CHtml::encode($data->home->address); ?>
            <br />
        <?php endif ?>

        <?php if (!$data->home->isAttributeBlank('city')): ?>
            <b><?php echo CHtml::encode($data->home->getAttributeLabel('city')); ?>:</b>
            <?php echo CHtml::encode($data->home->city); ?>
            <br />
        <?php endif ?>
    <?php endif ?>

    <?php if (!$data->isAttributeBlank('contact')): ?>
        <?php if (!$data->contact->isAttributeBlank('email')): ?>
            <b><?php echo CHtml::encode($data->contact->getAttributeLabel('email')); ?>:</b>
            <?php echo CHtml::encode($data->contact->email); ?>
            <br />
        <?php endif ?>
    <?php endif ?>

    <?php if (!$data->isAttributeBlank('adminUserID')): ?>
        <b><?php echo CHtml::encode($data->getAttributeLabel('adminUserID')); ?>:</b>
        <?php echo CHtml::link(CHtml::encode($data->adminUser->name), array('user/view', 'id' => $data->adminUser->primaryKey)); ?>
        <br />
    <?php endif ?>


</div>