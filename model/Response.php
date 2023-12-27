<?php

const EXACT_CASE_INSENSITIVE = 0;
const EXACT_CASE_SENSITIVE = 1;
const CONTAINS = 2;
const REGULAR_EXPRESSION = 3;

class Response extends Entity
{

    public $message;

    public $response;

    public $matchType;

    public $enabled;

    public $userID;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getResponse(): string
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
     * @return int
     */
    public function getMatchType(): int
    {
        return (int)$this->matchType;
    }

    /**
     * @param int $matchType
     */
    public function setMatchType(int $matchType)
    {
        $this->matchType = $matchType;
    }

    /**
     * @return int
     */
    public function getEnabled(): int
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @param string $message
     * @return bool
     * @note https://stackoverflow.com/a/28200905/1273550
     */
    public function match(string $message): bool
    {
        if ($this->getMatchType() != REGULAR_EXPRESSION) {
            $messages = preg_split("/(?<!\\\\)\|/im", $this->getMessage(), -1, PREG_SPLIT_NO_EMPTY);
        } else {
            $messages = [$this->getMessage()];
        }
        $result = false;
        foreach ($messages as $msg) {
            $msg = str_replace("\|", "|", $msg);
            switch ($this->getMatchType()) {
                case EXACT_CASE_INSENSITIVE:
                    $result = mb_strtolower($msg) === mb_strtolower($message) || mb_strtoupper($msg) === mb_strtoupper($message);
                    break;
                case EXACT_CASE_SENSITIVE:
                    $result = $msg === $message;
                    break;
                case CONTAINS:
                    $result = mb_stripos($message, $msg) !== false;
                    break;
                case REGULAR_EXPRESSION:
                    $result = @preg_match($msg, $message, $matches) === 1 && $matches[0] === $message;
                    break;
            }
            if ($result) {
                break;
            }
        }
        return $result;
    }
}