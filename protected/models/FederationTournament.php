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
 *
 * The followings are the available model relations:
 * @property AthleteGroup[] $athleteGroups
 * @property CompetitiveResultHistory[] $competitiveResultHistories
 * @property FederationClub $federationClub
 * @property AgeBand[] $ageBands
 * @property TournamentType[] $tournamentTypes
 */
class FederationTournament extends CExtendedActiveRecord {

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
            array('federationTournamentID, level, mainDrawStartDate, mainDrawEndDate, name, city, surface, federationClubID', 'required'),
            array('federationTournamentID, meals, prizeMoney, federationClubID', 'numerical', 'integerOnly' => true),
            array('level', 'length', 'max' => 2),
            array('city, name', 'length', 'max' => 150),
            array('surface, accommodation', 'length', 'max' => 45),
            array('qualyStartDate, qualyEndDate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('federationTournamentID, level, qualyStartDate, qualyEndDate, mainDrawStartDate, mainDrawEndDate, name,
            city, surface, accommodation, meals, prizeMoney, federationClubID, federationClub', 'safe', 'on' => 'search'),
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
            'tournamentTypes' => array(self::HAS_MANY, 'TournamentType', 'federationTournamentID'),
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
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->distinct = true;
        $criteria->together = true;
        $criteria->with = array('federationClub');

        $criteria->compare('federationTournamentID', $this->federationTournamentID);
        $criteria->compare('level', $this->level, true);
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
        if ($this->federationClub !== null) {
            $criteria->compare('federationClub.name', $this->federationClub->name);
        }

        $dataProvider = new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));

        $dataProvider->pagination->pageSize = 5;
        $dataProvider->sort->defaultOrder = array("mainDrawStartDate" => CSort::SORT_ASC);

        return $dataProvider;
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
        foreach ($this->ageBands as $ageBand) {
            $result[] = $ageBand->name;
        }

        return implode("; ",$result);
    }

    public function isInAthleteGroup($athleteGroupID) {
        foreach ($this->athleteGroups as $athleteGroup) {
            if ($athleteGroup->primaryKey == $athleteGroupID) {
                return true;
            }
        }
        return false;
    }
}
