<?php
/* @var $this UserController */
/* @var $dataProvider CArrayDataProvider */
/* @var $userType UserType */

$userTypeText = $userType == null ? 'Utilizador' : ucwords($userType->name);

$this->breadcrumbs = array(
    $userTypeText,
);
?>

<h1><?php echo $userTypeText; ?></h1>

<?php
Yii::app()->clientScript->registerScript('search', "var ajaxUpdateTimeout;
    var ajaxRequest;
    $('input#name').keyup(function(){
        ajaxRequest = $(this).serialize();
        clearTimeout(ajaxUpdateTimeout);
        ajaxUpdateTimeout = setTimeout(function () {
            $.fn.yiiListView.update(
                'ajaxListView',
                {data: ajaxRequest}
            )
        },
// this is the delay
        300);
    });"
);
echo CHtml::beginForm(CHtml::normalizeUrl(array('user/index')), 'get', array('id' => 'filter-form',
    'class' => 'form-search'
        )
);
?>
<div class="input-prepend">
    <span class="add-on"><i class="icon-search"></i></span>
        <?php
        echo CHtml::textField('name', (isset($_GET['name'])) ? $_GET['name'] : '', array('id' => 'name',
            'class' => "input-prepend search-query",
            'placeholder' => "Nome do $userTypeText")
        );
        ?>
</div>

<div hidden="true">
    <?php echo CHtml::textField('userType', (isset($_GET['userType'])) ? $_GET['userType'] : '', array('id' => 'userType')); ?>
    <?php echo CHtml::submitButton('Submit', array('name' => '', 'class' => 'add-on')); ?>
</div>

<?php
echo CHtml::endForm();
?>

<?php
$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'id' => 'ajaxListView'
));
?>
