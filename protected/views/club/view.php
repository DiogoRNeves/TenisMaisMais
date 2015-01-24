<?php
/* @var $this ClubController */
/* @var $model Club */

$this->breadcrumbs = array(
    'Clubs' => array('index'),
    $model->name,
);

$this->menu = $this->getDetailViewsMenu($model);
?>

<h1>View Club #<?php echo $model->name; ?></h1>

<?php
$this->widget('booster.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'name',
        'home.phoneNumber',
        'home.address',
        'home.city',
        'contact.email',
        CHelper::getObjectsLinks($model->adminUser, 'name', $model->getAttributeLabel('adminUserID')),
    ),
));
?>
