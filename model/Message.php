<?php

class Message extends Entity implements JsonSerializable
{
    public $number;

    public $message;

    public $deviceID;

    public $simSlot;

    public $schedule;

    public $userID;

    public $groupID;

    public $status;

    public $resultCode;

    public $errorCode;

    public $type;

    public $attachments;

    public $prioritize;

    public $retries;

    public $sentDate;

    public $deliveredDate;

    public $expiryDate;

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
    public function getResultCode(): ?string
    {
        if ($this->getType() === "sms") {
            if (isset($this->resultCode)) {
                $error = $this->resultCodeError();
                if (is_null($this->getErrorCode())) {
                    return $error;
                } else {
                    if ($error === "GENERIC_FAILURE" || substr($error, 0, 10) === "RESULT_RIL") {
                        return "{$error} [{$this->getErrorCode()}]";
                    }
                }
            }
        } else {
            return null;
        }
        return $this->resultCode;
    }

    private function resultCodeError(): ?string
    {
        switch ($this->resultCode) {
            case -1:
                return null;
            case 0:
                return "DELIVERY_FAILURE";
            case 1:
                return "GENERIC_FAILURE";
            case 2:
                return "RADIO_OFF";
            case 3:
                return "NULL_PDU";
            case 4:
                return "NO_SERVICE";
            case 5:
                return "LIMIT_EXCEEDED";
            case 6:
                return "RESULT_ERROR_FDN_CHECK_FAILURE";
            case 7:
                return "SHORT_CODE_NOT_ALLOWED";
            case 8:
                return "SHORT_CODE_NEVER_ALLOWED";
            case 9:
                return "RESULT_RADIO_NOT_AVAILABLE";
            case 10:
                return "RESULT_NETWORK_REJECT";
            case 11:
                return "RESULT_INVALID_ARGUMENTS";
            case 12:
                return "RESULT_INVALID_STATE";
            case 13:
                return "RESULT_NO_MEMORY";
            case 14:
                return "RESULT_INVALID_SMS_FORMAT";
            case 15:
                return "RESULT_SYSTEM_ERROR";
            case 16:
                return "RESULT_MODEM_ERROR";
            case 17:
                return "RESULT_NETWORK_ERROR";
            case 18:
                return "RESULT_ENCODING_ERROR";
            case 19:
                return "RESULT_INVALID_SMSC_ADDRESS";
            case 20:
                return "RESULT_OPERATION_NOT_ALLOWED";
            case 21:
                return "RESULT_INTERNAL_ERROR";
            case 22:
                return "RESULT_NO_RESOURCES";
            case 23:
                return "RESULT_CANCELLED";
            case 24:
                return "RESULT_REQUEST_NOT_SUPPORTED";
            case 25:
                return "RESULT_NO_BLUETOOTH_SERVICE";
            case 26:
                return "RESULT_INVALID_BLUETOOTH_ADDRESS";
            case 27:
                return "RESULT_BLUETOOTH_DISCONNECTED";
            case 28:
                return "RESULT_UNEXPECTED_EVENT_STOP_SENDING";
            case 29:
                return "RESULT_SMS_BLOCKED_DURING_EMERGENCY";
            case 30:
                return "RESULT_SMS_SEND_RETRY_FAILED";
            case 31:
                return "RESULT_REMOTE_EXCEPTION";
            case 32:
                return "RESULT_NO_DEFAULT_SMS_APP";
            case 100:
                return "RESULT_RIL_RADIO_NOT_AVAILABLE";
            case 101:
                return "RESULT_RIL_SMS_SEND_FAIL_RETRY";
            case 102:
                return "RESULT_RIL_NETWORK_REJECT";
            case 103:
                return "RESULT_RIL_INVALID_STATE";
            case 104:
                return "RESULT_RIL_INVALID_ARGUMENTS";
            case 105:
                return "RESULT_RIL_NO_MEMORY";
            case 106:
                return "RESULT_RIL_REQUEST_RATE_LIMITED";
            case 107:
                return "RESULT_RIL_INVALID_SMS_FORMAT";
            case 108:
                return "RESULT_RIL_SYSTEM_ERR";
            case 109:
                return "RESULT_RIL_ENCODING_ERR";
            case 110:
                return "RESULT_RIL_INVALID_SMSC_ADDRESS";
            case 111:
                return "RESULT_RIL_MODEM_ERR";
            case 112:
                return "RESULT_RIL_NETWORK_ERR";
            case 113:
                return "RESULT_RIL_INTERNAL_ERR";
            case 114:
                return "RESULT_RIL_REQUEST_NOT_SUPPORTED";
            case 115:
                return "RESULT_RIL_INVALID_MODEM_STATE";
            case 116:
                return "RESULT_RIL_NETWORK_NOT_READY";
            case 117:
                return "RESULT_RIL_OPERATION_NOT_ALLOWED";
            case 118:
                return "RESULT_RIL_NO_RESOURCES";
            case 119:
                return "RESULT_RIL_CANCELLED";
            case 120:
                return "RESULT_RIL_SIM_ABSENT";
            case 121:
                return "RESULT_RIL_SIMULTANEOUS_SMS_AND_CALL_NOT_ALLOWED";
            case 122:
                return "RESULT_RIL_ACCESS_BARRED";
            case 123:
                return "RESULT_RIL_BLOCKED_DUE_TO_CALL";
            case 26012019:
                return "APP_ERROR";
            default:
                return "UNKNOWN_ERROR-{$this->resultCode}";
        }
    }

    /**
     * @param int|null $resultCode
     */
    public function setResultCode(?int $resultCode)
    {
        $this->resultCode = $resultCode;
    }

    /**
     * @return int|null
     */
    public function getErrorCode(): ?int
    {
        if ($this->errorCode == -1) {
            return null;
        }
        return $this->errorCode;
    }

    /**
     * @param int|null $errorCode
     */
    public function setErrorCode(?int $errorCode)
    {
        $this->errorCode = $errorCode;
    }


    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type ?? "sms";
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getAttachments(): array
    {
        if (empty($this->attachments)) {
            return [];
        }
        return explode(',', $this->attachments);
    }

    /**
     * @param string $attachments
     */
    public function setAttachments(string $attachments)
    {
        $this->attachments = $attachments;
    }

    /**
     * @return bool
     */
    public function getPrioritize(): bool
    {
        return $this->prioritize;
    }

    /**
     * @param bool $prioritize
     */
    public function setPrioritize(bool $prioritize): void
    {
        $this->prioritize = $prioritize;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
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
     * @return DateTime|null
     */
    public function getDeliveredDate(): ?DateTime
    {
        if (isset($this->deliveredDate)) {
            return getDisplayTime($this->deliveredDate);
        }
        return null;
    }

    /**
     * @param string|null $deliveredDate
     */
    public function setDeliveredDate(?string $deliveredDate)
    {
        $this->deliveredDate = $deliveredDate;
    }

    /**
     * @return DateTime|null
     * @throws Exception
     */
    public function getExpiryDate(): ?DateTime
    {
        if (isset($this->expiryDate)) {
            return new DateTime($this->expiryDate);
        }
        return null;
    }

    /**
     * @param string|null $expiryDate
     */
    public function setExpiryDate(?string $expiryDate)
    {
        $this->expiryDate = $expiryDate;
    }

    /**
     * @return int|null
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
     * @return string|null
     */
    public function getGroupID(): ?string
    {
        return $this->groupID;
    }

    /**
     * @param string $groupID
     */
    public function setGroupID(string $groupID)
    {
        $this->groupID = $groupID;
    }

    /**
     * @return int
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
     * @return DateTime|null
     * @throws Exception
     */
    public function getSchedule(): ?DateTime
    {
        if (isset($this->schedule)) {
            return DateTime::createFromFormat("U", $this->schedule)
                ->setTimezone(new DateTimeZone($_SESSION['timeZone']));
        }
        return null;
    }

    /**
     * @param int|null $schedule
     */
    public function setSchedule(?int $schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * @return int
     */
    public function getRetries(): int
    {
        return (int)$this->retries;
    }

    /**
     * @param int $retries
     */
    public function setRetries(int $retries)
    {
        $this->retries = $retries;
    }

    public function getErrorDescription(): ?string
    {
        $resultCodes = [
            "NO_SERVICE" => "Your device simply has no cell reception. You're probably in the middle of nowhere, somewhere inside, underground, or up in space. Certainly away from any cell phone tower. Sometimes it also happens when you get a call while sending messages.",
            "RADIO_OFF" => "You switched your device into airplane mode, which tells your device exactly \"turn all radios off\" (cell, wifi, Bluetooth, NFC, ...).",
            "LIMIT_EXCEEDED" => "Failed because the phone reached the sending queue limit. Try setting the higher delay between messages from Profile page.",
            "GENERIC_FAILURE" => "Something went wrong and there's no way to tell what, why or how. You have to provide the mobile number in E.164 formatting (e.g. +911234567890, +14155552671). You can also try setting the higher delay between messages from Profile page.",
        ];

        $errorCodes = [
            1 => "This cause indicates that the destination requested by the Mobile Station cannot be reached because, although the number is in a valid format, it is not currently assigned (allocated).",
            8 => "This cause indicates that the MS has tried to send a mobile originating short message when the MS’s network operator or service provider has forbidden such transactions.",
            10 => "This cause indicates that the outgoing call barred service applies to the short message service for the called destination.",
            17 => "This cause is sent to the MS if the MSC cannot service an MS generated request because of PLMN failures, e.g. problems in MAP.",
            21 => "This cause indicates that the equipment sending this cause does not wish to accept this short message, although it could have accepted the short message since the equipment sending this cause is neither busy nor incompatible.",
            27 => "This cause indicates that the destination indicated by the Mobile Station cannot be reached because the interface to the destination is not functioning correctly. The term “not functioning correctly” indicates that a signaling message was unable to be delivered to the remote user; e.g., a physical layer or data link layer failure at the remote user, user equipment off-line, etc.",
            28 => "This cause indicates that the subscriber is not registered in the PLMN (i.e. IMSI not known).",
            29 => "This cause indicates that the facility requested by the Mobile Station is not supported by the PLMN.",
            30 => "This cause indicates that the subscriber is not registered in the HLR (i.e. IMSI or directory number is not allocated to a subscriber).",
            38 => "This cause indicates that the network is not functioning correctly and that the condition is likely to last a relatively long period of time; e.g., immediately reattempting the short message transfer is not likely to be successful.",
            41 => "This cause indicates that the network is not functioning correctly and that the condition is not likely to last a long period of time; e.g., the Mobile Station may wish to try another short message transfer attempt almost immediately.",
            42 => "This cause indicates that the short message service cannot be serviced because of high traffic.",
            47 => "Resources unavailable. This cause is used to report a resource unavailable event only when no other cause applies.",
            50 => "This cause indicates that the requested short message service could not be provided by the network because the user has not completed the necessary administrative arrangements with its supporting networks.",
            69 => "This cause indicates that the network is unable to provide the requested short message service.",
            81 => "This cause indicates that the equipment sending this cause has received a message with a short message reference which is not currently in use on the MS-network interface.",
            95 => "This cause is used to report an invalid message event only when no other cause in the invalid message class applies.",
            96 => "This cause indicates that the equipment sending this cause has received a message where a mandatory information element is missing and/or has a content error (the two cases are indistinguishable).",
            97 => "This cause indicates that the equipment sending this cause has received a message with a message type it does not recognize either because this is a message not defined or defined but not implemented by the equipment sending this cause.",
            98 => "Message not compatible with short message protocol state",
            99 => "This cause indicates that the equipment sending this cause has received a message which includes information elements not recognized because the information element identifier is not defined or it is defined but not implemented by the equipment sending the cause. However, the information element is not required to be present in the message in order for the equipment to send the cause to process the message.",
            111 => "This cause is used to report a protocol error event only when no other cause applies.",
            127 => "This cause indicates that there has been interworking with a network that does not provide causes for actions it takes; thus, the precise cause for a message which is being sent cannot be ascertained.",
            128 => "Telematic internetworking not supported",
            129 => "Short message type 0 not supported",
            130 => "Cannot replace short message",
            143 => "Unspecified TP-PID error",
            144 => "Data code scheme not supported",
            145 => "Message class not supported",
            159 => "Unspecified TP-DCS error",
            160 => "Command cannot be actioned",
            161 => "Command unsupported",
            175 => "Unspecified TP-Command error",
            176 => "TPDU not supported",
            192 => "SC busy",
            193 => "No SC subscription",
            194 => "SC System failure",
            195 => "Invalid SME address",
            196 => "Destination SME barred",
            197 => "SM Rejected-Duplicate SM",
            198 => "TP-VPF not supported",
            199 => "TP-VP not supported",
            208 => "D0 SIM SMS Storage full",
            209 => "No SMS Storage capability in SIM",
            210 => "Error in MS",
            211 => "Memory capacity exceeded",
            212 => "Sim application toolkit busy",
            213 => "SIM data download error",
            255 => "Unspecified error cause",
            300 => "ME Failure",
            301 => "SMS service of ME reserved",
            302 => "Operation not allowed",
            303 => "Operation not supported",
            304 => "Invalid PDU mode parameter",
            305 => "Invalid Text mode parameter",
            310 => "SIM not inserted",
            311 => "SIM PIN required",
            312 => "PH-SIM PIN required",
            313 => "SIM failure",
            314 => "SIM busy",
            315 => "SIM wrong",
            316 => "SIM PUK required",
            317 => "SIM PIN2 required",
            318 => "SIM PUK2 required",
            320 => "Memory failure",
            321 => "Invalid memory index",
            322 => "Memory full",
            330 => "SMSC address unknown",
            331 => "No network service",
            332 => "Network timeout",
            340 => "No +CNMA expected",
            500 => "Unknown error",
            512 => "User abort",
            513 => "Unable to store",
            514 => "Invalid Status",
            515 => "Device busy or Invalid Character in string",
            516 => "Invalid length",
            517 => "Invalid character in PDU",
            518 => "Invalid parameter",
            519 => "Invalid length or character",
            520 => "Invalid character in text",
            521 => "Timer expired",
            522 => "Operation temporary not allowed",
            532 => "SIM not ready",
            534 => "Cell Broadcast error unknown",
            535 => "Protocol stack busy",
            538 => "Invalid parameter"
        ];

        $resultCode = $this->getResultCode();
        $errorCode = $this->getErrorCode();
        if (is_null($resultCode) && is_null($errorCode)) {
            return null;
        } else {
            if (is_null($errorCode) || !array_key_exists($errorCode, $errorCodes)) {
                return $resultCodes[$resultCode] ?? null;
            } else {
                return $errorCodes[$errorCode];
            }
        }
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
        $message = get_object_vars($this);
        $message["sentDate"] = $this->getSentDate()->format(DATE_ISO8601);
        $deliveredDate = $this->getDeliveredDate();
        if (isset($deliveredDate)) {
            $message["deliveredDate"] = $deliveredDate->format(DATE_ISO8601);
        }
        return $message;
    }

    /**
     * @param Message[] $messages
     * @param User $user
     * @param bool $checkRetries
     * @throws Exception
     */
    public static function resend(array $messages, User $user, bool $checkRetries = false)
    {
        if ($user->getExpiryDate() !== null && new DateTime($user->getExpiryDate()) < new DateTime()) {
            throw new Exception(__("error_subscription_expired"));
        } else {
            $groups = [];
            foreach ($messages as $message) {
                if ($checkRetries) {
                    $maxRetries = Setting::get("max_retries") ? (int)Setting::get("max_retries") : 1;
                    if ($maxRetries <= $message->getRetries()) {
                        continue;
                    }
                }
                if (is_null($message->getDeviceID())) {
                    if ($user->getPrimaryDeviceID() == 0) {
                        throw new Exception(__("error_no_active_device_found"));
                    } else {
                        $message->setDeviceID($user->getPrimaryDeviceID());
                    }
                }
                $message->setSentDate(date("Y-m-d H:i:s"));
                $message->setDeliveredDate(null);
                $message->setResultCode(null);
                $message->setErrorCode(null);
                $message->setStatus("Pending");
                $message->setRetries($message->getRetries() + 1);
                if (array_key_exists($message->getDeviceID(), $groups)) {
                    $groupID = $groups[$message->getDeviceID()]["data"]["groupId"];
                } else {
                    $groupID = uniqid(random_str(18), true);
                    $deviceUser = User::getDeviceUser($message->getDeviceID(), $user->getID());
                    if ($deviceUser->getDevice()->getUseOwnerSettings() && $user->getID() != $deviceUser->getDevice()->getUserID()) {
                        $groups[$deviceUser->getDeviceID()] = ["device" => $deviceUser->getDevice(), "data" => ["groupId" => $groupID, "delay" => $deviceUser->getDevice()->getUser()->getDelay(), "reportDelivery" => $deviceUser->getDevice()->getUser()->getReportDelivery(), "useProgressiveQueue" => $deviceUser->getDevice()->getUser()->isUseProgressiveQueue(), "sleepTime" => $user->getSleepTime()]];
                    } else {
                        $groups[$deviceUser->getDeviceID()] = ["device" => $deviceUser->getDevice(), "data" => ["groupId" => $groupID, "delay" => $user->getDelay(), "reportDelivery" => $user->getReportDelivery(), "useProgressiveQueue" => $user->isUseProgressiveQueue(), "sleepTime" => $user->getSleepTime()]];
                    }
                }
                $message->setGroupID($groupID);
            }
            $testGroups = [];
            foreach ($groups as $deviceID => $group) {
                $group["data"] = [];
                $testGroups[$deviceID] = $group;
            }
            Device::processRequests($testGroups);
            MysqliDb::getInstance()->startTransaction();
            foreach ($messages as $message) {
                $message->save(false);
            }
            MysqliDb::getInstance()->commit();
            Device::processRequests($groups);
        }
    }

    /**
     * @param array $messages
     * @param User $user
     * @param array $devices
     * @param int|null $schedule
     * @param int $prioritize
     * @return array
     * @throws Exception
     */
    public static function sendMessages(array $messages, User $user, array $devices = [], ?int $schedule = null, int $prioritize = 0): array
    {
        if ($user->getExpiryDate() !== null && new DateTime($user->getExpiryDate()) < new DateTime()) {
            throw new Exception(__("error_subscription_expired"));
        } else {
            if (isset($schedule) && $schedule <= time()) {
                throw new Exception(__("error_invalid_schedule_time"));
            }
            $messagesCount = count($messages);
            $blacklist = $user->getBlacklistedNumbers();
            for ($i = 0; $i < $messagesCount; $i++) {
                $message = $messages[$i];
                if (!empty($message['ignoreBlacklist'])) {
                    continue;
                }
                if (isset($message["type"]) && $message["type"] === "mms") {
                    $numbers = explode('|', $message["number"]);
                    for ($j = 0; $j < count($numbers); $j++) {
                        if (in_array($numbers[$j], $blacklist)) {
                            unset($numbers[$j]);
                        }
                    }
                    $number = implode('|', $numbers);
                    if (isValidMobileNumber($number, true)) {
                        $messages[$i]["number"] = $number;
                    } else {
                        unset($messages[$i]);
                    }
                } else {
                    if (in_array($message["number"], $blacklist)) {
                        unset($messages[$i]);
                    }
                }
            }
            $requiredCredits = 0;
            foreach ($messages as $message) {
                $requiredCredits += countMessageCredits($message['number'], $message['message'], $message['type'] ?? 'sms');
            }
            if ($requiredCredits > 0) {
                $groups = array();
                if (is_null($user->getCredits()) || $user->getCredits() >= $requiredCredits) {
                    if (empty($devices)) {
                        if ($user->getPrimaryDeviceID() == 0) {
                            throw new Exception(__("error_no_active_device_found"));
                        } else {
                            $devices = [$user->getPrimaryDeviceID()];
                        }
                    }
                    $messagesChunks = partition($messages, count($devices));
                    $msgObjects = [];
                    $sims = $user->getSims();
                    for ($i = 0; $i < count($messagesChunks); $i++) {
                        $identifiers = Device::isValidIdentifier($devices[$i]);
                        if (empty($identifiers)) {
                            throw new Exception(__("error_invalid_request_format"));
                        }
                        if (!array_key_exists($identifiers[0], $groups)) {
                            $deviceUser = User::getDeviceUser($identifiers[0], $user->getID());
                            if ($deviceUser->getDevice()->getUseOwnerSettings() && $user->getID() != $deviceUser->getDevice()->getUserID()) {
                                $groups[$identifiers[0]] = [
                                    "device" => $deviceUser->getDevice(),
                                    "sims" => [],
                                    "data" => [],
                                    "settings" => [
                                        "delay" => $deviceUser->getDevice()->getUser()->getDelay(),
                                        "reportDelivery" => $deviceUser->getDevice()->getUser()->getReportDelivery(),
                                        "useProgressiveQueue" => $deviceUser->getDevice()->getUser()->isUseProgressiveQueue(),
                                        "prioritize" => $prioritize
                                    ]
                                ];
                            } else {
                                $groups[$identifiers[0]] = [
                                    "device" => $deviceUser->getDevice(),
                                    "sims" => [],
                                    "data" => [],
                                    "settings" => [
                                        "delay" => $user->getDelay(),
                                        "reportDelivery" => $user->getReportDelivery(),
                                        "useProgressiveQueue" => $user->isUseProgressiveQueue(),
                                        "prioritize" => $prioritize
                                    ]
                                ];
                            }
                            if ($user->getSleepTime()) {
                                $groups[$identifiers[0]]["settings"]["sleepTime"] = $user->getSleepTime();
                            }
                        }
                        if (isset($identifiers[1])) {
                            if (isset($sims[$identifiers[0]][$identifiers[1]])) {
                                $groups[$identifiers[0]]["sims"][] = $identifiers[1];
                            } else {
                                throw new Exception(__("error_no_sim_present", ["slot" => $identifiers[1]]));
                            }
                        }
                    }
                    Device::processRequests($groups);
                    MysqliDb::getInstance()->startTransaction();
                    $i = 0;
                    if (!$user->getIsAdmin() && Setting::get("footer_text_enabled") && !$user->hasActiveSubscription()) {
                        $footerText = Setting::get("footer_text");
                    }
                    $totalCount = 0;
                    foreach (array_keys($groups) as $deviceID) {
                        $messagesChunksDevice = [];
                        $groupID = uniqid(random_str(18), true);
                        $groups[$deviceID]["data"] = array_merge(["groupId" => $groupID], $groups[$deviceID]["settings"]);
                        unset($groups[$deviceID]["settings"]);
                        if (!empty($groups[$deviceID]["sims"])) {
                            foreach ($groups[$deviceID]["sims"] as $simSlot) {
                                $messagesChunksDevice[$simSlot] = $messagesChunks[$i];
                                $i++;
                            }
                        } else {
                            $messagesChunksDevice[null] = $messagesChunks[$i];
                            $i++;
                        }
                        $groupMessagesCount = 0;
                        foreach ($messagesChunksDevice as $simSlotIndex => $messagesSim) {
                            foreach ($messagesSim as $message) {
                                $messageType = $message["type"] ?? "sms";
                                if (!isset($message["message"]) || trim($message["message"]) === '') {
                                    if ($messageType === 'sms' || ($messageType === 'mms' && empty($message["attachments"]))) {
                                        throw new Exception(__("error_invalid_request_format"));
                                    }
                                } elseif (empty($message["number"])) {
                                    throw new Exception(__("error_invalid_request_format"));
                                } elseif (!isValidMobileNumber($message["number"], $messageType === 'mms')) {
                                    throw new Exception(__("error_use_valid_number"));
                                }
                                $obj = new Message();
                                $obj->setNumber($message["number"]);
                                $messageText = spintax($message["message"]);
                                $messageText = preg_replace_callback("/%random-([0-9]*)%/", function ($match) {
                                    return random_str($match[1], '0123456789');
                                }, $messageText);
                                if (isset($footerText)) {
                                    $messageText .= "\n\n{$footerText}";
                                }
                                $obj->setMessage($messageText);
                                if (is_null($schedule)) {
                                    $obj->setStatus("Pending");
                                } else {
                                    $obj->setStatus("Scheduled");
                                    $obj->setSchedule($schedule);
                                }
                                $obj->setPrioritize($prioritize);
                                $obj->setType($messageType);
                                if (isset($message["attachments"]) && $messageType === "mms") {
                                    $obj->setAttachments($message["attachments"]);
                                }
                                $obj->setDeviceID($deviceID);
                                $obj->setExpiryDate($user->getExpiryDate());
                                $obj->setUserID($user->getID());
                                $obj->setGroupID($groupID);
                                if ($simSlotIndex !== "") {
                                    $obj->setSimSlot($simSlotIndex);
                                } elseif (isset($message["simSlot"])) {
                                    $obj->setSimSlot($message["simSlot"]);
                                }
                                $obj->setSentDate(date("Y-m-d H:i:s"));
                                $obj->save();
                                $msgObjects[] = $obj;
                                $groupMessagesCount += countMessageCredits($message["number"], $messageText, $messageType);
                            }
                        }
                        $groups[$deviceID]["count"] = $groupMessagesCount;
                        $totalCount += $groupMessagesCount;
                    }
                    if (isset($schedule)) {
                        $user->depleteCredits($totalCount);
                    }
                    MysqliDb::getInstance()->commit();
                    if (is_null($schedule)) {
                        Device::processRequests($groups, $user);
                    }
                    return $msgObjects;
                } else {
                    throw new Exception(__("error_credits_depleted"));
                }
            } else {
                throw new Exception(__("error_zero_messages"));
            }
        }
    }

    /**
     * @param string $attachments
     * @return string
     * @throws Exception
     */
    public static function isValidAttachments(string $attachments): string
    {
        $attachments = explode(',', $attachments);
        $result = "";
        foreach ($attachments as $attachment) {
            $attachment = trim($attachment);
            if ($attachment) {
                if (!filter_var($attachment, FILTER_VALIDATE_URL)) {
                    throw new Exception(__("attachments_invalid"));
                }
                if (!empty($result)) {
                    $result .= ",";
                }
                $result .= $attachment;
            }
        }
        return $result;
    }
}
