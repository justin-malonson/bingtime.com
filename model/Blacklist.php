<?php

class Blacklist extends Entity
{
    /**
     * @var string
     */
    public $number;

    /**
     * @var int
     */
    public $userID;

    /**
     * @var User
     */
    public $user;

    public static $relations = [
        "User" => ["ID", "userID"]
    ];

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber(string $number): void
    {
        $this->number = $number;
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
    public function setUserID(int $userID): void
    {
        $this->userID = $userID;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}