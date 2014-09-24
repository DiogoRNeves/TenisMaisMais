<?php

/**
 * This is the model class for table "Sponsor".
 *
 * The followings are the available columns in table 'Sponsor':
 * @property integer $sponsorID
 * @property integer $athleteID
 * @property string $startDate
 * @property integer $relationshipType
 * @property string $endDate
 *
 * The followings are the available model relations:
 * @property SponsorAthleteRelationshipType relatedRelationshipType
 * @property User $sponsor
 * @property User $athlete
 */
class Sponsor extends CExtendedActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'Sponsor';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sponsorID, athleteID, startDate, relationshipType', 'required'),
            array('sponsorID, athleteID, relationshipType', 'numerical', 'integerOnly' => true),
            array('endDate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('sponsorID, athleteID, startDate, relationshipType, endDate', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'relatedRelationshipType' => array(self::BELONGS_TO, 'SponsorAthleteRelationshipType', 'relationshipType'),
            'sponsor' => array(self::BELONGS_TO, 'User', 'sponsorID'),
            'athlete' => array(self::BELONGS_TO, 'User', 'athleteID'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'sponsorID' => 'Sponsor',
            'athleteID' => 'Athlete',
            'startDate' => 'Start Date',
            'relationshipType' => 'Relationship Type',
            'endDate' => 'End Date',
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

        $criteria->compare('sponsorID', $this->sponsorID);
        $criteria->compare('athleteID', $this->athleteID);
        $criteria->compare('startDate', $this->startDate, true);
        $criteria->compare('relationshipType', $this->relationshipType);
        $criteria->compare('endDate', $this->endDate, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the a list to be fed to a dropdown
     * 
     * @return array the list data that can be used in {@link dropDownList}, {@link listBox}, etc.
     */
    public function getRelationsListDataArray() {
        $models = SponsorAthleteRelationshipType::model()->findAll();
        $list = CHtml::listData($models, 'relationshipTypeId', 'label');
        if ($this->isNewRecord) {
            $list[NULL] = 'please select value';
        }
        return $list;
    }
    
    /**
     * Returns an array with all attributes except the ones passed as an argument. Is case sensitive.
     * @param array $attr Attributes that should not be returned
     * @return array the desired atribute names
     */
    public function getAttributeNamesExcept($attr) {
        $result = array();
        foreach ($this->attributeNames() as $attributeName) {
            if (!in_array($attributeName, $attr)) {
                array_push($result, $attributeName);
            }
        }
        return $result;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Sponsor the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
