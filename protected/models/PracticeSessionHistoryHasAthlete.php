<?php

/**
 * This is the model class for table "PracticeSessionHistoryHasAthlete".
 *
 * The followings are the available columns in table 'PracticeSessionHistoryHasAthlete':
 * @property integer $practiceSessionHistoryID
 * @property integer $athleteID
 * @property integer $attendanceTypeID
 *
 * The following are available through relations:
 * @property PracticeSessionHistory $practiceSessionHistory
 * @property User $athlete
 * @property PracticeSessionAttendanceType $attendanceType
 *
 */
class PracticeSessionHistoryHasAthlete extends CExtendedActiveRecord {

    public $clubID;
    public $date;

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
            array('practiceSessionHistoryID, athleteID, attendanceTypeID', 'required'),
            array('practiceSessionHistoryID, athleteID, attendanceTypeID', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('practiceSessionHistoryID, athleteID, attendanceTypeID, clubID, date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'attendanceType' => array(self::BELONGS_TO, 'PracticeSessionAttendanceType', 'attendanceTypeID'),
            'athlete'  => array(self::BELONGS_TO, 'User', 'athleteID'),
            'practiceSessionHistory' => array(self::BELONGS_TO, 'PracticeSessionHistory', 'practiceSessionHistoryID'),
            'club' => array(self::BELONGS_TO, 'Club', array('clubID' => 'clubID'), 'through' => 'practiceSessionHistory'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'practiceSessionHistoryID' => 'Practice Session History',
            'athleteID' => 'Athlete',
            'attendanceTypeID' => 'Attendance Type',
            'clubID' => Club::model()->getAttributeLabel('clubID'),
            'date' => 'Ano-Mês',
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

        $criteria->with = array('practiceSessionHistory', 'club', 'attendanceType', 'athlete');

        $criteria->compare('practiceSessionHistoryID', $this->practiceSessionHistoryID);
        $criteria->compare('athleteID', $this->athleteID);
        $criteria->compare('attendanceTypeID', $this->attendanceTypeID);
        $criteria->compare('club.clubID', $this->clubID);
        $criteria->compare('practiceSessionHistory.date', $this->date, true);

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
