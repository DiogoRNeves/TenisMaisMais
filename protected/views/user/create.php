<?php
/* @var $this UserController */
/* @var $user User */
/* @var $contact Contact */
/* @var $sponsor Sponsor */
/* @var $clubHasUser ClubHasUser */

$this->breadcrumbs = array(
    'Users' => array('index'),
    'Create',
);

//Side menu populated by controller
$this->menu = $this->getSideMenuItems($user);
?>

<h1>Create User</h1>

<?php
$this->renderPartial('_form', array(
    'user' => $user,
    'contact' => $contact,
    'clubHasUser' => $clubHasUser,
    'sponsor' => $sponsor
));
?>