<?php
/* @var $this CompetitivePlanController
 * @var $model AthleteGroup
 */
?>
<div class="well" style="margin-top: 1em;">
<?php
    /* @var $form TbActiveForm */
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'tournament-plan-form',
    //'action' => Yii::app()->createUrl($this->id . '/' . $this->getFormAction(), array('id' => $model->athleteGroupID)),
    'type' => 'horizontal',
    'enableAjaxValidation' => true,
    ));
    ?>


<?php
Yii::app()->clientScript->registerScript('updateListSearchResults' , new CJavaScriptExpression(
    "function updateListSearchResults() { $.fn.yiiGridView.update('tournament-list', {
            data: $(\"#" . $form->id . "\").serialize() + '&FederationTournament_page=1'
        }); }"
));
$updateSearchResultsJS = new CJavaScriptExpression('function() { updateListSearchResults(); }');

echo $form->switchGroup($model, 'showPastEvents', array('widgetOptions' => array(
    //'id' => 'showPastEvents',
    'options' => array(
        'onText' => 'SIM',
        'offText' => 'NÃO',
        'offColor' => 'info',
        //'state' => false,
    ),
    'events' => array(
        'switchChange' => $updateSearchResultsJS,
    )
)));
?>

    <div class="form-actions">
        <?php
        $showPastEventsLink = $this->createUrl('downloadPdf', array(
            'id' => $model->primaryKey,
            'showPastEvents' => 1,
        ));
        $notShowPastEventsLink = $this->createUrl('downloadPdf', array(
            'id' => $model->primaryKey,
            'showPastEvents' => 0,
        ));
        $this->widget(
            'booster.widgets.TbButton',
            array(
                'buttonType' => 'link',
                'context' => 'primary',
                'icon' => 'download-alt',
                'label' => 'Descarregar PDF',
                'url' => '#',
                'htmlOptions' => array(
                    'onclick' => "bootbox.dialog({
                        title: 'Torneios passados',
                        message: 'Deseja incluír os torneios já terminados?',
                        buttons: {
                            cancel: {
                                label: 'Cancelar',
                                className: 'btn-info pull-left'
                            },
                            yes: {
                                label: 'Sim',
                                className: 'btn-default',
                                callback: function() {
                                    window.open('$showPastEventsLink', '_blank');
                                }
                            },
                            no: {
                                label: 'Não',
                                className: 'btn-primary',
                                callback: function() {
                                    window.open('$notShowPastEventsLink', '_blank');
                                }
                            }
                        }
                    })",
                ),
            )
        );
        ?>
    </div>

<?php $this->endWidget(); //end form ?>
</div>

<?php

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
            'header' => 'Datas de realização',
            'value' => '$data->getDateRange()'
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