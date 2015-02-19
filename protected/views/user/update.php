<?php
/* @var $this UserController */
/* @var $user User */
/* @var $contact Contact */

$this->breadcrumbs = array(
    $user->name => array('view', 'id' => $user->userID),
    'Editar',
);

//Side menu populated by controller
$this->menu = $this->getSideMenuItems($user);
?>

<h1>Editar <?php echo $user->name; ?></h1>

<?php $this->renderPartial('_form', array('user' => $user, 'contact' => $contact)); ?>