<?php

/**
 * This is the model class for table "FederationTournamentHasAgeBand".
 *
 * The followings are the available columns in table 'FederationTournamentHasAgeBand':
 * @property integer $federationTournamentID
 * @property integer $ageBandID
 * @property integer $tournamentVariationID
 */
class FederationTournamentHasAgeBand extends CExtendedActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'FederationTournamentHasAgeBand';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('federationTournamentID, ageBandID, tournamentVariationID', 'required'),
			array('federationTournamentID, ageBandID, tournamentVariationID', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('federationTournamentID, ageBandID, tournamentVariationID', 'safe', 'on'=>'search'),
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
			'federationTournament' => array(self::HAS_ONE, 'FederationTournament', 'federationTournamentID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'federationTournamentID' => 'Federation Tournament',
			'ageBandID' => 'Age Band',
			'tournamentVariationID' => 'Tournament Variation',
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

		$criteria->compare('federationTournamentID',$this->federationTournamentID);
		$criteria->compare('ageBandID',$this->ageBandID);
		$criteria->compare('tournamentVariationID',$this->tournamentVariationID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FederationTournamentHasAgeBand the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
