<?php
/* @var $this CompetitivePlanController
 * @var $model AthleteGroup
 */

$adminColumn = array();
if ($model->canBeUpdatedBy(User::getLoggedInUser())) {
    $adminColumn[] = FederationTournament::model()->getAdminColumn($model);
}

$this->widget('booster.widgets.TbExtendedGridView', array(
    'id' => 'tournament-list',
    'responsiveTable' => true,
    'fixedHeader' => true,
    'headerOffset' => 50,
    'dataProvider' => $model->searchFederationTournaments(),
    'type' => 'striped',
    //'filter' => new FederationTournament,
    'columns' => array_merge($adminColumn, FederationTournament::model()->getCommonColumns()),
));