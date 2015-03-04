<?php

/**
 * This is the model class for table "FederationTournament".
 *
 * The followings are the available columns in table 'FederationTournament':
 * @property integer $federationTournamentID
 * @property string $level
 * @property string $qualyStartDate
 * @property string $qualyEndDate
 * @property string $mainDrawStartDate
 * @property string $mainDrawEndDate
 * @property string $name
 * @property string $city
 * @property string $surface
 * @property string $accommodation
 * @property integer $meals
 * @property integer $prizeMoney
 * @property integer $federationClubID
 * @property string $searchDateRange
 * @property integer $localCoordinateCacheID
 *
 * The followings are the available model relations:
 * @property AthleteGroup[] $athleteGroups
 * @property CompetitiveResultHistory[] $competitiveResultHistories
 * @property FederationClub $federationClub
 * @property AgeBand[] $ageBands
 * @property LocalCoordinateCache $localCoordinateCache
 */
class FederationTournament extends CExtendedActiveRecord {

    public $searchDateRange, $searchDistance = null, $cachedDistance = null;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'FederationTournament';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('federationTournamentID, level, mainDrawStartDate, mainDrawEndDate, name, city, surface, federationClubID, localCoordinateCacheID', 'required'),
            array('federationTournamentID, meals, prizeMoney, federationClubID, localCoordinateCacheID', 'numerical', 'integerOnly' => true),
            array('level', 'length', 'max' => 2),
            array('city, name', 'length', 'max' => 150),
            array('surface, accommodation', 'length', 'max' => 45),
            array('qualyStartDate, qualyEndDate', 'safe'),
            // The following rule is used by search().
            array('federationTournamentID, level, qualyStartDate, qualyEndDate, mainDrawStartDate, mainDrawEndDate, name,
            city, surface, accommodation, meals, prizeMoney, federationClubID, federationClub, ageBands, searchDateRange, searchDistance', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'athleteGroups' => array(self::MANY_MANY, 'AthleteGroup', 'CompetitivePlan(federationTournamentID, athleteGroupID)'),
            'competitiveResultHistories' => array(self::HAS_MANY, 'CompetitiveResultHistory', 'federationTournamentID'),
            'federationClub' => array(self::BELONGS_TO, 'FederationClub', 'federationClubID'),
            'ageBands' => array(self::MANY_MANY, 'AgeBand', 'FederationTournamentHasAgeBand(federationTournamentID, ageBandID)'),
            'localCoordinateCache' => array(self::BELONGS_TO, 'LocalCoordinateCache', 'localCoordinateCacheID'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'federationTournamentID' => 'Torneio FPT',
            'level' => 'Nível',
            'qualyStartDate' => 'Data Início Qualy',
            'qualyEndDate' => 'Data Início Qualy',
            'mainDrawStartDate' => 'Data Início Quadro',
            'mainDrawEndDate' => 'Data Fim Quadro',
            'name' => 'Nome',
            'city' => 'Local', //'Cidade',
            'surface' => 'Piso',
            'accommodation' => 'Dormida',
            'meals' => 'Alimentação',
            'prizeMoney' => 'Prize Money',
            'federationClubID' => 'Clube',
            'searchDateRange' => 'Período de tempo',
            'searchDistance' => 'Distância máxima ao clube',
            'ageBandsString' => 'Escalões',
            'ageBands' => 'Escalões',
            'cachedDistance' => 'Distância',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->together = true;
        $criteria->with = array('federationClub', 'ageBands');

        $criteria->compare('federationTournamentID', $this->federationTournamentID);
        $criteria->compare('level', $this->level);
        $criteria->compare('qualyStartDate', $this->qualyStartDate, true);
        $criteria->compare('qualyEndDate', $this->qualyEndDate, true);
        $criteria->compare('mainDrawStartDate', $this->mainDrawStartDate, true);
        $criteria->compare('mainDrawEndDate', $this->mainDrawEndDate, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('surface', $this->surface, true);
        $criteria->compare('accommodation', $this->accommodation, true);
        $criteria->compare('meals', $this->meals);
        $criteria->compare('prizeMoney', $this->prizeMoney);

        if (!CHelper::isNullOrEmptyString($this->searchDateRange)) {
            $dates = explode(" a ", $this->searchDateRange);
            $criteria->addBetweenCondition('IFNULL(qualyStartDate, mainDrawStartDate)',$dates[0], $dates[1]);
        }

        if ($this->federationClub !== null) {
            $criteria->compare('federationClub.name', $this->federationClub->name);
        }
        if ($this->ageBands !== null) {
            $criteria->compare('ageBands.ageBandID', $this->ageBands);
        }
        $criteria->group = 't.' . $this->getTableSchema()->primaryKey;

        $dataProvider = new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));


        if (!$this->isDistanceToBeCalculated()) {
            $dataProvider->pagination->pageSize = 5;
            $dataProvider->sort->defaultOrder = array("mainDrawStartDate" => CSort::SORT_ASC);
            return $dataProvider;
        }

        $club = User::getLoggedInUser()->clubs[0];
        $dataProvider->criteria->with[] = 'localCoordinateCache';
        $k = 111.044736; //69 miles, translated to km
        $deltaLat = (float)$this->searchDistance / $k;
        $deltaLng = $deltaLat / abs(cos($club->localCoordinateCache->lat));
        $limits = array(
            'lat' => array(
                'min' => (float)$club->localCoordinateCache->lat - $deltaLat,
                'max' => (float)$club->localCoordinateCache->lat + $deltaLat,
            ),
            'lng' => array(
                'min' => (float)$club->localCoordinateCache->lng - $deltaLng,
                'max' => (float)$club->localCoordinateCache->lng + $deltaLng,
            ),
        );
        $dataProvider->criteria->addBetweenCondition('localCoordinateCache.lat', $limits['lat']['min'], $limits['lat']['max']);
        $dataProvider->criteria->addBetweenCondition('localCoordinateCache.lng', $limits['lng']['min'], $limits['lng']['max']);
        $dataProvider->pagination = false;
        $results = array();
        /** @var FederationTournament $federationTournament */
        foreach ($dataProvider->getData() as $federationTournament) {
            if ($federationTournament->getDistance() <= $this->searchDistance) {
                $results[] = $federationTournament;
            }
        }

        return new CArrayDataProvider($results, array(
            'pagination' => array('pageSize' => 5),
            'sort' => array(
                'attributes' => array(
                    'mainDrawStartDate',
                    'qualyStartDate',
                    'name',
                    //'federationClub.name',
                    //'ageBandsString',
                    //'distance',
                    'level',
                    'surface',
                ),
                'defaultOrder' => array("mainDrawStartDate" => CSort::SORT_ASC),
            ),
        ));

    }

    protected function beforeValidate()
    {
        if ($this->isAttributeBlank('localCoordinateCacheID') && $this->canAssignLocalCoordinateCache()) {
            $this->setLocalCoordinateCache();
        }
        return parent::beforeValidate();
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FederationTournament the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getAgeBandsString() {
        $result = array();
        $this->refresh();
        foreach ($this->ageBands as $ageBand) {
            $result[] = $ageBand->name;
        }

        return implode("; ",$result);
    }

    public function getFederationSiteLink() {
        return CPortugueseTennisFederation::getClubSiteLink($this->primaryKey);
    }

    public function isInAthleteGroup($athleteGroupID) {
        foreach ($this->athleteGroups as $athleteGroup) {
            if ($athleteGroup->primaryKey == $athleteGroupID) {
                return true;
            }
        }
        return false;
    }

    public function getDateRange($includeYear = true) {
        setlocale(LC_TIME, 'pt_PT');
        $format = '%e-%h' . ($includeYear ? '-%Y' : '');
        return strftime($format, $this->getStartDate()->getTimestamp()) . " a " .
            strftime($format, CHelper::newDateTime($this->mainDrawEndDate)->getTimestamp());
    }

    public function hasQuali() {
        return !CHelper::isNullOrEmptyString($this->qualyStartDate);
    }

    private function getStartDate() {
        return CHelper::newDateTime($this->hasQuali() ? $this->qualyStartDate : $this->mainDrawStartDate);
    }

    public function getCommonColumns($printLinks = true) {
        return array(
            array(
                'name' => 'mainDrawStartDate',
                'header' => 'Datas de realização',
                'value' => '$data->getDateRange()'
            ),
            array(
                'name' => 'qualyStartDate',
                'header' => 'Qualifying',
                'value' => '$data->hasQuali() ? "Sim" : "Não"',
            ),
            'level',
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => $printLinks ? 'CHtml::link($data->name, $data->getFederationSiteLink(), array(
                    "target" => "_blank"
                ));' : '$data->name',
            ),
            'surface',
            array(
                'name' => 'federationClubID',
                'value' => '$data->federationClub->name',
            ),
            'city',
            'ageBandsString',
        );
    }

    public function getAdminColumn($ageGroup) {
        return array(
            'class'=>'booster.widgets.TbButtonColumn',
            'template' => '{delete}',
            'buttons' => array(
                'delete' => array (
                    'label' => 'Remover torneio do plano',
                    //'icon' => 'minus',
                    'click' => "function(){
                                    var element = $('#tournament-list');
                                    $.ajax({
                                        url : $(this).attr('href'),
                                    }).success(function(res) {
                                        var tournamentName = res.name;
                                        element.notify('Torneio \"' + tournamentName + '\" removido do plano!',
                                        {
                                            className : 'success',
                                            position : 'top center'
                                        });
                                        $.fn.yiiGridView.update('search-tournament-table');
                                        $.fn.yiiGridView.update('tournament-list');
                                    }).fail( function() {
                                        element.notify('Não foi possível remover o torneio do plano.',
                                        {
                                            className : 'error',
                                            position : 'top center'
                                        });
                                    });
                                    return false;
                                }
                             ",
                    'url' => 'Yii::app()->createUrl("competitivePlan/removeTournament", array(
                                "federationTournamentID" => $data->primaryKey,
                                "athleteGroupID" => ' . $ageGroup->primaryKey . '
                            ))',
                    'options'=>array(
                        'class'=>'btn btn-small',
                    ),
                ),
            ),
        );
    }

    public function isTennisTournament() {
        return NotTennisTournaments::model()->findByPk($this->primaryKey) === null;
    }

    protected function canAssignLocalCoordinateCache() {
        return !$this->isAttributeBlank('city') && !$this->isAttributeBlank('federationClubID');
    }

    protected function setLocalCoordinateCache() {
        $geocodingString = $this->getGeocodingSearchString();
        $localCoordinateCache = LocalCoordinateCache::model()->findByAttributes(array(
            'coordinatesSearchString' => $geocodingString,
        ));
        $localCoordinateCache = $localCoordinateCache === null ?
            LocalCoordinateCache::geocode($geocodingString) : $localCoordinateCache;
        $this->localCoordinateCacheID = $localCoordinateCache->primaryKey;
    }

    protected function getGeocodingSearchString() {
        if (!$this->canAssignLocalCoordinateCache()) {
            throw new CException("Not able to compile search string for geocoding FederationTournament {$this->primaryKey}");
        }
        /** @var FederationClub $federationClub */
        $federationClub = $this->federationClub == null ?
            FederationClub::model()->findByPk($this->federationClubID) : $this->federationClub;
        if ($federationClub == null) {
            throw new CException("Invalid FederationClubID {$federationClub->primaryKey}");
        }
        $clubPhoneArea = $federationClub->getLandPhoneAreaString();
        return CHelper::removeDiacritic($this->city . ($clubPhoneArea === null ? "" : ", $clubPhoneArea") . ", PT");
    }

    /**
     * @return bool
     */
    private function isDistanceToBeCalculated() {
        return $this->searchDistance !== null && $this->searchDistance > 0;
    }

    /**
     * @param $club Club
     */
    public function calculateDistanceTo($club) {
        if ($club->isAttributeBlank('localCoordinateCacheID')) { $club->setLocalCoordinateCache(); }
        $this->cachedDistance = $this->localCoordinateCache->calculateDistanceTo($club->localCoordinateCache);
    }

    /**
     * @param Club|null $club
     * @return float
     */
    public function getDistance($club = null) {
        if ($this->cachedDistance === null) {
            $club = $club === null ? User::getLoggedInUser()->clubs[0] : $club;
            $this->calculateDistanceTo($club);
        }
        return round($this->cachedDistance, 2);
    }
}
