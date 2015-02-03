<?php

/**
 * This is the model class for table "TournamentVariation".
 *
 * The followings are the available columns in table 'TournamentVariation':
 * @property integer $tournamentVariationID
 * @property string $abbreviation
 * @property string $text
 * @property integer $singles
 * @property integer $allowMale
 * @property integer $allowFemale
 */
class TournamentVariation extends CExtendedActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'TournamentVariation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('abbreviation, text, singles, allowMale, allowFemale', 'required'),
			array('singles, allowMale, allowFemale', 'numerical', 'integerOnly'=>true),
			array('abbreviation', 'length', 'max'=>3),
			array('text', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tournamentVariationID, abbreviation, text, singles, allowMale, allowFemale', 'safe', 'on'=>'search'),
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
			'tournamentVariationID' => 'Tournament Variation',
			'abbreviation' => 'Abbreviation',
			'text' => 'Text',
			'singles' => 'Singles',
			'allowMale' => 'Allow Male',
			'allowFemale' => 'Allow Female',
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

		$criteria->compare('tournamentVariationID',$this->tournamentVariationID);
		$criteria->compare('abbreviation',$this->abbreviation,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('singles',$this->singles);
		$criteria->compare('allowMale',$this->allowMale);
		$criteria->compare('allowFemale',$this->allowFemale);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TournamentVariation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
