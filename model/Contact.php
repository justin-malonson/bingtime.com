<?php

use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Reader\Common\Creator\ReaderFactory;
use OpenSpout\Reader\Exception\ReaderNotOpenedException;

class Contact extends Entity
{
    public $name;

    public $number;

    public $subscribed;

    public $contactsListID;

    public $contactsList;

    public static $relations = [
        "ContactsList" => ["ID", "contactsListID"]
    ];

    public function __construct()
    {
        $this->contactsList = new ContactsList();
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name)
    {
        $this->name = $name;
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
    public function setNumber(string $number)
    {
        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getContactsListID(): int
    {
        return $this->contactsListID;
    }

    /**
     * @param int $contactsListID
     */
    public function setContactsListID(int $contactsListID)
    {
        $this->contactsListID = $contactsListID;
    }

    /**
     * @return bool
     */
    public function getSubscribed(): bool
    {
        return $this->subscribed;
    }

    /**
     * @param bool $subscribed
     */
    public function setSubscribed(bool $subscribed)
    {
        $this->subscribed = $subscribed;
    }

    public function getMessage($message)
    {
        $vars = [
            "%name%" => $this->getName(),
            "%number%" => rawurlencode($this->getNumber()),
            "%listID%" => $this->getContactsListID(),
        ];
        foreach ($vars as $var => $value) {
            $message = str_ireplace($var, $value, $message);
        }
        return $message;
    }

    /**
     * @return ContactsList
     */
    public function getContactsList(): ContactsList
    {
        return $this->contactsList;
    }

    /**
     * @param ContactsList $contactsList
     */
    public function setContactsList(ContactsList $contactsList)
    {
        $this->contactsList = $contactsList;
    }

    /**
     * @param string $filePath
     * @param int $listID
     * @return int
     * @throws IOException
     * @throws ReaderNotOpenedException
     * @throws UnsupportedTypeException
     * @throws Exception
     */
    public static function import(string $filePath, int $listID): int
    {
        $count = 0;
        $reader = ReaderFactory::createFromFile($filePath);
        $reader->open($filePath);
        $contacts = ContactsList::getNumbers($listID);
        $data = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $cells = $row->getCells();
                if (count($cells) >= 2) {
                    $name = null;
                    if (!empty($cells[0]->getValue())) {
                        $name = sanitize($cells[0]->getValue());
                    }
                    $number = sanitize($cells[1]->getValue());
                    if (isValidMobileNumber($number) && !isset($contacts[$number])) {
                        $contacts[$number] = 1;
                        $data[] = [
                            "name" => empty($name) ? null : $name,
                            "number" => $number,
                            "contactsListID" => $listID
                        ];
                        $count++;
                    }
                    if ($count % 1000 === 0) {
                        Contact::insertMultiple($data);
                        $data = [];
                    }
                }
            }
        }
        $reader->close();
        if (!empty($data)) {
            Contact::insertMultiple($data);
        }
        unlink($filePath);
        return $count;
    }
}