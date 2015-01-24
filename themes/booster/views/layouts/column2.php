<?php
$this->beginContent('//layouts/main');
$showOperations = !($this->menu == null || $this->menu == array());
?>
<div class="row-fluid">    
    <?php if ($showOperations): ?>
        <div class="col-md-3">
            <?php
            $header = array(
                'label' => "Ações",
                'itemOptions' => array('class' => 'nav-header')
            );
            $this->widget('booster.widgets.TbMenu', array(
                'type' => 'pills',
                'items' => array_merge(array($header), $this->menu),
                'htmlOptions' => array('class' => 'operations',
                    'class' => 'nav-stacked',
                ),
            ));
            ?>
        </div><!-- sidebar -->
    <?php endif ?>
    <div class="col-md-<?php echo $showOperations ? "9" : "12" ?>">
        <?php echo $content; ?>
    </div><!-- content -->
</div>
<?php $this->endContent(); ?>