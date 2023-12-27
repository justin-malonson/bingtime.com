<?php

class ContactsList extends Entity
{
    public $name;

    public $userID;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
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

    /**
     * @param int $listID
     * @param int $userID
     * @return bool|ContactsList
     * @throws Exception
     */
    public static function getContactsList(int $listID, int $userID)
    {
        $contactsList = new ContactsList();
        $contactsList->setID($listID);
        $contactsList->setUserID($userID);
        return $contactsList->read();
    }

    /**
     * @param int $listID
     * @return array
     * @throws Exception
     */
    public static function getNumbers(int $listID): array
    {
        $result = MysqliDb::getInstance()
            ->where('contactsListID', $listID)
            ->orderBy('number')
            ->get('Contact', null, 'number');
        $contacts = [];
        foreach ($result as $row) {
            $contacts[$row["number"]] = 1;
        }
        return $contacts;
    }
}