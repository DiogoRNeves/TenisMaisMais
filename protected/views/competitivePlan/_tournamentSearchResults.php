<?php
/* @var $this CompetitivePlanController
 * @var $model AthleteGroup
 * @var $federationTournamentSearch FederationTournament
 */

$this->widget('booster.widgets.TbExtendedGridView', array(
    'id' => 'search-tournament-table',
    'responsiveTable' => true,
    //'fixedHeader' => true,
    //'headerOffset' => 50,
    'dataProvider' => $federationTournamentSearch->search(),
    //'type' => 'striped',
    //'filter' => $federationTournamentSearch,
    'columns' => array(
        array(
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
        ),
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
        'level',
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => 'CHtml::link($data->name, $data->getFederationSiteLink(), array(
                        "target" => "_blank",
                    ));'
        ),
        'surface',
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
    ),
));
/** @var CClientScript $cs */
$cs = Yii::app()->getClientScript();
$cs->registerScript('makeLargeModal', new CJavaScriptExpression('$("#searchTournament .modal-dialog").addClass("modal-lg");'));