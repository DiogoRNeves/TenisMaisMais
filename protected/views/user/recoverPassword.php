<?php
/* @var $this UserController */
/* @var $user User */
/* @var $sent bool */

$this->breadcrumbs = array(
    'Recuperar Password',
);
?>

<h1>Teste sobre recuperação de password</h1>

<p><?php echo $user === null ? 'user not found' : $user->name . " recovered: " . $sent; ?></p>


