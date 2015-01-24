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
        'type' => 'search'
    ));
    ?>

    <?php echo $form->textFieldRow($model, 'username'); ?> <br />

    <?php echo $form->passwordFieldRow($model, 'password'); ?> <br />

    <?php echo $form->checkBoxRow($model, 'rememberMe', array('class' => 'rememberMe')); ?>

    <div class="form-actions">
        <?php
        $form->widget(
                'booster.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => 'Login',
        ));
        ?>
    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->
