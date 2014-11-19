<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="well">

    <b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id' => $data->userID)); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('male')); ?>:</b>
    <?php echo CHtml::encode($data->getGender()); ?>
    <br />

    <?php if (!$data->isAttributeBlank('birthDate')): ?>
        <b><?php echo CHtml::encode($data->getAttributeLabel('birthDate')); ?>:</b>
        <?php echo CHtml::encode($data->birthDate); ?>
        <br />
    <?php endif ?>

    <?php if (!$data->isAttributeBlank('federationNumber')): ?>
        <b><?php echo CHtml::encode($data->getAttributeLabel('federationNumber')); ?>:</b>
        <?php echo CHtml::encode($data->federationNumber); ?>
        <br />
    <?php endif ?>

    <?php if (!$data->isAttributeBlank('coachLevel')): ?>
        <b><?php echo CHtml::encode($data->getAttributeLabel('coachLevelID')); ?>:</b>
        <?php echo CHtml::encode($data->coachLevel->coachLevel); ?>
        <br />
    <?php endif ?>

    <?php if (!$data->isAttributeBlank('playerLevel')): ?>
        <b><?php echo CHtml::encode($data->getAttributeLabel('playerLevelID')); ?>:</b>
        <?php echo CHtml::encode($data->playerLevel->playerLevel); ?>
        <br /> 
    <?php endif ?>

    <?php if (!$data->isAttributeBlank('contact')): ?>
        <?php if (!$data->contact->isAttributeBlank('cellularPhone')): ?>
            <b><?php echo CHtml::encode($data->contact->getAttributeLabel('cellularPhone')); ?>:</b>
            <?php echo CHtml::encode($data->contact->cellularPhone); ?>
            <br />
        <?php endif ?>

        <?php if (!$data->contact->isAttributeBlank('workPhone')): ?>
            <b><?php echo CHtml::encode($data->contact->getAttributeLabel('workPhone')); ?>:</b>
            <?php echo CHtml::encode($data->contact->workPhone); ?>
            <br />
        <?php endif ?>

        <?php if (!$data->contact->isAttributeBlank('email')): ?>
            <b><?php echo CHtml::encode($data->contact->getAttributeLabel('email')); ?>:</b>
            <?php echo CHtml::encode($data->contact->email); ?>
            <br />
        <?php endif ?>

        <?php if (!$data->contact->isAttributeBlank('fax')): ?>
            <b><?php echo CHtml::encode($data->contact->getAttributeLabel('fax')); ?>:</b>
            <?php echo CHtml::encode($data->contact->fax); ?>
            <br />
        <?php endif ?>

        <?php if (!$data->contact->isAttributeBlank('website')): ?>
            <b><?php echo CHtml::encode($data->contact->getAttributeLabel('website')); ?>:</b>
            <?php echo CHtml::encode($data->contact->website); ?>
            <br />    
        <?php endif ?>
    <?php endif ?>

    <?php if (!($data->isAttributeBlank('sponsors') && $data->isAttributeBlank('sponsoredAthletes'))) : ?>
        <br />
    <?php endif ?>

    <?php if (!$data->isAttributeBlank('sponsors')): ?>
        <b><?php echo CHtml::encode($data->getAttributeLabel('sponsors')); ?>:</b>
        <?php echo CHelper::getStringOfObjectLinks($data->sponsors, 'name'); ?>
        <br />    
    <?php endif ?>

    <?php if (!$data->isAttributeBlank('sponsoredAthletes')): ?>
        <b><?php echo CHtml::encode($data->getAttributeLabel('sponsoredAthletes')); ?>:</b>
        <?php echo CHelper::getStringOfObjectLinks($data->sponsoredAthletes, 'name'); ?>
        <br />    
    <?php endif ?>
</div>