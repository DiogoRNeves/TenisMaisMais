<?php

/**
 * This is the model class for table "LandPhonePrefixes".
 *
 * The followings are the available columns in table 'LandPhonePrefixes':
 * @property integer $prefix
 * @property string $zone
 */
class LandPhonePrefixes extends CExtendedActiveRecord
{
    /**
     * @param $prefix
     * @return LandPhonePrefixes
     */
    public static function searchPrefix($prefix) {
        return LandPhonePrefixes::model()->findByAttributes(array(
            'prefix' => $prefix,
        ));
    }

    public static function getZoneString($testNumber) {
        $testPrefix = substr($testNumber, 0, 3);
        if ($testPrefix > 400) { return null; } //mobile phone
        /** @var LandPhonePrefixes $result */
        $result = LandPhonePrefixes::searchPrefix($testPrefix);
        $result = $result === null ? LandPhonePrefixes::searchPrefix(substr($testPrefix, 0, 2)) : $result;
        if ($result === null) {
            throw new CException('Error finding area code for number: ' . $testNumber);
        }
        return $result === null ? null : $result->zone;
    }

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'LandPhonePrefixes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prefix, zone', 'required'),
			array('prefix', 'numerical', 'integerOnly'=>true),
			array('zone', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('prefix, zone', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'prefix' => 'Prefix',
			'zone' => 'Zone',
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

		$criteria->compare('prefix',$this->prefix);
		$criteria->compare('zone',$this->zone,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LandPhonePrefixes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
