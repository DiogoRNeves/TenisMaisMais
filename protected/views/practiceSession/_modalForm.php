<?php
/* @var $user User */

$this->beginWidget('bootstrap.widgets.TbModal', array(
    'id' => 'modalDialog',
));
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Modal header</h4>
</div>

<div class="modal-body">    
    <?php $this->renderPartial('_form', array('model' => $user->getSamplePracticeSession())); ?>
</div>

<div class="modal-footer">
    <?php
    $this->widget(
            'bootstrap.widgets.TbButton', array(
        'type' => 'primary',
        'label' => 'Save',
        'htmlOptions' => array(
            'class' => 'submitModal'),
            )
    );
    ?>
    <?php
    $this->widget(
            'bootstrap.widgets.TbButton', array(
        'type' => 'danger',
        'label' => 'Delete',
        'htmlOptions' => array(
            'class' => 'deleteObject',
            'data-toggle' => 'confirmation',
            'data-popout' => 'true',
            'data-baseUrl' => $this->createUrl('practiceSession/delete'),
        ),
            )
    );
    ?>
    <?php
    $this->widget(
            'bootstrap.widgets.TbButton', array(
        'label' => 'Close',
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
            )
    );
    ?>
</div>

<?php $this->endWidget(); ?>
