<?php
/* @var $user User */

$this->beginWidget('booster.widgets.TbModal', array(
    'id' => 'modalDialog',
));
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Sessão de Treino</h4>
</div>

<div class="modal-body">
    <?php $this->renderPartial('_form', array('model' => $user->getSamplePracticeSession())); ?>
</div>

<div class="modal-footer">
    <?php
    $this->widget(
            'booster.widgets.TbButton', array(
        'context' => 'primary',
        'label' => 'Gravar',
        'htmlOptions' => array(
            'class' => 'submitModal'),
            )
    );
    ?>
    <?php
    $this->widget(
            'booster.widgets.TbButton', array(
        'context' => 'danger',
        'label' => 'Apagar',
        'htmlOptions' => array(
            'class' => 'deleteObject',
            'data-toggle' => 'confirmation',
            'data-popout' => 'true',
            'data-placement' => 'top',
            'data-btnOkClass' => 'btn btn-sm btn-primary',
            'data-title' => 'Tem a certeza?',
            'data-btnOkLabel' => '<i class="icon-ok-sign icon-white"></i> Sim',
            'data-btnCancelLabel' => '<i class="icon-remove-sign"></i> Não',
            'data-baseUrl' => $this->createUrl('practiceSession/delete'),
        ),
            )
    );
    ?>
    <?php
    $this->widget(
            'booster.widgets.TbButton', array(
        'label' => 'Fechar',
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
            )
    );
    ?>
</div>

<?php $this->endWidget(); ?>
