<?php

/**
 * This is the model class for table "PlayerLevel".
 *
 * The followings are the available columns in table 'PlayerLevel':
 * @property integer $playerLevelID
 * @property string $generalReference
 * @property string $levelWithinReference
 *
 * The followings are the available model relations:
 * @property User[] $users
 */
class PlayerLevel extends CExtendedActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'PlayerLevel';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('playerLevelID, generalReference, levelWithinReference', 'required'),
            array('playerLevelID', 'numerical', 'integerOnly' => true),
            array('generalReference, levelWithinReference', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('playerLevelID, generalReference, levelWithinReference', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'users' => array(self::HAS_MANY, 'User', 'playerLevelId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'playerLevelID' => 'Nível do Jogador',
            'generalReference' => 'Nível',
            'levelWithinReference' => 'Sub-nível',
            'playerLevel' => 'Nível do Jogador',
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

        $criteria->compare('playerLevelID', $this->playerLevelID);
        $criteria->compare('generalReference', $this->generalReference, true);
        $criteria->compare('levelWithinReference', $this->levelWithinReference, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    /**
     * Defines the group field
     * @return string the attribute name to get Text Filed data from
     */
    public function getListDataGroupField() {
        return 'generalReference';
    }
    
    /**
     * Defines the group field
     * @return string the attribute name to get Text Filed data from
     */
    public function getListDataTextField() {
        return 'compiledListText';
    }
    
    /**
     * Method to compile the text that should come in a dropdownlist. 
     * Don't call this method outside the class!
     * @return string the text that should come in a dropdownlist.
     */
    public function getCompiledListText() {
        return $this === null ? null : $this->generalReference . " - " . $this->levelWithinReference;
    }
    
    public function getPlayerLevel() {
        return $this->getCompiledListText();
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PlayerLevel the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
