<?php
/**
 * Created by PhpStorm.
 * User: diogoneves
 * Date: 01/02/15
 * Time: 11:24
 *
 * @property CExcelTournamentReader $_excelTournaments
 */

class ImportFptTournamentsFromXlsCommand extends CConsoleCommand {

    private $_excelTournaments, $_cachedAgeBand = array(), $_cachedTournamentVariation = array();
    private $_notTennisTournaments = array();

    const TOURNAMENT_VARIATIONS_DELIMITER = "; ";

    public function run($args) {
        $fileName = $args[0];
        $year = $args[1];
        if (!is_numeric($year)) {
            echo "The year must be numeric: '$year' given\n";
            return 3;
        }
        $this->defineNotTennisTournaments();
        $this->doCaching();
        try {
            $this->_excelTournaments = new CExcelTournamentReader(Yii::app()->basePath . '/data/' . $fileName);
        } catch (Exception $e) {
            echo $e->getMessage() . " on line " . $e->getLine();
            return 2;
        }

        if (!$this->deleteTournaments($year)) {
            echo "Unable to delete tournaments. Exiting.\n";
            return 1;
        }

        echo "Tournaments deleted.\n";

        for ($this->_excelTournaments->currentRow = $this->_excelTournaments->getFirstDataRow();
             $this->_excelTournaments->currentRow < $this->_excelTournaments->getMaxRow();
             $this->_excelTournaments->currentRow++) {
            $federationClub = $this->getFederationClub();
            if ($federationClub->isNewRecord) {
                echo 'Could not save club ' . $federationClub->name . ": " . $federationClub->getErrorsString();
            } else {
                /** @var FederationTournamentHasAgeBand[] $tournamentHasAgeBandWithErrors */
                /** @var FederationTournament $federationTournament */
                list($federationTournament, $tournamentHasAgeBandWithErrors) = $this->addTournamentToDb($federationClub->primaryKey);
                if ($federationTournament !== null) {
                    //is tennis tournament
                    if ($federationClub->isNewRecord) {
                        echo 'Could not save tournament ' . $federationTournament->primaryKey . ":\n" . $federationTournament->getErrorsString();
                    }
                    foreach ($tournamentHasAgeBandWithErrors as $tournamentHasAgeBand) {
                        echo 'Something went wrong for tournament ' . $federationTournament->primaryKey . ' ' .
                            " information, so it is not correct on DB.\n" . $tournamentHasAgeBand->getErrorsString() . "\n";
                    }
                }
            }
        }
        echo "Done. (" . count($this->_notTennisTournaments) . " not tennis tournaments found)\n";
        return 0;
    }

    /**
     * @param $federationClubID
     * @return array
     */
    private function addTournamentToDb($federationClubID)
    {
        $federationTournament = new FederationTournament;
        $federationTournament->attributes = array(
            'federationTournamentID' => $this->_excelTournaments->getNumber(),
            'federationClubID' => $federationClubID,
            'level' => $this->_excelTournaments->getLevel(),
            'qualyStartDate' => $this->_excelTournaments->getQualiStartDate(),
            'qualyEndDate' => $this->_excelTournaments->getQualiEndDate(),
            'mainDrawStartDate' => $this->_excelTournaments->getMainDrawStartDate(),
            'mainDrawEndDate' => $this->_excelTournaments->getMainDrawEndDate(),
            'name' => $this->_excelTournaments->getName(),
            'city' => $this->_excelTournaments->getLocal(),
            'surface' => $this->_excelTournaments->getSurface(),
            'accommodation' => $this->_excelTournaments->getAccomodation(),
            'meals' => $this->_excelTournaments->getMeals(),
            'prizeMoney' => $this->_excelTournaments->getPrizeMoney(),
        );
        $federationTournamentHasAgeBandWithErrors = array();
        if (!$federationTournament->isTennisTournament()) {
            $this->_notTennisTournaments[] = $federationTournament->primaryKey;
            $federationTournament = null;
        } elseif ($federationTournament->save()) {
            foreach ($this->getAgeVariationPairs() as list($ageBandID, $tournamentVariationID)) {
                $federationTournamentHasAgeBand = new FederationTournamentHasAgeBand;
                $federationTournamentHasAgeBand->ageBandID = $ageBandID;
                $federationTournamentHasAgeBand->federationTournamentID = $federationTournament->primaryKey;
                $federationTournamentHasAgeBand->tournamentVariationID = $tournamentVariationID;
                if (!$federationTournamentHasAgeBand->save()) {
                    $federationTournamentHasAgeBandWithErrors[] = $federationTournamentHasAgeBand;
                }
            }
        }
        return array($federationTournament, $federationTournamentHasAgeBandWithErrors);
    }

    /**
     * @return FederationClub
     */
    private function getFederationClub()
    {
        $attributes = array(
            'name' => $this->_excelTournaments->getClub(),
            'phoneNumber' => $this->_excelTournaments->getPhoneNumber(),
            'fax' => $this->_excelTournaments->getFaxNumber(),
        );
        /** @var FederationClub $federationClub */
        $federationClub = FederationClub::model()->find(CHelper::getCriteriaFromAttributes($attributes, 'OR'));
        if ($federationClub !== null) {
            return $federationClub;
        }
        $federationClub = new FederationClub;
        $federationClub->attributes = $attributes;
        if ($federationClub->validate()) {
            $federationClub->save(false);
        }
        return $federationClub;
    }

    private function deleteTournaments($year)
    {
        //TODO understand why deleteAll($criteria) won't work on FederationTournamentHasAgeBand
        $startDate = $year . "-01-01";
        $endDate = $year . "-12-31";
        $criteria = new CDbCriteria;
        $criteria->addBetweenCondition('mainDrawStartDate', $startDate, $endDate);
        $deleted = true;
        foreach (FederationTournament::model()->findAll($criteria) as $federationTournament) {
            $criteriaHas = new CDbCriteria;
            $criteriaHas->compare('federationTournamentID', $federationTournament->primaryKey);
            $previewDeleted = FederationTournamentHasAgeBand::model()->count($criteriaHas);
            if ($previewDeleted > 0) {
                $reallyDeleted = FederationTournamentHasAgeBand::model()->deleteAll($criteriaHas);
                $deleted = $deleted && ($previewDeleted == $reallyDeleted);
            }
            $deleted = $deleted && $federationTournament->delete();
        }
        return $deleted;
    }

    /**
     * @return array an array of pairs, array(ageBandID, tournamentVariationID)
     */
    private function getAgeVariationPairs()
    {
        /** @var array $result */
        $result = array();
        foreach ($this->_excelTournaments->getTournamentTypesArray() as $ageBandStr => $tournamentVariationsStr) {
            $ageBandID = $this->searchAgeBandIDFromCache($ageBandStr);
            if ($ageBandID !== null) {
                foreach (explode(self::TOURNAMENT_VARIATIONS_DELIMITER, $tournamentVariationsStr) as $tournamentVariationStr) {
                    $tournamentVariationID = $this->searchTournamentVariationIDFromCache($tournamentVariationStr);
                    $temp = array($ageBandID, $tournamentVariationID);
                    if ($tournamentVariationID !== null && !in_array($temp, $result)) {
                        $result[] = $temp;
                    }
                }
            }
        }
        return $result;
    }

    private function doCaching()
    {
        $this->_cachedAgeBand = CHelper::modelsIntoAssociativeArrayInverted(AgeBand::model()->findAll(), 'name');
        $this->_cachedTournamentVariation = CHelper::modelsIntoAssociativeArrayInverted(TournamentVariation::model()->findAll(), 'abbreviation');
    }

    private function searchAgeBandIDFromCache($ageBandStr)
    {
        if (!isset($this->_cachedAgeBand[$ageBandStr])) {
            echo 'Age Band not found: ' . $ageBandStr . "\n";
            return null;
        }
        return $this->_cachedAgeBand[$ageBandStr];
    }

    private function searchTournamentVariationIDFromCache($tournamentVariationStr)
    {
        if (!isset($this->_cachedTournamentVariation[$tournamentVariationStr])) {
            echo 'Tournament Variation not found: ' . $tournamentVariationStr . "\n";
            return null;
        }
        return $this->_cachedTournamentVariation[$tournamentVariationStr];
    }

    private function defineNotTennisTournaments() {
        echo "Checking non tennis tournaments... ";
        echo (NotTennisTournaments::model()->deleteAll() ? 'deleted old data' : 'could not delete old data') . "\n";
        foreach (CPortugueseTennisFederation::getNotTennisTournaments() as $number) {
            $add = new NotTennisTournaments;
            $add->federationTournamentID = $number;
            if (!$add->save()) {
                echo "Something wrong with $number.\n";
            }
        }
        echo "Found " . NotTennisTournaments::model()->count() . " not tennis tournaments.\n";
    }
}