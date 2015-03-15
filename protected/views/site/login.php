<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form TbActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Login';
$this->breadcrumbs = array(
    'Login',
);
?>

<h1>Login</h1>

<p>Preencha as suas credenciais:</p>

<div class="form">
    <?php
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'login-form',
        'type' => 'horizontal'
    ));
    ?>

    <?php echo $form->textFieldGroup($model, 'username', array('label' => $model->getAttributeLabel('username'))); ?> <br />

    <?php
        $link = CHtml::link('Esqueci-me da palavra-passe', '#', array(
            'data-toggle' => 'modal',
            'data-target' => '#recover-password',
        ));
        echo $form->passwordFieldGroup($model, 'password', array(
            'label' => $model->getAttributeLabel('password'),
            'hint' => $link,
        ));
    ?> <br />

    <?php echo $form->checkboxGroup($model, 'rememberMe', array('class' => 'rememberMe')); ?>

    <div class="form-actions">
        <?php
        $form->widget(
                'booster.widgets.TbButton', array(
            'buttonType' => 'submit',
            'context' => 'primary',
            'label' => 'Login',
        ));
        ?>
    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->

<!-- modal to recover password -->
<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array(
        'id' => 'recover-password',
    )
); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Recuperar palavra passe</h4>
</div>

<div class="modal-body">

    <?php
    /* @var $form TbActiveForm */
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'recover-password-form',
        'action' => Yii::app()->createUrl('user/recoverPassword'),
        'type' => 'horizontal',
        'enableAjaxValidation' => true,
    ));
    Yii::app()->clientScript->registerScript('submitForgotPassword' , new CJavaScriptExpression(
        "$('#" . $form->id . "').on('submit', function (e) {
   $.post($(this).attr('action'), $(this).serialize(), function(data) {
        console.log(data);
        var input = $('#" . $form->id . " :input').first();
        input.notify(data.notificationText, {className: data.status === 'OK' ? 'success' : 'error'});
    });
   //stop form submission
   e.preventDefault();
});"));
    ?>

    <?php $label = ucfirst(Contact::model()->getAttributeLabel('email'));
    echo $form->textFieldGroup($model, 'username', array(
        'label' => $label,
        'widgetOptions' => array('htmlOptions' => array('placeholder' => $label))
    )); ?>

    <div class="modal-footer">
        <?php
        $this->widget(
            'booster.widgets.TbButton',
            array(
                'buttonType' => 'submit',
                'context' => 'primary',
                'label' => 'Recuperar palavra-passe',
                'htmlOptions' => array(
                    'class' => 'pull-right',
                ),
            )
        );
        ?>
    </div>

    <?php $this->endWidget(); //end form?>
</div>

<?php $this->endWidget(); //end modal?>
<!-- end of modal to recover password -->
