<?php
/* @var $this CompetitivePlanController
 * @var $model AthleteGroup
 */
$this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'competitivePlan')
); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Atletas do Plano Competitivo</h4>
</div>

<div class="modal-body">
    <?php
    /* @var $form TbActiveForm */
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'athlete-group-form',
        'action' => Yii::app()->createUrl($this->id . '/' . $this->getFormAction(), array('id' => $model->athleteGroupID)),
        'type' => 'horizontal',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => true,
    ));
    ?>

    <p class="note">Os campos marcados com <span class="required">*</span> são obrigatórios.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldGroup($model, 'friendlyName'); ?>

    <?php
        if ($this->getFormAction() === "create") {
            echo CHelper::select2Row($form, $model, 'clubID', CHtml::listData(User::getLoggedInUser()->coachClubs, 'clubID', 'name'));
        }
    ?>

    <?php echo $form->textFieldGroup($model, 'minAge'); ?>

    <?php echo $form->textFieldGroup($model, 'maxAge'); ?>

    <?php echo CHelper::select2Row($form, $model, 'minPlayerLevelID', PlayerLevel::model()->getListData()) ?>

    <?php echo CHelper::select2Row($form, $model, 'maxPlayerLevelID', PlayerLevel::model()->getListData()) ?>

    <?php echo $form->switchGroup($model, 'includeMale', array('widgetOptions' => array(
        'options' => array(
        'onText' => 'SIM',
        'offText' => 'NÃO',
        'value' => true,
        ),
    ))); ?>

    <?php echo $form->switchGroup($model, 'includeFemale', array('widgetOptions' => array(
        'options' => array(
            'onText' => 'SIM',
            'offText' => 'NÃO',
            'value' => true,
        ),
    ))); ?>
</div>

<div class="modal-footer">
    <?php
    $redirectURL = $this->createUrl("view", array('id' => "athleteGroupID"));
    $redirectJavascript = 'window.location.replace("'. $redirectURL . '".replace("athleteGroupID", data.athleteGroupID))';
    $updateJavascript = '$.fn.yiiGridView.update("athlete-list");';
    $javascript = $this->getFormAction() === 'create' ? $redirectJavascript : $updateJavascript;
    $this->widget(
        'booster.widgets.TbButton',
        array(
            'buttonType' => 'ajaxSubmit',
            'context' => 'primary',
            'label' => 'Gravar',
            'url' => $form->action,
            'ajaxOptions' => array(
                'type' => 'POST',
                'data'=>'js:$("#' . $form->id . '").serialize()',
                'success' => 'function(data) { ' . $javascript . '; }',
            ),
            'htmlOptions' => array('data-dismiss' => 'modal'),
        )
    ); ?>
    <?php $this->widget(
        'booster.widgets.TbButton',
        array(
            'label' => 'Fechar',
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal'),
        )
    ); ?>
</div>

    <?php $this->endWidget(); //end form ?>

<?php $this->endWidget(); //end modal ?>