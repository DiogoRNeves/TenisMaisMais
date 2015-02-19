<?php
/* @var $this UserController */
/* @var $user User */
/* @var $contact Contact */
/* @var $sponsor Sponsor */
/* @var $clubHasUser ClubHasUser */

$this->breadcrumbs = array(
    'Criar ' . ($clubHasUser->isAttributeBlank('clubID') ? 'Patrocinador' : UserType::model()->findByPk($clubHasUser->userTypeID)->name),
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