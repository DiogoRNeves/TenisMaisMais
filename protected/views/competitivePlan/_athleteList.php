<?php
/* @var $this CompetitivePlanController
 * @var $model AthleteGroup
 */


$gridColumns = array(
    'name',
    array(
        'name' => 'playerLevelID',
        'value' => '$data->playerLevel === null ? "" : $data->playerLevel->playerLevel',
    ),
    'birthDate',
);

$this->widget('booster.widgets.TbExtendedGridView', array(
    'id' => 'athlete-list',
    'responsiveTable' => true,
    'fixedHeader' => true,
    'headerOffset' => 50,
    'dataProvider' => $model->searchAthletes(),
    'type' => 'striped',
    'columns' => $gridColumns,
));