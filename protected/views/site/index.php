<?php

/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;

$loggedUser = User::model()->findByPk(Yii::app()->user->id);
$this->menu = Yii::app()->user->isGuest ? null : $loggedUser->getQuickActions();

$this->widget('application.extensions.EBootstrapRssReader.BootstrapRssReader', array(
    'url' => "https://news.google.pt/news/feeds?hl=pt-PT&gl=pt&tbm=nws&authuser=0&q=t%C3%A9nis+-mesa&oq=t%C3%A9nis+-mesa&output=rss",
    'maxCount' => 6,
    'linkOptions' => array('target' => "_blank"),
    'imagesSubfolder' => 'tennisNews',
    'poweredByLabel' => 'Google Notícias',
    'poweredByUrl' => 'https://www.google.pt/search?hl=pt-PT&gl=pt&tbm=nws&authuser=0&q=tenis+-mensa&oq=tenis+-mensa&gs_l=news-cc.3..43j43i53.1005.7069.0.7319.14.2.0.12.0.1.127.207.1j1.2.0...0.0...1ac.1.uAWFMbSmgwA#hl=pt-PT&gl=pt&authuser=0&tbm=nws&q=tenis+-mesa',
));
?>
