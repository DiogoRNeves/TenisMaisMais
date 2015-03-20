<?php
/**
 * Created by PhpStorm.
 * User: diogoneves
 * Date: 17/02/15
 * Time: 21:14
 */

class CPortugueseTennisFederation {
    private static $_searchTournamentURI = 'http://www.tenis.pt/index.php/competicao/calendario/nacionais/tournements';
    private static $_notTennis = array('CadeiraRodas', 'Praia', 'Padel');
    private static $_baseTournamentLink = 'http://www.tenis.pt/index.php/competicao/calendario/nacionais/tournement/';
    private static $_NOT_TENNIS_TOURNAMENTS_CACHE_LOCATION = '/data/cachedNotTennisTournaments.json';

    public static function getNotTennisTournaments($updateCache = false) {
        if ($updateCache) {
            if (!self::updateNotTennisTournamentsCache()) {
                throw new Exception("Couldn't write JSON HTML file to disk.");
            }
        }
        $result = array();
        foreach (self::$_notTennis as $other) {
            $result = array_merge($result, self::getTournamentsOfType($other));
        }
        return $result;
    }

    public static function getTournamentsOfType($type) {
        Yii::import('ext.SimpleHTMLDOM.SimpleHTMLDOM');
        $cacheContents = CJSON::decode(file_get_contents(self::getCacheLocation()));
        $html = (new SimpleHTMLDOM())->str_get_html($cacheContents[$type]);
        /** @var SimpleHTMLDOMNode $message */
        $message = $html->find("div.list-footer span.message", -1);
        $messageArray = explode(" ", trim($message->text()));
        $lastMessageWord = end($messageArray);
        $numberOfResults = is_numeric($lastMessageWord) ? $lastMessageWord : 0;
        $numberOfPages = floor(($numberOfResults - 1) / 20) + 1;
        $results =  array();
        for($page = 1; $page <= $numberOfPages; $page++) {
            if ($page > 1) { $html = (new SimpleHTMLDOM())->file_get_html(self::_getSearchUrlOf($type, $page)); }
            /** @var SimpleHTMLDOMNode[] $rows */
            $tournamentNumbers = $html->find("td.col1");
            foreach ($tournamentNumbers as $number) {
                /** @var SimpleHTMLDOMNode $number */
                $numTextArray = explode(" ", $number->text());
                $results[] = $numTextArray[0];
            }
        }
        return $results;
    }

    private static function _getSearchUrlOf($type, $page = 1) {
        $startLimit = ($page - 1) * 20 + 1;
        return self::$_searchTournamentURI . "?md=$type&limitstart=$startLimit";
    }

    public static function getClubSiteLink($number) {
        return self::$_baseTournamentLink . "$number";
    }

    public static function updateNotTennisTournamentsCache() {
        //url: self::_getSearchUrlOf($type)
        $result = array();
        foreach (self::$_notTennis as $type) {
            echo "getting $type HTML... ";
            $time = microtime(true);
            $result[$type] = file_get_contents(self::_getSearchUrlOf($type));
            echo "done (time: ".sprintf('%.3f', microtime(true)-$time)."s)\n";
        }
        return file_put_contents(self::getCacheLocation(), CJSON::encode($result));
    }

    /**
     * @return string
     */
    private static function getCacheLocation() {
        return Yii::app()->basePath . self::$_NOT_TENNIS_TOURNAMENTS_CACHE_LOCATION;
    }
}