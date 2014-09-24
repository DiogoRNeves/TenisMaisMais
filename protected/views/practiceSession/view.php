<?php
/* @var $this PracticeSessionController */
/* @var $model PracticeSession */

$this->breadcrumbs = array(
    'Practice Sessions' => array('index'),
    $model->practiceSessionID,
);

$this->menu = array(
    array('label' => 'List PracticeSession', 'url' => array('index')),
    array('label' => 'Create PracticeSession', 'url' => array('create')),
    array('label' => 'Update PracticeSession', 'url' => array('update', 'id' => $model->practiceSessionID)),
    array('label' => 'Delete PracticeSession', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->practiceSessionID), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage PracticeSession', 'url' => array('admin')),
);
?>

<h1>View PracticeSession #<?php echo $model->practiceSessionID; ?></h1>

<?php
$this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        CHelper::getObjectsLinks($model->coach, 'name', 'treinador'),
        CHelper::getObjectsLinks($model->club, 'name', 'club'),
        'activePracticeSession',
        'startTime',
        'endTime',
        CHelper::getObjectsLinks($model->playerLevel, 'compiledListText', 'groupLevel', false),
        'dayOfWeek',
        CHelper::getObjectsLinks($model->athletes, 'name', 'athletes'),
    ),
));
?>
