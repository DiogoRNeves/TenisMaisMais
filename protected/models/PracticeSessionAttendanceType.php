<?php

/**
 * This is the model class for table "PracticeSessionAttendanceType".
 *
 * The followings are the available columns in table 'PracticeSessionAttendanceType':
 * @property integer $attendanceTypeID
 * @property string $description
 */
class PracticeSessionAttendanceType extends CExtendedActiveRecord
{
    /**
     * @return PracticeSessionAttendanceType
     */
    public static function getAttended()
    {
        return self::getAttendanceType('presença');
    }

    /**
     * @return PracticeSessionAttendanceType
     */
    public static function getJustifiedUnnatended()
    {
        return self::getAttendanceType('ausência com compensação');
    }

    /**
     * @return PracticeSessionAttendanceType
     */
    public static function getInjustifiedUnnatended()
    {
        return self::getAttendanceType('ausência sem compensação');
    }

    /**
     * @param $string
     * @return PracticeSessionAttendanceType
     */
    private static function getAttendanceType($string)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('description', $string);
        return self::model()->find($criteria);
    }

    public static function getTypesAndLabels()
    {
        $result = array();
        /** @var PracticeSessionAttendanceType $type */
        foreach (self::model()->findAll() as $type) {
            $result[ucfirst($type->description)] = array('label' => ucwords(CHelper::getPlural($type->description)));
        }
        return $result;
    }

    public static function getCompensation()
    {
        return self::getAttendanceType('presença de compensação');
    }

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'PracticeSessionAttendanceType';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('attendanceTypeID', 'required'),
			array('attendanceTypeID', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('attendanceTypeID, description', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'practiceSessionHistoryHasAthlete' => array(SELF::HAS_MANY, 'PracticeSessionHistoryHasAthlete', 'attendanceTypeID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'attendanceTypeID' => 'Tipo de assiduidade',
			'description' => 'Tipo de assiduidade',
            'listDataTextField' => 'Tipo de assiduidade',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('attendanceTypeID',$this->attendanceTypeID);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PracticeSessionAttendanceType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getListDataTextField() {
        return ucfirst($this->description);
    }

    /**
     * @param PracticeSessionAttendanceType $attendanceType
     * @return bool
     */
    public function isAttendanceType($attendanceType)
    {
        return $this->primaryKey === $attendanceType->primaryKey;
    }
}
