<?php

$notification = Yii::app()->user->getFlash('savedPracticeSessionAttendance', null);
if ($notification !== NULL) {
    Yii::app()->clientScript->registerScript(
        'savedToDbNotification',
        '$(".breadcrumb").notify("' . $notification[1] . '", '
        . '{position: "bottom center", className: "' .
        ($notification[0] ? "success" : "error") . '", arrowShow: false});',
        CClientScript::POS_READY
    );
}

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/practiceSessionAttendance.js');

/* @var $form TbActiveForm */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
    'enableAjaxValidation' => true,
));
?>

<?php /** @var PracticeSessionHistoryRegistryForm $model */
echo $form->datePickerRow($model, 'date', array(
    'options' => array(
        'format' => "yyyy-mm-dd",
        'endDate' => 'today',
    ),
    'events' => array(
        'change' => 'js:function($el, status, e){afterValChange();}',
    ),
    'htmlOptions' => array(
        'class' => 'auto-submit-item',
    )
)); ?>

<?php
/* @var $loggedInUser User */
$loggedInUser = User::getLoggedInUser();
$coachInOneClub = count($loggedInUser->coachClubs) === 1;
?>



<?php
/** @var PracticeSessionHistoryRegistryForm $model */
//Club
echo $form->select2Row($model, 'clubID', array(
    'data' => $loggedInUser->getClubsCoachedOptions(),
    'options' => array(
        'enabled' => false,
        'minimumResultsForSearch' => -1
    ),
    'htmlOptions' => array('class' => 'auto-submit-item'),
));
?>

<?php
//Coach
echo $form->select2Row($model, 'coachID', array(
    'data' => $loggedInUser->getAdminedCoachesOptions(Club::model()->findByPk($model->clubID)),
    'options' => array(
        'minimumResultsForSearch' => -1
    ),
    'htmlOptions' => array('class' => 'auto-submit-item'),
));
?>

<?php if (isset($model->date, $model->coachID, $model->clubID)): ?>

    <?php
    $allowedPracticeSession = $model->isPracticeSessionAllowed();
    //PracticeSession
    echo $form->select2Row($model, 'practiceSessionID', array(
        'data' => $model->getPracticeSessionOptions(),
        'options' => array(
            "placeholder" => "Selecione a hora de início",
            'minimumResultsForSearch' => -1
        ),
        'htmlOptions' => array(
            'class' => 'auto-submit-item',
        )
    ));
    ?>

    <?php if ($allowedPracticeSession): ?>
        <?php
        $existsOnDb = $model->existsOnDb();
        $textToShow = $existsOnDb ? 'Existe informação sobre assiduidade a este treino no sistema.' :
            'Não existe informação sobre assiduidade a este treino registada no sistema.';
        $alertType = $existsOnDb ? 'success' : 'info';
        ?>
        <div class="alert alert-<?php echo $alertType; ?>">
            <?php echo $textToShow; ?>
        </div>

        <?php
        //PracticeCancelled
        echo $form->toggleButtonRow($model, 'cancelled', array(
            'enabledLabel' => 'SIM',
            'disabledLabel' => 'NÃO',
            'value' => false,
            'htmlOptions' => array(
                'class' => 'auto-submit-item',
            )
        )); ?>
        <?php
        $model->setupAttendance();
        $practiceSessionAthletes = $model->getPracticeSessionAthleteOptions();
        //AthletesAttended
        echo $form->select2Row($model, 'athletesAttended', array(
            'data' => $loggedInUser->getCoachedAthletesOptions(),
            'options' => array(
                "placeholder" => "Selecione atletas",
            ),
            'htmlOptions' => array(
                'multiple' => 'multiple',
            ),
        ));

        ?>
        <?php
        //AthletesUnnatended - Justified
        echo $form->select2Row($model, 'athletesJustifiedUnnatendance', array(
            'data' => $practiceSessionAthletes,
            'options' => array(
                "placeholder" => "Selecione atletas",
            ),
            'htmlOptions' => array(
                'multiple' => 'multiple',
            ),
        ));
        ?>
        <?php
        //AthletesUnttended - Not Justified
        echo $form->select2Row($model, 'athletesInjustifiedUnnatendance', array(
            'data' => $practiceSessionAthletes,
            'options' => array(
                'allowClear' => true,
                "placeholder" => "Selecione atletas",
            ),
            'htmlOptions' => array(
                'multiple' => 'multiple',
            ),
        ));
        ?>

        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => 'Registar',
            ));
            ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

    <div style="display: none">
        <?php echo $form->checkBoxRow($model, 'autoSubmit'); ?>
        <?php echo $form->checkBoxRow($model, 'clickedCancel'); ?>
    </div>

<?php $this->endWidget(); ?>