<?php

/**
 * This is the model class for table "AthleteGroup".
 *
 * The followings are the available columns in table 'AthleteGroup':
 * @property integer $athleteGroupID
 * @property integer $minAge
 * @property integer $maxAge
 * @property integer $clubID
 * @property integer $minPlayerLevelID
 * @property integer $maxPlayerLevelID
 * @property string $friendlyName
 * @property boolean $includeMale
 * @property boolean $includeFemale
 * @property boolean $active
 *
 * The followings are the available model relations:
 * @property Club $club
 * @property FederationTournament[] $federationTournaments
 * @property PlayerLevel $minPlayerLevel
 * @property PlayerLevel $maxPlayerLevel
 */
class AthleteGroup extends CExtendedActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'AthleteGroup';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('clubID, friendlyName', 'required'),
            array('athleteGroupID, minAge, maxAge, clubID, minPlayerLevelID, maxPlayerLevelID', 'numerical', 'integerOnly' => true),
            array('friendlyName', 'length', 'max' => 60),
            array('includeMale, includeFemale active', 'boolean'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('athleteGroupID, minAge, maxAge, clubID, minPlayerLevelID, maxPlayerLevelID', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'club' => array(self::BELONGS_TO, 'Club', 'clubID'),
            'minPlayerLevel' => array(self::BELONGS_TO, 'PlayerLevel', 'minPlayerLevelID'),
            'maxPlayerLevel' => array(self::BELONGS_TO, 'PlayerLevel', 'maxPlayerLevelID'),
            'federationTournaments' => array(self::MANY_MANY, 'FederationTournament', 'CompetitivePlan(athleteGroupID, federationTournamentID)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'athleteGroupID' => 'Filtro de Atletas',
            'friendlyName' => 'Nome do Plano Competitivo',
            'minAge' => 'Idade mínima',
            'maxAge' => 'Idade máxima',
            'minPlayerLevelID' => 'Nível mínimo de jogador',
            'maxPlayerLevelID' => 'Nível máximo de jogador',
            'includeMale' => 'Masculinos',
            'includeFemale' => 'Femininos',
            'clubID' => 'Clube',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AthleteGroup the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }


    public function searchFederationTournaments()
    {

        $criteria = new CDbCriteria;
        $criteria->with = array('athleteGroups');
        $criteria->together = true; //allows gridview to render properly with pagination
        $criteria->compare('athleteGroups.athleteGroupID', $this->primaryKey);

        $federationTournament = new FederationTournament('search');

        $sort = new CSort(get_class($federationTournament));
        $sort->defaultOrder = array("mainDrawStartDate" => CSort::SORT_ASC);

        return new CActiveDataProvider($federationTournament, array(
            'criteria' => $criteria,
            'sort' => $sort,
        ));
    }

    public function searchAthletes()
    {
        $criteria = new CDbCriteria;
        $criteria->together = true;
        $criteria->with = array('athleteClubs');
        if (!$this->isAttributeBlank('maxAge')) {
            $criteria->compare('YEAR(birthDate)', '>=' . CHelper::ageToYearString($this->maxAge));
        }
        if (!$this->isAttributeBlank('minAge')) {
            $criteria->compare('YEAR(birthDate)', '<=' . CHelper::ageToYearString($this->minAge));
        }
        if (!$this->isAttributeBlank('minPlayerLevelID')) {
            $criteria->compare('playerLevelID', '<=' . $this->minPlayerLevelID);
        }
        if (!$this->isAttributeBlank('maxPlayerLevelID')) {
            $criteria->compare('playerLevelID', '>=' . $this->maxPlayerLevelID);
        }
        $criteria->compare('athleteClubs.clubID', $this->clubID);
        if ($this->includeMale && !$this->includeFemale) {
            $criteria->compare('male', '1');
        } elseif (!$this->includeMale && $this->includeFemale) {
            $criteria->compare('male', '0');
        }

        $user = new User;

        $sort = new CSort(get_class($user));
        $sort->defaultOrder = array("name" => CSort::SORT_ASC);

        return new CActiveDataProvider($user, array(
            'criteria' => $criteria,
            'sort' => $sort,
        ));
    }

    /**
     * @param $user User
     * @return bool
     */
    public function appliesTo($user)
    {
        return $user->isSystemAdmin() || $this->appliesToCoach($user) || $this->appliesToAthlete($user) || $this->appliesToSponsor($user);
    }

    /**
     * @param $coach User
     * @return bool
     */
    private function appliesToCoach($coach)
    {
        return $coach->isCoachAt($this->club);
    }

    /**
     * @param $athlete User
     * @return bool
     */
    private function appliesToAthlete($athlete)
    {
        return $this->isAthleteClubOK($athlete) && $this->isAthleteGenderOK($athlete) && $this->isAthleteAgeOK($athlete) && $this->isAthletePlayerLevelOK($athlete);
    }

    /**
     * @param $sponsor User
     * @return bool
     */
    private function appliesToSponsor($sponsor)
    {
        foreach ($sponsor->sponsoredAthletes as $athlete) {
            if ($this->appliesToAthlete($athlete)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $athlete User
     * @return bool
     */
    private function isAthleteAgeOK($athlete)
    {
        $result = true;
        $birthYear = (int)(new DateTime($athlete->birthDate))->format('Y');
        if (!$this->isAttributeBlank('maxAge')) {
            $result = $result && (int)CHelper::ageToYearString($this->maxAge) >= $birthYear;
        }
        if (!$this->isAttributeBlank('minAge')) {
            $result = $result && (int)CHelper::ageToYearString($this->minAge) <= $birthYear;
        }
        return $result;
    }

    /**
     * @param $athlete User
     * @return bool
     */
    private function isAthleteGenderOK($athlete)
    {
        return ($this->includeFemale && !$athlete->male) || ($this->includeMale && $athlete->male);
    }

    /**
     * @param $athlete User
     * @return bool
     */
    private function isAthleteClubOK($athlete)
    {
        return $this->club->hasAthlete($athlete);
    }

    /**
     * @param $athlete User
     * @return bool
     */
    private function isAthletePlayerLevelOK($athlete)
    {
        $result = true;
        if (!$this->isAttributeBlank('minPlayerLevelID')) {
            $result = $result && $this->minPlayerLevelID >= $athlete->playerLevelID;
        }
        if (!$this->isAttributeBlank('maxPlayerLevelID')) {
            $result = $result && $this->maxPlayerLevelID <= $athlete->playerLevelID;
        }
        return $result;
    }

    /**
     * @param $user User
     * @return bool
     */
    public function canBeUpdatedBy($user) {
        return $user->isCoachAt($this->club) || $user->isSystemAdmin();
    }

    /**
     * @return array
     */
    public function getAgeBandIDs() {
        $result = array();
        foreach ($this->getAgeBands() as $ageBand) {
            $result[] = $ageBand->primaryKey;
        }
        return $result;
    }

    /**
     * @return AgeBand[]
     */
    public function getAgeBands() {
        $result = array();
        foreach (AgeBand::model()->findAll() as $ageBand) {
            if ($this->hasAgeBand($ageBand)) {
                $result[] = $ageBand;
            }
        }
        return $result;
    }

    /**
     * @param $ageBand AgeBand
     * @return bool
     */
    public function hasAgeBand($ageBand) {
        $ageBandMinAge = $ageBand->minAge === null ? 1 : $ageBand->minAge;
        $athleteGroupMinAge = $this->minAge === null ? 1 : $this->minAge;
        $ageBandMaxAge = $ageBand->maxAge === null ? 999 : $ageBand->maxAge;
        $athleteGroupMaxAge = $this->maxAge === null ? 999 : $this->maxAge;
        return $ageBandMinAge <= $athleteGroupMinAge && $ageBandMaxAge >= $athleteGroupMaxAge;
    }

}
