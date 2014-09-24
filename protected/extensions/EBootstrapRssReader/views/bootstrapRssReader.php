<?php
/* @var $this BootstrapRssReader */
Yii::app()->clientScript->registerCss("carousselCenterImage", '  
  .carousel-inner a img {
    margin-left: auto;
    margin-right: auto;
    height: 400px;
}');
?>
<div id="rss">
    <?php
    $this->widget(
            'bootstrap.widgets.TbCarousel', array(
        'items' => $this->getBoosterCarouselItemsFromRSS(),
            )
    );
    ?>
    Notícias de <a target="_blank" href="<?php echo $this->poweredByUrl ?>"><?php echo $this->poweredByLabel ?></a>
</div>
