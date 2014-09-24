<?php

/**
 * @property User $user the user
 */
class CHelper extends CApplicationComponent {

    private static $_select2HtmlOptions = array(
        'placeholder' => 'Please Select a Value',
        'select2Options' => array('allowClear' => true)
    );

    /**
     * Checks if the arrays have at least one value that is the same. If one og the arrays has no elements
     * returns true.
     * @param array $array1
     * @param array $array2
     * @return boolean false if all elements in both arrays are diferent, true otherwise
     */
    public static function arraysIntersect($array1, $array2) {
        $countA1 = count($array1);
        $countA2 = count($array2);
        $countIntersect = count(array_intersect($array1, $array2));
        $result = $countA1 == 0 || $countA2 == 0 || $countIntersect != 0;
        return $result;
    }

    /**
     * Wrapper for Select2 extension activeDropDownList.
     * @param CActiveRecord $model
     * @param string $attribute
     * @param array $data
     */
    public static function activeDropDownList($model, $attribute, $data) {
        echo Select2::activeDropDownList($model, $attribute, $data, self::$_select2HtmlOptions);
    }

    /**
     * Wrapper for Select2 extension activeDropDownList.
     * @param CActiveRecord $model
     * @param string $attribute
     * @param array $data
     */
    public static function activeMultiSelect($model, $attribute, $data) {
        echo Select2::activeMultiSelect($model, $attribute, $data, self::$_select2HtmlOptions);
    }

    /**
     * Returns an array containing only an attribute of the objects in another array.
     * @param CExtendedActiveRecord $models The array of objects
     * @param string $attributeName the attribute name
     * @return array an array containing only the specified attribute for all the elements of the given array
     */
    public static function getArrayOfAttribute($models, $attributeName, $linkToObject = false) {
        $call = ($linkToObject ? "getLink('$attributeName')" : $attributeName);
        $function = create_function('$o', 'return $o->' . $call . ';');
        return array_map($function, $models);
    }

    public static function getObjectsLinks($models, $attributeName, $label = '', $linkToObject = true) {
        $models = is_array($models) ? $models : array($models);
        return array(
            'name' => $label = '' ? $attributeName : $label,
            'type' => 'raw',
            'value' => self::getStringOfObjectLinks($models, $attributeName),
        );
    }
    
    public static function getStringOfObjectLinks($models, $attributeName, $linkToObject = true) {
        return implode(", ", self::getArrayOfAttribute($models, $attributeName, $linkToObject));
    }

    /**
     * select2Row wrapper for Booster TbActiveForm.
     * @param TbActiveForm $form the form where this is being rendered.
     * @param CExtendedActiveRecord $model the model. 
     * @param string $attribute the attribute name.
     * @param string $data the data. 
     * @param bool $multiSelect whether this is a multiSelect. Defaults to false.
     * @param string $hint Hint to be rendered.
     * @param type $hintOptions The hint htmlOptions to be renderer into the wrapping tag
     * @return string the HTML to be echoed.
     */
    public static function select2Row($form, $model, $attribute, $data, $multiSelect = false, $hint = null, $hintOptions = null, $enabled = false) {
        $widgetOptions = array(
            'data' => $data,
            'options' => array(
                'placeholder' => 'Please Select a Value',
                'allowClear' => true,
            ),
            'htmlOptions' => array('select2-enabled' => $enabled ? 'true' : 'false'),
        );
        if ($multiSelect) {
            $widgetOptions['htmlOptions'] = array(
                'multiple' => 'multiple',
            );
        }
        $rowOptions = array();
        if ($hint !== null) {
            $rowOptions['hint'] = $hint;
        }
        if ($hintOptions !== null) {
            $rowOptions['hint'] = $hintOptions;
        }
        return $form->select2Row($model, $attribute, $widgetOptions, $rowOptions);
    }

    public static function echoSubmitButton($form, $model) {
        $form->widget(
                'bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => $model->isNewRecord ? 'Create' : 'Save',
        ));
    }
    
    public static function timepickerRow($form, $model, $attribute) {
        return $form->timepickerRow(
            $model,
            $attribute,
            array(
                'htmlOptions' => array(
                    'class' => 'input-small',
                ),
                'options' => array(
                    'defaultTime' => false,
                    'showMeridian' => false,
                    'showInputs' => false,
                ),
                'noAppend' => true,
            )
        );
    }
    
    public static function timeToString($time) {
        return substr($time, 0, 5);
    }

}