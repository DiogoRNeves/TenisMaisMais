<?php
/* @var $this ClubController */
/* @var $model Club */

$this->breadcrumbs = array(
    'Clubs' => array('index'),
    $model->name => array('view', 'id' => $model->primaryKey),
    'Update',
);

$this->menu = $this->getDetailViewsMenu($model);
?>

<h1>Update Club <?php echo $model->clubID; ?></h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>