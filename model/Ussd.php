<?php

class Ussd extends Entity implements JsonSerializable
{
    public $request;

    public $response;

    public $userID;

    public $deviceID;

    public $simSlot;

    public $sentDate;

    public $responseDate;

    /**
     * @return string
     */
    public function getRequest(): string
    {
        return $this->request;
    }

    /**
     * @param string $request
     */
    public function setRequest(string $request)
    {
        $this->request = $request;
    }

    /**
     * @return string|null
     */
    public function getResponse(): ?string
    {
        return $this->response;
    }

    /**
     * @param string $response
     */
    public function setResponse(string $response)
    {
        $this->response = $response;
    }

    /**
     * @return DateTime|null
     */
    public function getResponseDate(): ?DateTime
    {
        if (isset($this->responseDate)) {
            return getDisplayTime($this->responseDate);
        }
        return null;
    }

    /**
     * @param string $responseDate
     */
    public function setResponseDate(string $responseDate)
    {
        $this->responseDate = $responseDate;
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
     * @return null|int
     */
    public function getDeviceID(): ?int
    {
        return $this->deviceID;
    }

    /**
     * @param int $deviceID
     */
    public function setDeviceID(int $deviceID)
    {
        $this->deviceID = $deviceID;
    }

    /**
     * @return null|int
     */
    public function getSimSlot(): ?int
    {
        return $this->simSlot;
    }

    /**
     * @param int|null $simSlot
     */
    public function setSimSlot(?int $simSlot)
    {
        $this->simSlot = $simSlot;
    }

    /**
     * @return DateTime
     */
    public function getSentDate(): DateTime
    {
        return getDisplayTime($this->sentDate);
    }

    /**
     * @param string $sentDate
     */
    public function setSentDate(string $sentDate)
    {
        $this->sentDate = $sentDate;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize(): array
    {
        $ussdRequest = get_object_vars($this);
        $ussdRequest["sentDate"] = $this->getSentDate()->format(DATE_ISO8601);
        $responseDate = $this->getResponseDate();
        if (isset($responseDate)) {
            $ussdRequest["responseDate"] = $responseDate->format(DATE_ISO8601);
        }
        return $ussdRequest;
    }
}
