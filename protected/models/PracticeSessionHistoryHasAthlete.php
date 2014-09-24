<?php

/**
 * This is the model class for table "PracticeSessionHistoryHasAthlete".
 *
 * The followings are the available columns in table 'PracticeSessionHistoryHasAthlete':
 * @property integer $practiceSessionHistoryID
 * @property integer $athleteID
 * @property integer $attendanceType
 */
class PracticeSessionHistoryHasAthlete extends CExtendedActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'PracticeSessionHistoryHasAthlete';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('practiceSessionHistoryID, athleteID, attendanceType', 'required'),
            array('practiceSessionHistoryID, athleteID, attendanceType', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('practiceSessionHistoryID, athleteID, attendanceType', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'practiceSessionHistoryID' => 'Practice Session History',
            'athleteID' => 'Athlete',
            'attendanceType' => 'Attendance Type',
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

        $criteria->compare('practiceSessionHistoryID', $this->practiceSessionHistoryID);
        $criteria->compare('athleteID', $this->athleteID);
        $criteria->compare('attendanceType', $this->attendanceType);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PracticeSessionHistoryHasAthlete the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
