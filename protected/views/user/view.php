<?php
/* @var $this UserController */
/* @var $model User */

$mailMessage = Yii::app()->user->getFlash('mailSent', null);
if ($mailMessage !== NULL) {
    Yii::app()->clientScript->registerScript(
            'mailSentNotification', 
            '$(".breadcrumb").notify("' . $mailMessage[1] . '", '
                . '{position: "bottom center", className: "' .
                ($mailMessage[0] ? "success" : "error" ) . '", arrowShow: false});',
            CClientScript::POS_READY
    );
}

$this->breadcrumbs = array(
    'Users' => array('index'),
    $model->name,
);

//Side menu populated by controller
$this->menu = $this->getSideMenuItems($model);
?>

<h1><?php echo $model->name; ?></h1>

<?php
$this->widget('booster.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        array(
            'name' => $model->getAttributeLabel('male'),
            'type' => 'raw',
            'value' => $model->getGender(),
        ),
        'birthDate',
        'federationNumber',
        'coachLevel.coachLevel',
        'playerLevel.playerLevel',
        'contact.email',
        'contact.cellularPhone',
        'contact.workPhone',
        'contact.fax',
        'contact.website',
        CHelper::getObjectsLinks($model->sponsors, 'name', $model->getAttributeLabel('sponsors')),
        CHelper::getObjectsLinks($model->sponsoredAthletes, 'name', $model->getAttributeLabel('sponsoredAthletes')),
    ),
));
?>
