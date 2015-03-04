<?php

/**
 * This is the model class for table "LocalCoordinateCache".
 *
 * The followings are the available columns in table 'LocalCoordinateCache':
 * @property integer $localCoordinateCacheID
 * @property string $coordinatesSearchString
 * @property string $lat
 * @property string $lng
 */
class LocalCoordinateCache extends CExtendedActiveRecord
{
    public static function geocode($geocodingString) {
        $instance = new LocalCoordinateCache;
        $instance->coordinatesSearchString = $geocodingString;
        list($instance->lat, $instance->lng) = CHelper::geocode($geocodingString);
        return $instance->save() ? $instance : null;
    }

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'LocalCoordinateCache';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('coordinatesSearchString, lat, lng', 'required'),
			array('coordinatesSearchString', 'length', 'max'=>255),
			array('lat', 'numerical', 'max'=>180, 'min' => -180),
            array('lat', 'numerical', 'max'=>90, 'min' => -90),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('localCoordinateCacheID, coordinatesSearchString, lat, lng', 'safe', 'on'=>'search'),
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
			'localCoordinateCacheID' => 'Local Coordinate Cache',
			'coordinatesSearchString' => 'Coordinates Search String',
			'lat' => 'Lat',
			'lng' => 'Lng',
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

		$criteria->compare('localCoordinateCacheID',$this->localCoordinateCacheID);
		$criteria->compare('coordinatesSearchString',$this->coordinatesSearchString,true);
		$criteria->compare('lat',$this->lat,true);
		$criteria->compare('lng',$this->lng,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LocalCoordinateCache the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @param $destination LocalCoordinateCache
     * @return float
     */
    public function calculateDistanceTo($destination) {
        $dLat = deg2rad($this->lat - $destination->lat);
        $dLon = deg2rad($this->lng - $destination->lng);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($destination->lat)) * cos(deg2rad($this->lat)) * sin($dLon / 2) * sin($dLon / 2);
        //var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        $c = 2 * asin(sqrt($a));
        $R = 6351; //earth radius in Km
        return $R * $c;
    }
}
