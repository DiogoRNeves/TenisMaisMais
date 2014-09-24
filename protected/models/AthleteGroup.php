<?php

/**
 * This is the model class for table "AthleteGroup".
 *
 * The followings are the available columns in table 'AthleteGroup':
 * @property integer $athleteGroupID
 * @property integer $minAge
 * @property integer $maxAge
 * @property string $minPlayerLevel
 * @property string $maxPlayerLevel
 * @property integer $clubID
 *
 * The followings are the available model relations:
 * @property Club $club
 * @property FederationTournament[] $federationTournaments
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
            array('athleteGroupID, clubID', 'required'),
            array('athleteGroupID, minAge, maxAge, clubID', 'numerical', 'integerOnly' => true),
            array('minPlayerLevel, maxPlayerLevel', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('athleteGroupID, minAge, maxAge, minPlayerLevel, maxPlayerLevel, clubID', 'safe', 'on' => 'search'),
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
            'federationTournaments' => array(self::MANY_MANY, 'FederationTournament', 'CompetitivePlan(athleteGroupID, federationTournamentID)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'athleteGroupID' => 'Athlete Group',
            'minAge' => 'Min Age',
            'maxAge' => 'Max Age',
            'minPlayerLevel' => 'Min Player Level',
            'maxPlayerLevel' => 'Max Player Level',
            'clubID' => 'Club',
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

        $criteria->compare('athleteGroupID', $this->athleteGroupID);
        $criteria->compare('minAge', $this->minAge);
        $criteria->compare('maxAge', $this->maxAge);
        $criteria->compare('minPlayerLevel', $this->minPlayerLevel, true);
        $criteria->compare('maxPlayerLevel', $this->maxPlayerLevel, true);
        $criteria->compare('clubID', $this->clubID);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
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

}
