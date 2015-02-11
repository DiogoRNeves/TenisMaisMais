<?php
/* @var $this CompetitivePlanController
 * @var $model AthleteGroup
 * @var $federationTournamentSearch FederationTournament
 */
$this->beginWidget(
    'booster.widgets.TbModal',
    array(
        'id' => 'searchTournament',
        )
); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Adicionar Torneio</h4>
    </div>

    <div class="modal-body">
        <?php
        $this->widget('booster.widgets.TbExtendedGridView', array(
            'id' => 'search-tournament-table',
            'responsiveTable' => true,
            //'fixedHeader' => true,
            //'headerOffset' => 50,
            'dataProvider' => $federationTournamentSearch->search(),
            //'type' => 'striped',
            'filter' => $federationTournamentSearch,
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
                    'name' => 'federationClub.name',
                    'value' => '$data->federationClub->name',
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
        ?>

        <div class="modal-footer">
            <?php $this->widget(
                'booster.widgets.TbButton',
                array(
                    'label' => 'Fechar',
                    'url' => '#',
                    'htmlOptions' => array('data-dismiss' => 'modal'),
                )
            ); ?>
        </div>
    </div>

<?php $this->endWidget(); //end modal?>