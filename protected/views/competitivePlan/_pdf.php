<?php
/* @var $this CompetitivePlanController
 * @var $model AthleteGroup
 */

$dataProvider = $model->searchFederationTournaments();
$dataProvider->pagination = false;

$this->widget('ext.pdfGrid.EPDFGrid', array(
    'dataProvider' => $dataProvider,
    'columns' => FederationTournament::model()->getCommonColumns(false),
    'config'    => array(
        'title'     => 'Plano Competitivo "' . $model->friendlyName . '"',
        'subTitle'  => $model->club->name,
    ),
));