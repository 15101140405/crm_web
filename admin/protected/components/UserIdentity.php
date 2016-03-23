<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;
    private $_name;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        $user = User::model()->find(array(
            "condition" => "username=:username",
            "params" => array(
                ":username" => $this->username,
            ),
        ));

        if ($user && $user->password == $this->password) {
            $this->_name = $user->username;
            $this->_id = $user->id;
            $this->errorCode = self::ERROR_NONE;

        } else {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        }

        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getName()
    {
        return $this->_name;
    }
}
