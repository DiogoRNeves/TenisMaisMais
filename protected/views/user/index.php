<?php
/* @var $this UserController */
/* @var $dataProvider CActiveDataProvider */
/* @var $userType UserType */

$userTypeText = $userType == null ? 'Utilizador' : ucwords($userType->name);

$this->breadcrumbs = array(
    $userTypeText,
);

//Side menu populated by controller
$this->menu = $this->getSideMenuItems($dataProvider);
?>

<h1><?php echo $userTypeText; ?></h1>

<?php
$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
));
?>
