<?php

/**
 * This is the model class for table "CoachLevel".
 *
 * The followings are the available columns in table 'CoachLevel':
 * @property integer $coachLevelId
 * @property string $description
 * @property string $group
 *
 * The followings are the available model relations:
 * @property User[] $users
 */
class CoachLevel extends CExtendedActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'CoachLevel';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('coachLevelId', 'required'),
            array('coachLevelId', 'numerical', 'integerOnly' => true),
            array('description, group', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('coachLevelId, description, group', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'users' => array(self::HAS_MANY, 'User', 'coachLevelId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'coachLevelId' => 'Nível do Treinador',
            'description' => 'Descrição',
            'group' => 'Grupo',
            'coachLevel' => 'Nível do Treinador',
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

        $criteria->compare('coachLevelId', $this->coachLevelId);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('group', $this->group, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Defines the group field
     * @return string the attribute name to get Text Filed data from
     */
    public function getListDataGroupField() {
        return 'group';
    }

    /**
     * Defines the text field
     * @return string the attribute name to get Text Filed data from
     */
    public function getListDataTextField() {
        return 'description';
    }
    
    public function getCoachLevel() {
        return $this->description;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CoachLevel the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
