<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $_id;

    /**
     * 
     * @param User $user
     */
    public function __construct($username, $password, $id = 0) {
        parent::__construct($username, $password);
        $this->_id = $id;
    }

    public function authenticate() {
        $record = User::model()->findByContactInfo($this->username);
        if ($record === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if (!$record->isActivated()) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if (!CPasswordHelper::verifyPassword($this->password, $record->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->_id = $record->userID;
            $this->username = $record->name;
            //$this->setState('title', $record->title);
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId() {
        return $this->_id;
    }

    /**
     * Logs a user in for 30 minutes
     * 
     */
    public function login() {
        $duration = 3600 / 2; // 30 minutes
        Yii::app()->user->login($this, $duration);
    }

}
