<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Some extra methods found usefull that all model classes should have.
 *
 * @author diogoneves
 */
abstract class CExtendedActiveRecord extends CActiveRecord {

    /**
     * Checks if the model has not null attributes
     * @return boolean false if all the attributes of the model are null, true otherwise.
     */
    public function hasNotNullAttributes() {
        return count(array_filter($this->attributes)) != 0;
    }

    /**
     * @param $idsArray int[]
     * @param $attribute string
     * @return string
     */
    public static function getAttributeStringFromIDs($idsArray, $attribute, $glue = ', ')
    {
        $class = get_called_class();
        $attributeValues = CHelper::getArrayOfAttribute($class::model()->findAllByPk($idsArray), $attribute);
        return implode($glue, $attributeValues);
    }

    /**
     * Returns the list of data from this model, given the criteria
     * @param CDbCriteria $criteria The criteria to be used in order to get results. Defaults to all records.
     * @param string $valueField The value field attribute name. Defaults to the primary key.
     * @param string $textField The attribute name to return as textField. Defaults to {@link getListDataField}.
     * @param string $groupsField The attribute name to the group names. If empty, no groups are created.
     * @return array the list data that can be used in {@link dropDownList}, {@link listBox}, etc.
     */
    public function getListData($criteria = '', $valueField = '', $textField = '', $groupsField = '') {
        if ($valueField == '') {
            $valueField = $this->tableSchema->primaryKey;
        }
        if ($textField == '') {
            $textField = $this->getListDataTextField();
        }
        if ($groupsField == '') {
            $groupsField = $this->getListDataGroupField();
        }
        return CHtml::listData($this->findAll($criteria), $valueField, $textField, $groupsField);
    }

    /**
     * This method can be overwritten in order for {@link getListData} to get diferent text field
     * @return string the attribute name to get Text Filed data from
     */
    public function getListDataTextField() {
        return $this->attributeNames()[1];
    }

    /**
     * This method can be overwritten in order for {@link getListData} to get a group field
     * @return string the attribute name to get Text Filed data from
     */
    public function getListDataGroupField() {
        return '';
    }

    /**
     * This method must be overwritten in order for {@link search} to work differently
     * @return array the name of the searchable attributes
     */
    public function getSearchableAttributes() {
        return $this->attributeNames();
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
        /** @var CDbCriteria $criteria * */
        $criteria = new CDbCriteria;
        foreach ($this->getSearchableAttributes() as $attribute) {
            $criteria->compare($attribute, $this->$attribute, true);
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Searches all searchable fields for value.
     * @param string $value the value to search for.
     * @param bool $partialMatch whether the value should consider partial text match 
     * (using LIKE and NOT LIKE operators). Defaults to false, meaning exact comparison.
     * @param string[] $exceptions the attributes not to search.
     * @return CActiveRecord[] All the records that have $value in any of the searched attributes.
     */
    public function searchAllAttributes($value, $partialMatch = false, $exceptions = array()) {
        /** @var CDbCriteria $criteria * */
        $criteria = new CDbCriteria;
        foreach ($this->getSearchableAttributes() as $attribute) {
            if (!in_array($attribute, $exceptions)) {
                $criteria->compare($attribute, $value, $partialMatch, 'OR');
            }
        }
        return $this->findAll($criteria);
    }

    /**
     * Saves the current record. If the model has only null attribute values, it will not be saved.
     *
     * The record is inserted as a row into the database table if its {@link isNewRecord}
     * property is true (usually the case when the record is created using the 'new'
     * operator). Otherwise, it will be used to update the corresponding row in the table
     * (usually the case if the record is obtained using one of those 'find' methods.)
     *
     * Validation will be performed before saving the record. If the validation fails,
     * the record will not be saved. You can call {@link getErrors()} to retrieve the
     * validation errors.
     *
     * If the record is saved via insertion, its {@link isNewRecord} property will be
     * set false, and its {@link scenario} property will be set to be 'update'.
     * And if its primary key is auto-incremental and is not set before insertion,
     * the primary key will be populated with the automatically generated key value.
     *
     * @param boolean $runValidation whether to perform validation before saving the record.
     * If the validation fails, the record will not be saved to database.
     * @param array $attributes list of attributes that need to be saved. Defaults to null,
     * meaning all attributes that are loaded from DB will be saved.
     * @return boolean whether the saving succeeds
     */
    public function save($runValidation = true, $attributes = null) {
        if ($this->hasNotNullAttributes()) {
            return parent::save($runValidation, $attributes);
        }
        return false;
    }

    /**
     * Relates this model instance to the one(s) passed as an argument.
     * @param CExtendedActiveRecord[] $models model or models to relate this one with.
     * @return boolean[] whether the operation was successfull or not for each model.
     */
    public function relateTo($models) {
        if (!is_array($models)) {
            $models = array($models);
        }
        /* @var $i int iterator for the $status array index */
        /* @var $status boolean[] the array to return */
        $i = 0;
        $status = array();
        foreach ($models as $model) {
            $status[$i] = $this->relateToOneModel($model);
        }
        return $status;
    }

    /**
     * Relates this model instance to the one passed as an argument.
     * @param CExtendedActiveRecord $model the model to relate this one with.
     * @return boolean whether the operation was successfull or not.
     */
    protected function relateToOneModel(&$model) {
        /* @var $relationInfo string the relationship type between the models */
        $relationInfo = $this->getRelationType($model);
        switch ($relationInfo[0]) {
            case '': return false;
            case self::BELONGS_TO: return $this->relateBelongsTo($model, $relationInfo);
            case self::HAS_MANY: return $model->relateTo($this);
            case self::HAS_ONE: return $this->relateHasOne($model, $relationInfo);
            case self::MANY_MANY: return $this->relateManyToMany($model, $relationInfo);
            default: return false;
        }
    }

    /**
     * Returns the relation type between models.
     * @param CExtendedActiveRecord $model the model to check relationship.
     * @return array The relation info as associative array('varName','relationType', 'className', 'foreignKey')
     */
    protected function getRelationInfo(&$model) {
        foreach ($this->relations() as $varName => $relation) {
            if ($relation[1] == get_class($model)) {
                return array(
                    'varName' => $varName,
                    'relationtype' => $relation[0],
                    'className' => $relation[1],
                    'foreignKey' => $relation[2],
                );
            }
        }
        return array('');
    }

    /**
     * TODO: add composite primary keys support
     * @param CExtendedActiveRecord $model the modet to relate
     * @param array $relationInfo The relation info as associative array('varName','relationType', 'className', 'foreignKey')
     * @return bool Whether the operation was successfull or not.
     */
    protected function relateBelongsTo(&$model, $relationInfo) {
        if ($model->save()) {
            $this->{$relationInfo['foreignKeyAttribute']} = $model->primaryKey;
            if ($this->save()) {
                $this->refresh();
                $model->refresh();
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * TODO: write method
     * @param CExtendedActiveRecord $model the modet to relate
     * @param array $relationInfo The relation info as associative array('varName','relationType', 'className', 'foreignKey')
     * @return bool Whether the operation was successfull or not.
     */
    protected function relateHasOne(&$model, $relationInfo) {
        return false;
    }

    /**
     * TODO: write method
     * @param CExtendedActiveRecord $model the modet to relate
     * @param array $relationInfo The relation info as associative array('varName','relationType', 'className', 'foreignKey')
     * @return bool Whether the operation was successfull or not.
     */
    protected function relateManyToMany(&$model, $relationInfo) {
        return false;
    }

    /**
     * Returns the CHtml::link() result for this model's view, with the given attribute as label.
     * @param string $attributeName the attribute to be used as label
     * @return string the generated hyperlink
     */
    public function getLink($attributeName) {
        return CHtml::link(CHtml::encode($this->$attributeName), array(strtolower(get_class($this)) . '/view', 'id' => $this->primaryKey));
    }

    /**
     * 
     * @return string the errors found on this model.
     */
    public function getErrorsString() {
        $string = "";
        foreach ($this->getErrors() as $attribute => $errors) {
            $string .= $attribute . ": " . implode(", ", $errors) . "\n";
        }
        return $string;
    }

    /**
     * Usel because some widgets get the id.
     * @return mixed the primary key of this instance.
     */
    public function getId() {
        return $this->primaryKey;
    }

    /**
     * Allows the array_unique() method to work properly.
     * @return String
     */
    public function __toString() {
        return $this->primaryKey;
    }
    
    /**
     * 
     * @param String $attribute the attribute to be checked
     * @return boolean wether the given attribute is blank or not
     */
    public function isAttributeBlank($attribute) {
        $return = isset($this->$attribute) ?  $this->$attribute == null || $this->$attribute == '' : true;
        return $return;
    }
    
    public function beforeSave() {
        parent::beforeSave();
        foreach ($this->attributes as $attLabel => $attValue) {
            if ($attLabel != $this->tableSchema->primaryKey && $attValue == '') {
                $this->$attLabel = null;
            }
        }
        return true;
    }

}
