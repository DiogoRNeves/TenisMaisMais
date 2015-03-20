<?php
/* @var $this BootstrapRssReader */
Yii::app()->clientScript->registerCss("carousselCenterImage", '
.carousel {
  height: 400px;
  overflow: hidden;
  }

  .carousel-caption {
    top: 0;
    bottom: auto;
}

  .carousel-inner .item a img {
    min-height: 100%;
    min-width: 100%;
    max-width: 500%;
    max-height: 500%;
}');
?>
<div id="rss">
    <?php
    $this->widget(
            'booster.widgets.TbCarousel', array(
        'items' => $this->getBoosterCarouselItemsFromRSS(),
            )
    );
    ?>
    Notícias de <a target="_blank" href="<?php echo $this->poweredByUrl ?>"><?php echo $this->poweredByLabel ?></a>
</div>
