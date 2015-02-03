<?php
/* @var $this CompetitivePlanController
 * @var $model AthleteGroup
 */

$gridColumns = array(
    array(
        'name' => 'mainDrawStartDate',
        'header' => 'Data de início',
        'value' => '$data->qualyStartDate === null ? $data->mainDrawStartDate : $data->qualyStartDate'
    ),
    array(
        'name' => 'mainDrawEndDate',
        'header' => 'Data de fim',
    ),
    'level',
    'name',
    'surface',
    array(
        'name' => 'federationClubID',
        'value' => '$data->federationClub->name',
    ),
    'city',
);

$this->widget('booster.widgets.TbExtendedGridView', array(
    'id' => 'tournament-list',
    'responsiveTable' => true,
    'fixedHeader' => true,
    'headerOffset' => 50,
    'dataProvider' => $model->searchFederationTournaments(),
    'type' => 'striped',
    //'filter' => new FederationTournament,
    'columns' => $gridColumns,
));