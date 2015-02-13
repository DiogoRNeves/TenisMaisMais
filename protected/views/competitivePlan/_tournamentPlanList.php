<?php
/* @var $this CompetitivePlanController
 * @var $model AthleteGroup
 */

//TODO: add "show past events" option
//TODO: add "export to pdf" option

$gridColumns = array();
if ($model->canBeUpdatedBy(User::getLoggedInUser())) {
    $gridColumns[] = array(
        'class'=>'booster.widgets.TbButtonColumn',
        'template' => '{delete}',
        'buttons' => array(
            'delete' => array (
                'label' => 'Remover torneio do plano',
                //'icon' => 'minus',
                'click' => "function(){
                                    var element = $('#tournament-list');
                                    $.ajax({
                                        url : $(this).attr('href'),
                                    }).success(function(res) {
                                        var tournamentName = res.name;
                                        element.notify('Torneio \"' + tournamentName + '\" removido do plano!',
                                        {
                                            className : 'success',
                                            position : 'top center'
                                        });
                                        $.fn.yiiGridView.update('search-tournament-table');
                                        $.fn.yiiGridView.update('tournament-list');
                                    }).fail( function() {
                                        element.notify('Não foi possível remover o torneio do plano.',
                                        {
                                            className : 'error',
                                            position : 'top center'
                                        });
                                    });
                                    return false;
                                }
                             ",
                'url' => 'Yii::app()->createUrl("competitivePlan/removeTournament", array(
                                "federationTournamentID" => $data->primaryKey,
                                "athleteGroupID" => ' . $model->primaryKey . '
                            ))',
                'options'=>array(
                    'class'=>'btn btn-small',
                ),
            ),
        ),
    );
}

$gridColumns = array_merge($gridColumns, array(
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
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => 'CHtml::link($data->name, $data->getFederationSiteLink(), array(
                    "target" => "_blank"
                ));'
        ),
        'surface',
        array(
            'name' => 'federationClubID',
            'value' => '$data->federationClub->name',
        ),
        'city',
        array(
            'name' => 'ageBandsString',
            'header' => AgeBand::model()->getAttributeLabel('ageBandID'),
            'value' => '$data->ageBandsString',
        ),
    )
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