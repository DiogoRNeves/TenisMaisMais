<?php

/**
 * This is the model class for table "Home".
 *
 * The followings are the available columns in table 'Home':
 * @property integer $homeID
 * @property string $phoneNumber
 * @property string $address
 * @property string $postCode
 * @property string $city
 *
 * The followings are the available model relations:
 * @property Club[] $clubs
 * @property User[] $users
 */
class Home extends CExtendedActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'Home';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('city', 'required'),
            array('homeID', 'numerical', 'integerOnly' => true),
            array('phoneNumber, address, postCode, city', 'length', 'max' => 45),
            array('phoneNumber', 'match', 'pattern' => '/^([0-9]{9})$/',
                'message' => '{attribute} is not a valid phone number (it must be exactly 9 digits long).'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('homeID, phoneNumber, address, postCode, city', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'clubs' => array(self::HAS_MANY, 'Club', 'homeID'),
            'users' => array(self::HAS_MANY, 'User', 'homeID'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'homeID' => 'Casa',
            'phoneNumber' => 'Telefone',
            'address' => 'Morada',
            'postCode' => 'Código Postal',
            'city' => 'Cidade',
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

        $criteria->compare('homeID', $this->homeID);
        $criteria->compare('phoneNumber', $this->phoneNumber, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('postCode', $this->postCode, true);
        $criteria->compare('city', $this->city, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Home the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
