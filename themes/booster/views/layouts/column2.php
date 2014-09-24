<?php
$this->beginContent('//layouts/main');
$showOperations = !($this->menu == null || $this->menu == array());
?>
<div class="row-fluid">    
    <?php if ($showOperations): ?>
        <div class="span3">
            <?php
            $header = array(
                'label' => "Ações",
                'itemOptions' => array('class' => 'nav-header')
            );
            $this->widget('bootstrap.widgets.TbMenu', array(
                'type' => 'tabs',
                'items' => array_merge(array($header), $this->menu),
                'htmlOptions' => array('class' => 'operations',
                    'class' => 'nav-stacked',
                ),
            ));
            ?>
        </div><!-- sidebar -->
    <?php endif ?>
    <div class="span<?php echo $showOperations ? "9" : "12" ?>">
        <?php echo $content; ?>
    </div><!-- content -->
</div>
<?php $this->endContent(); ?>