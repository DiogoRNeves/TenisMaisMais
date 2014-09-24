<?php

/**
 * This is the model class for table "AgeBand".
 *
 * The followings are the available columns in table 'AgeBand':
 * @property integer $ageBandID
 * @property string $name
 * @property string $maxAge
 * @property string $minAge
 *
 * The followings are the available model relations:
 * @property FederationTournament[] $federationTournaments
 */
class AgeBand extends CExtendedActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'AgeBand';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('ageBandID, name, maxAge, minAge', 'required'),
            array('ageBandID', 'numerical', 'integerOnly' => true),
            array('name, maxAge, minAge', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('ageBandID, name, maxAge, minAge', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'federationTournaments' => array(self::MANY_MANY, 'FederationTournament', 'FederationTournamentHasAgeBand(ageBandID, federationTournamentID)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'ageBandID' => 'Age Band',
            'name' => 'Name',
            'maxAge' => 'Max Age',
            'minAge' => 'Min Age',
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

        $criteria->compare('ageBandID', $this->ageBandID);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('maxAge', $this->maxAge, true);
        $criteria->compare('minAge', $this->minAge, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AgeBand the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
