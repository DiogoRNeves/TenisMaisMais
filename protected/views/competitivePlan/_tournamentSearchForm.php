<?php
/* @var $this CompetitivePlanController
 * @var $model FederationTournament
 */


/* @var $form TbActiveForm */
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'search-tournament-form',
    //'action' => Yii::app()->createUrl($this->id . '/' . $this->getFormAction(), array('id' => $model->athleteGroupID)),
    'type' => 'horizontal',
    'enableAjaxValidation' => true,
));
?>

<?php //echo $form->textFieldGroup($model, 'friendlyName'); ?>

<?php
    Yii::app()->clientScript->registerScript('updateSearchResults' , new CJavaScriptExpression(
        "function updateSearchResults() { $.fn.yiiGridView.update('search-tournament-table', {
            data: $(\"#" . $form->id . "\").serialize() + '&" . get_class($model) . "_page=1'
        }); }"
    ));
    $updateSearchResultsJS = new CJavaScriptExpression('function() { updateSearchResults(); }');
    echo $form->dateRangeGroup($model, 'searchDateRange', array(
        'widgetOptions' => array(
            'options' => array(
                'format' => 'YYYY-MM-DD',
                'separator' => ' a ',
                'ranges' => array(
                    'Este mês' => array(
                        new CJavaScriptExpression('moment()'),
                        new CJavaScriptExpression('moment().endOf("month")'),
                    ),
                    'Próximo mês' => array(
                        new CJavaScriptExpression('moment().add("month", 1).startOf("month")'),
                        new CJavaScriptExpression('moment().add("month", 1).endOf("month")'),
                    ),
                    'Três meses' => array(
                        new CJavaScriptExpression('moment()'),
                        new CJavaScriptExpression('moment().add("month", 3).endOf("month")'),
                    ),
                    'Este ano' => array(
                        new CJavaScriptExpression('moment()'),
                        new CJavaScriptExpression('moment().endOf("year")'),
                    ),
                    'Próximo ano' => array(
                        new CJavaScriptExpression('moment().add("year", 1).startOf("year")'),
                        new CJavaScriptExpression('moment().add("year", 1).endOf("year")'),
                    ),
                ),
                //'startDate' => new CJavaScriptExpression('moment()'),
                //'endDate' => new CJavaScriptExpression('moment().endOf("year")'),
                'locale' => array(
                    'fromLabel' => 'De',
                    'toLabel' => 'A',
                    'applyLabel' => 'Aplicar',
                    'cancelLabel' => 'Cancelar',
                    'customRangeLabel' => 'Outras Datas'
                ),
            ),
            'callback' => $updateSearchResultsJS,
        ),
    ));
?>

<?php
    echo $form->select2Group($model, 'ageBands', array(
        'widgetOptions' => array(
            'data' => AgeBand::model()->getListData(),
            'htmlOptions' => array(
                'multiple' => 'multiple',
            ),
            'options' => array('placeholder' => $model->getAttributeLabel('ageBands')),
            'events' => array(
                'change' => $updateSearchResultsJS,
            ),
        ),
    ));
?>

<?php
//echo $form->textFieldGroup($model, 'username',array('label'=>Yii::t('model','Username')));
echo $form->select2Group($model, 'surface', array(
    'widgetOptions' => array(
        'data' => array("Duro" => "Duro", "Terra" => "Terra", "Relva" => "Relva"),
        'htmlOptions' => array('multiple' => 'multiple'),
        'options' => array('placeholder' => $model->getAttributeLabel('surface')),
        'events' => array(
            'change' => $updateSearchResultsJS,
        ),
    ),
    'label' => $model->getAttributeLabel('surface'),
));
?>

<?php
echo $form->select2Group($model, 'level', array(
    'widgetOptions' => array(
        'data' => array("C" => "Nível C", "B" => "Nível B", "A" => "Nível A", "CR" => "Campeonato Regional", "CN" => "Campeonato Nacional"),
        'htmlOptions' => array('multiple' => 'multiple'),
        'options' => array('placeholder' => $model->getAttributeLabel('level')),
        'events' => array(
            'change' => $updateSearchResultsJS,
        ),
    ),
    'label' => $model->getAttributeLabel('level'),
));
?>

<?php echo $form->textFieldGroup($model, 'searchDistance'); ?>

<?php $this->widget('booster.widgets.TbLabel', array(
        'context' => 'info',
        'label' => 'Distância aproximada em linha reta a "' . User::getLoggedInUser()->clubs[0]->getGeocodingSearchString() . '"',
    )
); ?>

<div class="form-actions pull-right">
    <?php
        $this->widget(
            'booster.widgets.TbButton',
            array(
                'buttonType' => 'ajaxSubmit',
                'context' => 'primary',
                'icon' => 'search',
                'label' => 'Pesquisar',
                'htmlOptions' => array(
                    'class' => 'pull-right',
                    'onclick' => "updateSearchResults(); return false;"
                ),
            )
        );
    ?>
</div>

<?php $this->endWidget(); //end form ?>