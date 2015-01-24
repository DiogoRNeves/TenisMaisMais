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
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
    'enableAjaxValidation' => true,
    'type' => 'horizontal',
));
?>

<?php /** @var PracticeSessionHistoryRegistryForm $model */
echo $form->datePickerGroup($model, 'date', array('widgetOptions' => array(
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
))); ?>

<?php
/* @var $loggedInUser User */
$loggedInUser = User::getLoggedInUser();
$coachInOneClub = count($loggedInUser->coachClubs) === 1;
?>



<?php
/** @var PracticeSessionHistoryRegistryForm $model */
//Club
echo $form->select2Group($model, 'clubID', array('widgetOptions' => array(
            'data' => $loggedInUser->getClubsCoachedOptions(),
            'options' => array(
                'enabled' => false,
                'minimumResultsForSearch' => -1,
                'width' => '70%',
            ),
            'htmlOptions' => array('class' => 'auto-submit-item'),
        )
));
?>

<?php
//Coach
echo $form->select2Group($model, 'coachID', array('widgetOptions' => array(
    'data' => $loggedInUser->getAdminedCoachesOptions(Club::model()->findByPk($model->clubID)),
    'options' => array(
        'minimumResultsForSearch' => -1,
        'width' => '70%',
    ),
    'htmlOptions' => array('class' => 'auto-submit-item'),
)));
?>

<?php if (isset($model->date, $model->coachID, $model->clubID)): ?>

    <?php
    $allowedPracticeSession = $model->isPracticeSessionAllowed();
    //PracticeSession
    echo $form->select2Group($model, 'practiceSessionID', array('widgetOptions' => array(
        'data' => $model->getPracticeSessionOptions(),
        'options' => array(
            "placeholder" => "Selecione a hora de início",
            'minimumResultsForSearch' => -1,
            'width' => '70%',
        ),
        'htmlOptions' => array(
            'class' => 'auto-submit-item',
        )
    )));
    ?>

    <?php if ($allowedPracticeSession): ?>
	<?php
        $existsOnDb = $model->existsOnDb();
        $textToShow = ucfirst(($existsOnDb ? '' : 'Não ') . 'existe informação sobre assiduidade a este treino no sistema.');
        $alertType = $existsOnDb ? 'success' : 'info';
        ?>
        <div class="alert alert-<?php echo $alertType; ?>" style="margin-top:20px">
            <?php echo $textToShow; ?>
        </div>
        <?php
        //PracticeCancelled
        echo $form->switchGroup($model, 'cancelled', array('widgetOptions' => array(
            'options' => array(
                'onText' => 'SIM',
                'offText' => 'NÃO',
                'value' => false,
            ),
            'htmlOptions' => array(
                'class' => 'auto-submit-item',
            )),
            'hint' => 'Devido a chuva, por exemplo.'
        )); ?>
        <?php
        $model->setupAttendance();
        $practiceSessionAthletes = $model->getPracticeSessionAthleteOptions();
        //AthletesAttended
        echo $form->select2Group($model, 'athletesAttended', array('widgetOptions' => array(
            'data' => $loggedInUser->getCoachedAthletesOptions(),
            'options' => array(
                "placeholder" => "Selecione atletas",
                'width' => '70%',
            ),
            'htmlOptions' => array(
                'multiple' => 'multiple',
            ),
        )));

        ?>
        <?php
        //AthletesUnnatended - Justified
        echo $form->select2Group($model, 'athletesJustifiedUnnatendance', array('widgetOptions' => array(
            'data' => $practiceSessionAthletes,
            'options' => array(
                "placeholder" => "Selecione atletas",
                'width' => '70%',
            ),
            'htmlOptions' => array(
                'multiple' => 'multiple',
            ),
        )));
        ?>
        <?php
        //AthletesUnttended - Not Justified
        echo $form->select2Group($model, 'athletesInjustifiedUnnatendance', array('widgetOptions' => array(
            'data' => $practiceSessionAthletes,
            'options' => array(
                'allowClear' => true,
                "placeholder" => "Selecione atletas",
                'width' => '70%',
            ),
            'htmlOptions' => array(
                'multiple' => 'multiple',
            ),
        )));
        ?>

        <div class="form-actions">
            <?php
            $this->widget('booster.widgets.TbButton', array(
                'buttonType' => 'submit',
                'context' => 'primary',
                'label' => 'Gravar',
            ));
            ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

    <div style="display: none">
        <?php echo $form->checkBox($model, 'autoSubmit'); ?>
        <?php echo $form->checkBox($model, 'clickedCancel'); ?>
    </div>

<?php $this->endWidget(); ?>