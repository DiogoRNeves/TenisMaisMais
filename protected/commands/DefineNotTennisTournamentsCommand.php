<?php
/**
 * Created by PhpStorm.
 * User: diogoneves
 * Date: 17/02/15
 * Time: 14:04
 */

class DefineNotTennisTournamentsCommand extends CConsoleCommand {

    public function run($args) {
        echo (NotTennisTournaments::model()->deleteAll() ? 'deleted old data' : 'could not delete old data') . "\n";
        foreach (CPortugueseTennisFederation::getNotTennisTournaments() as $number) {
            $add = new NotTennisTournaments;
            $add->federationTournamentID = $number;
            if (!$add->save()) {
                echo "Something wrong with $number.\n";
            }
        }
        echo "done. Not tournaments (" . NotTennisTournaments::model()->count() . "):\n";
        /** @var NotTennisTournaments $notTournament */
        foreach (NotTennisTournaments::model()->findAll() as $notTournament) {
            echo $notTournament->federationTournamentID . "\n";
        }
        return 1;
    }

}