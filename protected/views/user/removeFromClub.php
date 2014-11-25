<?php
/* @var $this UserController */
/* @var $model ClubHasUser */
/* @var $deleted bool */

$this->breadcrumbs = array(
    'Remover atleta de clube',
);
?>

<?php /** @var TbActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'method' => 'post',
    'type' => 'horizontal',
)); ?>

<?php
    echo $form->select2Row($model, 'userTypeID', array(
            'data' => CHtml::listData($model->user->getUserTypes(), 'userTypeID', 'name'),
        ),
        array(
            'label' => 'Tipo de utilizador',
        )
    );
?>

<?php echo CHtml::checkBox('confirmedDeletion', true, array('style' => 'display: none')); ?>

<?php
$form->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'label' => 'Confirmar',
));
?>

<?php $this->endWidget(); ?>



