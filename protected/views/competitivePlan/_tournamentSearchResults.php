<?php
/* @var $this CompetitivePlanController
 * @var $model AthleteGroup
 * @var $federationTournamentSearch FederationTournament
 */

$showAddColumn = $this->action->id == 'view' && $model->canBeUpdatedBy(User::getLoggedInUser());

$addColumn =  array(
    'class'=>'booster.widgets.TbButtonColumn',
    'template' => '{add}',
    'buttons' => array(
        'add' => array (
            'label'=>'Adicionar torneio ao plano',
            'icon'=>'plus',
            'click'=>"function(){
                                    var element = $('#searchTournament .modal-header');
                                    $.ajax({
                                        url : $(this).attr('href'),
                                    }).success(function(res) {
                                        var tournamentName = res.name;
                                        element.notify('Torneio \"' + tournamentName + '\" adicionado ao plano!',
                                        {
                                            className : 'success',
                                            position : 'bottom center'
                                        });
                                        $.fn.yiiGridView.update('search-tournament-table');
                                        $.fn.yiiGridView.update('tournament-list');
                                    }).fail( function() {
                                        element.notify('Não foi possível adicionar o torneio ao plano.',
                                        {
                                            className : 'error',
                                            position : 'bottom center'
                                        });
                                    });
                                    return false;
                                }
                             ",
            'url' => 'Yii::app()->createUrl("competitivePlan/addTournament", array(
                                "federationTournamentID" => $data->primaryKey,
                                "athleteGroupID" => ' . $model->primaryKey . '
                            ))',
            'options'=>array(
                'class'=>'btn btn-small',
            ),
            'visible' => '!$data->isInAthleteGroup(' . $model->primaryKey . ')',
        ),
    ),
);

$commonColumns = array(
    array(
        'name' => 'mainDrawStartDate',
        'header' => 'Datas de realização',
        'value' => '$data->getDateRange(false)'
    ),
    array(
        'name' => 'qualyStartDate',
        'header' => 'Qualifying',
        'value' => '$data->hasQuali() ? "Sim" : "Não"',
    ),
    array(
        'name' => 'level',
        'header' => FederationTournament::model()->getAttributeLabel('level'),
    ),
    array(
        'name' => 'name',
        'type' => 'raw',
        'value' => 'CHtml::link($data->name, $data->getFederationSiteLink(), array(
                        "target" => "_blank",
                    ));',
        'header' => FederationTournament::model()->getAttributeLabel('name'),
    ),
    array(
        'name' => 'surface',
        'header' => FederationTournament::model()->getAttributeLabel('surface'),
    ),
    array(
        'name' => 'federationClub.name',
        'value' => '$data->federationClub->name',
        'header' => FederationTournament::model()->getAttributeLabel('federationClubID'),
    ),
    array(
        'name' => 'ageBandsString',
        'header' => AgeBand::model()->getAttributeLabel('ageBandID'),
        'value' => '$data->ageBandsString',
    ),
    array(
        'name' => 'distance',
        'header' => FederationTournament::model()->getAttributeLabel('cachedDistance'),
    ),
);

$columns = array_merge($showAddColumn ? array($addColumn) : array(), $commonColumns);

$this->widget('booster.widgets.TbExtendedGridView', array(
    'id' => 'search-tournament-table',
    'responsiveTable' => true,
    //'fixedHeader' => true,
    //'headerOffset' => 50,
    'dataProvider' => $federationTournamentSearch->search(),
    //'type' => 'striped',
    //'filter' => $federationTournamentSearch,
    'columns' => $columns,
));
/** @var CClientScript $cs */
$cs = Yii::app()->getClientScript();
$cs->registerScript('makeLargeModal', new CJavaScriptExpression('$("#searchTournament .modal-dialog").addClass("modal-lg");'));
