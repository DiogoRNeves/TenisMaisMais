<?php

/**
 * This is the model class for table "CompetitiveResultHistory".
 *
 * The followings are the available columns in table 'CompetitiveResultHistory':
 * @property integer $competitiveResultHistoryID
 * @property integer $winnerUserID
 * @property integer $loserUserID
 * @property string $score
 * @property integer $federationTournamentID
 *
 * The followings are the available model relations:
 * @property FederationTournament $federationTournament
 * @property User $loserUser
 * @property User $winnerUser
 */
class CompetitiveResultHistory extends CExtendedActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'CompetitiveResultHistory';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('competitiveResultHistoryID, winnerUserID, loserUserID, score, federationTournamentID', 'required'),
            array('competitiveResultHistoryID, winnerUserID, loserUserID, federationTournamentID', 'numerical', 'integerOnly' => true),
            array('score', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('competitiveResultHistoryID, winnerUserID, loserUserID, score, federationTournamentID', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'federationTournament' => array(self::BELONGS_TO, 'FederationTournament', 'federationTournamentID'),
            'loserUser' => array(self::BELONGS_TO, 'User', 'loserUserID'),
            'winnerUser' => array(self::BELONGS_TO, 'User', 'winnerUserID'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'competitiveResultHistoryID' => 'Competitive Result History',
            'winnerUserID' => 'Winner User',
            'loserUserID' => 'Loser User',
            'score' => 'Score',
            'federationTournamentID' => 'Federation Tournament',
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

        $criteria->compare('competitiveResultHistoryID', $this->competitiveResultHistoryID);
        $criteria->compare('winnerUserID', $this->winnerUserID);
        $criteria->compare('loserUserID', $this->loserUserID);
        $criteria->compare('score', $this->score, true);
        $criteria->compare('federationTournamentID', $this->federationTournamentID);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CompetitiveResultHistory the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
