<?php
/**
 * Created by PhpStorm.
 * User: diogoneves
 * Date: 19/03/15
 * Time: 23:56
 */

class GetNonTennisTournamentsHTMLCommand extends CConsoleCommand {
    public function run($args) {
        return CPortugueseTennisFederation::updateNotTennisTournamentsCache() ? 0 : 1;
    }

}