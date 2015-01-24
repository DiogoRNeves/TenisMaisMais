<?php
/* @var $this ClubController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Clubs',
);

$this->menu = array();
?>

<h1>Clubs</h1>

<?php
$this->widget('booster.widgets.TbListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
));
?>
