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

    public static function getNotTennisTournaments() {
        $result = array();
        foreach (self::$_notTennis as $other) {
            $result = array_merge($result, self::getTournamentsOfType($other));
        }
        return $result;
    }

    public static function getTournamentsOfType($type) {
        Yii::import('ext.SimpleHTMLDOM.SimpleHTMLDOM');
        echo "getting $type... ";
        $time = microtime(true);
        $html = (new SimpleHTMLDOM())->file_get_html(self::_getSearchUrlOf($type));
        /** @var SimpleHTMLDOMNode $message */
        $message = $html->find("div.list-footer span.message", -1);
        $messageArray = explode(" ", trim($message->text()));
        $lastMessageWord = end($messageArray);
        $numberOfResults = is_numeric($lastMessageWord) ? $lastMessageWord : 0;
        if ($numberOfResults < 1) {
            echo "no results found (time: ".sprintf('%.3f', microtime(true)-$time)."s)\n";
            return array();
        }
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
        echo "done (time: ".sprintf('%.3f', microtime(true)-$time)."s)\n";
        return $results;
    }

    private static function _getSearchUrlOf($type, $page = 1) {
        $startLimit = ($page - 1) * 20 + 1;
        return self::$_searchTournamentURI . "?md=$type&limitstart=$startLimit";
    }

    public static function getClubSiteLink($number) {
        return self::$_baseTournamentLink . "$number";
    }
}