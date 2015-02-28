<?php

/**
 * @property User $user the user
 */
class CHelper extends CApplicationComponent {

    const DEFAULT_TIME_FORMAT = 'H:i';
    const DEFAULT_DATE_FORMAT = 'Y-m-d';

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
     * Returns an array containing only an attribute of the objects in another array.
     * @param CExtendedActiveRecord[] $models The array of objects
     * @param string $attributeName the attribute name
     * @param bool $linkToObject
     * @return array an array containing only the specified attribute for all the elements of the given array
     */
    public static function getArrayOfAttribute($models, $attributeName, $linkToObject = false) {
        $call = ($linkToObject ? "getLink('$attributeName')" : $attributeName);
        $function = create_function('$o', 'return $o->' . $call . ';');
        return array_map($function, $models);
    }

    public static function getObjectsLinks($models, $attributeName, $label = '') {
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
     * @param array $hintOptions The hint htmlOptions to be renderer into the wrapping tag
     * @param bool $enabled Whether this selection is enabled or not
     * @param array $defaultVal array indexed by id and text
     * @return string the HTML to be echoed.
     */
    public static function select2Row($form, $model, $attribute, $data, $multiSelect = false, 
            $hint = null, $hintOptions = null, $enabled = true, $defaultVal = null) {
        $widgetOptions = array(
            'data' => $data,
            'options' => array(
                'placeholder' => $model->getAttributeLabel($attribute),
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
        $options = array('widgetOptions' => $widgetOptions);
        if ($hint !== null) {
            $options['hint'] = $hint;
        }
        if ($hintOptions !== null) {
            $options['hintOptions'] = $hintOptions;
        }
        return $form->select2Group($model, $attribute, $options);
    }

    /**
     * @param $form TbActiveForm
     * @param $model
     */
    public static function echoSubmitButton($form, $model) {
        $form->widget(
                'booster.widgets.TbButton', array(
            'buttonType' => 'submit',
            'context' => 'primary',
            'label' => $model->isNewRecord ? 'Criar' : 'Gravar',
        ));
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
     * @param bool $splitWords
     * @param bool $caseSensitive
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

    public static function getTodayDate($format = null)
    {
        $format = $format === null ? self::DEFAULT_DATE_FORMAT : $format;
        return self::getNow()->format($format);
    }

    public static function getNow()
    {
        return self::newDateTime();
    }

    public static function getTimeZone() {
        return new DateTimeZone('Europe/London');
    }

    public static function newDateTime($dateTimeString = null)
    {
        return new DateTime($dateTimeString, self::getTimeZone());
    }

    /**
     * @param $age
     * @return int
     */
    public static function ageToYearString($age)
    {
        $thisYear = (new DateTime())->format('Y');
        return $thisYear - $age;
    }

    /**
     * @param $string
     * @return mixed
     */
    public static function removeCarriageReturns($string)
    {
        return preg_replace( "/\r|\n/", " ", $string);
    }

    public static function getCriteriaFromAttributes($attributes, $operator = 'AND')
    {
        $criteria = new CDbCriteria;
        foreach ($attributes as $attribute => $value) {
            $criteria->compare($attribute, $value, false, $operator);
        }
        return $criteria;
    }

    /**
     * @param $data CActiveRecord[]
     * @param $keyAttributeName string
     * @param $valueAttributeName string
     * @return array
     */
    public static function modelsIntoAssociativeArrayInverted($data, $valueAttributeName, $keyAttributeName = 'primaryKey')
    {
        return self::modelsIntoAssociativeArray($data, $keyAttributeName, $valueAttributeName);
    }

    /**
     * @param $data CActiveRecord[]
     * @param $valueAttributeName string
     * @param $keyAttributeName string
     * @return array
     */
    private static function modelsIntoAssociativeArray($data, $valueAttributeName, $keyAttributeName = 'primaryKey')
    {
        $result = array();
        foreach ($data as $model) {
            $result[$model->$keyAttributeName] = $model->$valueAttributeName;
        }
        return $result;
    }


    /**
     * Remove diacritic characters
     *
     * @param $str
     * @return mixed
     * @link http://myshadowself.com/coding/php-function-to-convert-accented-characters-to-their-non-accented-equivalant/
     */
    public static function removeDiacritic($str) {
        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');
        return str_replace($a, $b, $str);
    }

    /**
     * @param $geocodingString
     * @return array
     * @throws CException if geocoding process fails
     */
    public static function geocode($geocodingString) {
        //200 ms delay between requests in order to avoid exceeding limits
        usleep(200 * 1000);
        $googleResult = self::getGoogleGeocodeApiResult($geocodingString);
        if ($googleResult['status'] != 'OK') {
            throw new CException("could not geocode $geocodingString. Error: {$googleResult['status']}");
        }
        //results[0].geometry.location.lat
        $coordinates = $googleResult['results'][0]['geometry']['location'];
        return array(
            $coordinates['lat'],
            $coordinates['lng'],
        );
    }

    /**
     * Based on http://jslim.net/blog/2013/09/12/get-json-using-php-curl-from-web-service/
     * @param $geocodingString
     * @return mixed
     */
    private static function getGoogleGeocodeApiResult($geocodingString) {
        // set HTTP header
        $headers = array(
            'Content-Type: application/json',
        );

        // query string
        $fields = array(
            'sensor' => false,
	 		'region' => 'pt',
            'address' => $geocodingString,
        );
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?' . http_build_query($fields);

        // Open connection
        $ch = curl_init();

        // Set the url, number of GET vars, GET data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Execute request
        $result = curl_exec($ch);

        // Close connection
        curl_close($ch);

        // get the result and parse to JSON
        return json_decode($result, true);
    }

}
