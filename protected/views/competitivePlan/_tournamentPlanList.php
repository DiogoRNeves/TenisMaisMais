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
                                    window.location.replace('$showPastEventsLink');
                                }
                            },
                            no: {
                                label: 'Não',
                                className: 'btn-primary',
                                callback: function() {
                                    window.location.replace('$notShowPastEventsLink');
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

<?php $this->renderPartial('_plansTournaments', array('model' => $model));

