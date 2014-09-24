<?php

/**
 * This is the model class for table "MainCoach".
 *
 * The followings are the available columns in table 'MainCoach':
 * @property integer $coachID
 * @property integer $athleteID
 * @property string $startDate
 * @property string $endDate
 * @property integer $clubID
 *
 * The followings are the available model relations:
 * @property Club $club
 * @property User $coach
 * @property User $athlete
 */
class MainCoach extends CExtendedActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'MainCoach';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('coachID, athleteID, startDate, clubID', 'required'),
            array('coachID, athleteID, clubID', 'numerical', 'integerOnly' => true),
            array('endDate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('coachID, athleteID, startDate, endDate, clubID', 'safe', 'on' => 'search'),
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
            'coach' => array(self::BELONGS_TO, 'User', 'coachID'),
            'athlete' => array(self::BELONGS_TO, 'User', 'athleteID'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'coachID' => 'Coach',
            'athleteID' => 'Athlete',
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
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

        $criteria->compare('coachID', $this->coachID);
        $criteria->compare('athleteID', $this->athleteID);
        $criteria->compare('startDate', $this->startDate, true);
        $criteria->compare('endDate', $this->endDate, true);
        $criteria->compare('clubID', $this->clubID);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MainCoach the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
