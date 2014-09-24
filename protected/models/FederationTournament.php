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
            array('name', 'length', 'max' => 150),
            array('city, surface, accommodation', 'length', 'max' => 45),
            array('qualyStartDate, qualyEndDate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('federationTournamentID, level, qualyStartDate, qualyEndDate, mainDrawStartDate, mainDrawEndDate, name, city, surface, accommodation, meals, prizeMoney, federationClubID', 'safe', 'on' => 'search'),
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
            'federationTournamentID' => 'Federation Tournament',
            'level' => 'Level',
            'qualyStartDate' => 'Qualy Start Date',
            'qualyEndDate' => 'Qualy End Date',
            'mainDrawStartDate' => 'Main Draw Start Date',
            'mainDrawEndDate' => 'Main Draw End Date',
            'name' => 'Name',
            'city' => 'City',
            'surface' => 'Surface',
            'accommodation' => 'Accommodation',
            'meals' => 'Meals',
            'prizeMoney' => 'Prize Money',
            'federationClubID' => 'Federation Club',
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
        $criteria->compare('federationClubID', $this->federationClubID);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
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

}
