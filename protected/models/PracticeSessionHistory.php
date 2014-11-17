<?php

/**
 * This is the model class for table "PracticeSessionHistory".
 *
 * The followings are the available columns in table 'PracticeSessionHistory':
 * @property integer $practiceSessionHistoryID
 * @property string $startTime
 * @property string $endTime
 * @property string $date
 * @property integer $coachID
 * @property integer $clubID
 *
 * The followings are the available model relations:
 * @property Club $club
 * @property User $coach
 * @property User[] $athletes
 * @property PracticeSessionHistoryHasAthlete[] $practiceSessionHistoryHasAthlete
 */
class PracticeSessionHistory extends CExtendedActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'PracticeSessionHistory';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('practiceSessionHistoryID, startTime, endTime, date, coachID, clubID', 'required'),
            array('practiceSessionHistoryID, coachID, clubID', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('practiceSessionHistoryID, startTime, endTime, date, coachID, clubID', 'safe', 'on' => 'search'),
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
            'athletes' => array(self::MANY_MANY, 'User', 'PracticeSessionHistoryHasAthlete(practiceSessionHistoryID, athleteID)'),
            'practiceSessionHistoryHasAthlete' => array(self::HAS_MANY, 'PracticeSessionHistoryHasAthlete', 'practiceSessionHistoryID'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'practiceSessionHistoryID' => 'ID#',
            'startTime' => PracticeSession::model()->getAttributeLabel('startTime'),
            'endTime' => PracticeSession::model()->getAttributeLabel('endTime'),
            'date' => 'Data',
            'coachID' => PracticeSession::model()->getAttributeLabel('coachID'),
            'clubID' => PracticeSession::model()->getAttributeLabel('clubID'),
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
        $criteria->compare('startTime', $this->startTime, true);
        $criteria->compare('endTime', $this->endTime, true);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('coachID', $this->coachID);
        $criteria->compare('clubID', $this->clubID);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PracticeSessionHistory the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return User[][] the athletes indexed by attendance type
     */
    public function getAthletesAttendanceType()
    {
        $result = array();
        /** @var PracticeSessionAttendanceType $attendanceType */
        //initialize all indexes with empty array
        foreach (PracticeSessionAttendanceType::model()->findAll() as $attendanceType) {
            $result[$attendanceType->primaryKey()] = array();
        }
        //distribute athletes through the indexes (attendance types)
        foreach ($this->practiceSessionHistoryHasAthlete as $practiceSessionHistoryHasAthlete) {
            $result[$practiceSessionHistoryHasAthlete->attendanceTypeID][] = $practiceSessionHistoryHasAthlete->athlete;
        }
        return $result;
    }

}
