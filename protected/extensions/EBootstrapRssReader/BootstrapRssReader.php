<?php

Yii::import('ext.EBootstrapRssReader.*');
require_once('components/rss_php.php');

/**
 * Description of BootstrapRssReader
 *
 * @author diogoneves
 */
class BootstrapRssReader extends CWidget {

    public $url = '';
    public $poweredByLabel = '';
    public $poweredByUrl = '';
    public $maxCount = 5; //defaults to 5
    public $linkOptions = array();
    public $imagesSubfolder = '';
    private $images = array();
    private $imagesFolder = '';

    public function run() {
        $this->imagesFolder = Yii::getPathOfAlias('application') . "/../images" . ($this->imagesSubfolder == '' ? '' : ("/" . $this->imagesSubfolder));
        $this->initImages();
        $this->render('bootstrapRssReader');
    }
    
    private function initImages() {
        $this->images = CFileHelper::findFiles($this->imagesFolder);
        shuffle($this->images);
    }

    public function getBoosterCarouselItemsFromRSS() {
        $result = array();
        try {
            set_error_handler(create_function('', "throw new Exception(); return true;"));
            /* @var $items rss_php[] */
            $items = $this->getItems();
            for ($n = 0; $n < $this->maxCount; $n++) {
                $item = $items[$n];
                $result[] = array(
                    'image' => $this->generateImageLink($n),
                    'label' => $item['title'],
                    'caption' => $this->getCaption($item),
                    'link' => $item['link'],
                    'linkOptions' => $this->linkOptions,
                );
            }
        } catch (Exception $e) {
            $result[] = array(
                'image' => $this->generateImageLink(0),
                'label' => "Não foi possível carregar as notícias",
                'caption' => "Não foi possível carregar as notícias",
                'link' => null,
                'linkOptions' => $this->linkOptions,
            );
        }
        restore_error_handler();
        return $result;
    }

    private function getCaption($item) {
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($item['description'], 'HTML-ENTITIES', 'UTF-8'));
        libxml_use_internal_errors(false);
        $tags = $dom->getElementsByTagName('font');
        $i = 0;
        while (count_chars($tags->item($i)->nodeValue) < count_chars($item['title'])) {
            $i++;
        }
        return $tags->item($i)->getElementsByTagName('font')->item(2)->nodeValue;
    }

    private function getItems() {
        $rss = new rss_php;
        $rss->load($this->url);
        return $rss->getItems();
    }

    public function generateImageLink($n) {
        $temp = str_replace($this->imagesFolder, "images/" . ($this->imagesSubfolder == '' ? '' : ("/" . $this->imagesSubfolder)) ,$this->images[$n]);
        return str_replace("//", "/", Yii::app()->baseUrl . '/' . $temp);
    }

}
