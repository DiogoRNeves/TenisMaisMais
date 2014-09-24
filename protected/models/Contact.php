<?php

/**
 * This is the model class for table "Contact".
 *
 * The followings are the available columns in table 'Contact':
 * @property integer $contactID
 * @property string $cellularPhone
 * @property string $workPhone
 * @property string $email
 * @property string $fax
 * @property string $website
 *
 * The followings are the available model relations:
 * @property Club[] $clubs
 * @property User $user
 */
class Contact extends CExtendedActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'Contact';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cellularPhone, workPhone, email, fax, website', 'length', 'max' => 45),
            array('email', 'email'),
            array('email', 'required', 'on' => 'club'),
            array('cellularPhone, workPhone, fax', 'match', 'pattern' => '/^([0-9]{9})$/',
                'message' => '{attribute} não é um número de telefone (tem que ter exatamente 9 dígitos).'),
            array('website', 'url'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('contactID, cellularPhone, workPhone, email, fax, website', 'safe', 'on' => 'search'),
            array('email, cellularPhone', 'unique'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'clubs' => array(self::HAS_MANY, 'Club', 'contactID'),
            'user' => array(self::HAS_ONE, 'User', 'contactID'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'contactID' => 'Contacto',
            'cellularPhone' => 'Telemóvel',
            'workPhone' => 'Contacto Trabalho',
            'email' => 'e-mail',
            'fax' => 'Fax',
            'website' => 'Website',
        );
    }    
    
    /**
     * Defines searchable attributes.
     * @return array the name of the searchable attributes
     */
    public function getSearchableAttributes() {
        return array('cellularPhone','workPhone','email','fax','website');
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Contact the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
