<?php
/* @var $this ClubController */
/* @var $model Club */

$this->breadcrumbs = array(
    'Clubs' => array('index'),
    'Create',
);

$this->menu = $this->getDetailViewsMenu($model);
?>

<h1>Create Club</h1>

<?php
$this->renderPartial('_form', array(
    'model' => $model,
));
?>