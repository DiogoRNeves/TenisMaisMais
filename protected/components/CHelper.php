<?php

/**
 * @property User $user the user
 */
class CHelper extends CApplicationComponent {

    const DEFAULT_TIME_FORMAT = 'H:i';

    private static $_select2HtmlOptions = array(
        'placeholder' => 'Please Select a Value',
        'select2Options' => array('allowClear' => true)
    );

    /**
     * Checks if the arrays have at least one value that is the same. If one og the arrays has no elements
     * returns true.
     * @param array $array1
     * @param array $array2
     * @return boolean false if all elements in both arrays are different, true otherwise
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
     * @param CExtendedActiveRecord[] $models The array of objects
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
     * @param CModel $model the model.
     * @param string $attribute the attribute name.
     * @param string $data the data. 
     * @param bool $multiSelect whether this is a multiSelect. Defaults to false.
     * @param string $hint Hint to be rendered.
     * @param type $hintOptions The hint htmlOptions to be renderer into the wrapping tag
     * @param bool $enabled Wether this selection is enabled or not
     * @param array $defaultVal array indexed by id and text
     * @return string the HTML to be echoed.
     */
    public static function select2Row($form, $model, $attribute, $data, $multiSelect = false, 
            $hint = null, $hintOptions = null, $enabled = true, $defaultVal = null) {
        $widgetOptions = array(
            'data' => $data,
            'options' => array(
                'placeholder' => 'Please Select a Value',
                'allowClear' => true,
            ),
            //'htmlOptions' => array('disabled' => !$enabled ? 'true' : 'false'),
        );
        if (!$enabled) {
            $widgetOptions['htmlOptions'] = array('disabled' => 'true');
        }
        if ($defaultVal !== null) {
            $widgetOptions['val'] = $defaultVal;
        }
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
            $rowOptions['hintOptions'] = $hintOptions;
        }
        return $form->select2Row($model, $attribute, $widgetOptions, $rowOptions);
    }

    public static function echoSubmitButton($form, $model) {
        $form->widget(
                'bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => $model->isNewRecord ? 'Criar' : 'Gravar',
        ));
    }



    public static function timepickerRow($form, $model, $attribute) {
        return $form->timepickerRow(
                        $model, $attribute, array(
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

    public static function timeToString($time, $format = self::DEFAULT_TIME_FORMAT) {
        $time = new DateTime($time);
        return $time->format($format);
    }

    /**
     * 
     * @param CModel[] $data the array containing the models
     * @param array $filter array indexed by param array('name' => 'diogo')
     * @return User[] the related Coaches
     */
    public static function searchArray($data, $filter) {
        if ($data == null) {
            return array();
        } else if ($filter == null) {
            return $data;
        }
        $result = array();
        foreach ($data as $model) {
            foreach ($filter as $property => $searchValue) {
                if (self::stringContains($model->$property, $searchValue, true)) {
                    $result[] = $model;
                }
            }
        }
        return $result;
    }

    /**
     * 
     * @param string $string
     * @param string $searchValue
     * @return bool whether $searchValue is in $string or not
     */
    public static function stringContains($string, $searchValue, $splitWords = false, $caseSensitive = false) {
        $searchValue = trim($searchValue);
        if (!$caseSensitive) {
            $string = strtolower($string);
            $searchValue = strtolower($searchValue);
        }
        if ($splitWords) {
            $searchValue = explode(" ", $searchValue);
        } else {
            $searchValue = array($searchValue);
        }
        $result = true;
        foreach ($searchValue as $word) {
            $result = $result && strpos($string, $word) !== false;
        }
        return $result;
    }

    public static function isNullOrEmptyString($variables)
    {
        $variables = CHelper::toArray($variables);
        foreach ($variables as $variable) {
            if (isset($variable) && trim($variable)!=='') {return false;}
        }
        return true;
    }

    private static function toArray($variables)
    {
        if (!is_array($variables)) {
            return array($variables);
        }
        return $variables;
    }

    /**
     * @param $date string
     * @return int friday is 5
     */
    public static function getDayOfWeek($date)
    {
        $date = new DateTime($date);
        return $date->format('w');
    }

    public static function mergeArrays($arrays, $unique = true)
    {
        $result = array();
        foreach ($arrays as $array) {
            if (is_array($array)) {
                $result = array_merge($result, $array);
            }
        }
        return $unique ? array_unique($result) : $result;
    }

    public static function inArray($needle, $haystack)
    {
        if (!is_array($haystack)) { return false; }
        return in_array($needle, $haystack);
    }

    public static function timeIntervalString($startTime, $endTime, $format = self::DEFAULT_TIME_FORMAT) {
        return self::timeToString($startTime, $format) . " - " . self::timeToString($endTime, $format);
    }

    public static function getPlural($string)
    {
        $words = explode(" ", $string);
        $result = $words[0] . "s ";
        for ($i = 1; $i < count($words); $i++) {
            $result .= $words[$i] . " ";
        }
        return trim($result);
    }

}
