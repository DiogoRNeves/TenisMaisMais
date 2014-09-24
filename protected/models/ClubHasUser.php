<?php

/**
 * This is the model class for table "ClubHasUser".
 *
 * The followings are the available columns in table 'ClubHasUser':
 * @property integer $clubHasUserID
 * @property integer $clubID
 * @property integer $userID
 * @property integer $userTypeID
 * @property string $startDate
 * @property string $endDate
 *
 * The followings are the available model relations:
 * @property Club $club
 * @property User $user
 * @property UserType $userType
 */
class ClubHasUser extends CExtendedActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'ClubHasUser';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('clubID, userID, userTypeID, startDate', 'required'),
            array('clubID, userID, userTypeID', 'numerical', 'integerOnly' => true),
            array('endDate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('clubHasUserID, clubID, userID, userTypeID, startDate, endDate', 'safe', 'on' => 'search'),
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
            'user' => array(self::BELONGS_TO, 'User', 'userID'),
            'userType' => array(self::BELONGS_TO, 'UserType', 'userTypeID'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'clubHasUserID' => 'Club Has User',
            'clubID' => 'Club',
            'userID' => 'User',
            'userTypeID' => 'User Type',
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
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

        $criteria->compare('clubHasUserID', $this->clubHasUserID);
        $criteria->compare('clubID', $this->clubID);
        $criteria->compare('userID', $this->userID);
        $criteria->compare('userTypeID', $this->userTypeID);
        $criteria->compare('startDate', $this->startDate, true);
        $criteria->compare('endDate', $this->endDate, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ClubHasUser the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
