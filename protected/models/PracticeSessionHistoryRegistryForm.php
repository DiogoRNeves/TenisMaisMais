<?php

/**
 * PracticeSessionHistoryRegistryForm class.
 * PracticeSessionHistoryRegistryForm is the data structure for keeping
 * the information about the attendance registry. It is used by the 'register' action of 'PracticeSessionHistory'.
 */
class PracticeSessionHistoryRegistryForm extends CFormModel {

    public $athletesAttended;
    public $athletesJustifiedUnnatendance;
    public $athletesInjustifiedUnnatendance;
    public $cancelledDueToRain;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // cancelledDueToRain needs to be a boolean
            array('cancelledDueToRain', 'boolean'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'athletesAttended' => 'Presenças',
            'athletesJustifiedUnnatendance' => 'Ausências Justificadas (compensáveis)',
            'athletesInjustifiedUnnatendance' => 'Ausências Injustificadas (não compensáveis)',
            'cancelledDueToRain' => 'Treino cancelado devido a chuva',
        );
    }


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
