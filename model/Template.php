<?php

class Template extends Entity
{
    public $name;

    public $message;

    public $userID;

    /**
     * @return string
     */
    public function getName(): string
    {
        return htmlentities($this->name, ENT_QUOTES);
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return htmlentities($this->message, ENT_QUOTES);
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getUserID(): int
    {
        return $this->userID;
    }

    /**
     * @param int $userID
     */
    public function setUserID(int $userID)
    {
        $this->userID = $userID;
    }
}