<?php

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class User extends Entity
{
    public $name;

    public $email;

    public $password;

    public $isAdmin;

    public $delay;

    public $ussdDelay;

    public $dateAdded;

    public $lastLogin;

    public $lastLoginIP;

    public $devicesLimit;

    public $contactsLimit;

    public $apiKey;

    public $reportDelivery;

    public $autoRetry;

    public $language;

    public $credits;

    public $expiryDate;

    public $smsToEmail;

    public $useProgressiveQueue;

    public $receivedSmsEmail;

    public $sleepTime;

    public $timeZone;

    public $webHook;

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
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getPrimaryDeviceID(): int
    {
        $deviceUser = DeviceUser::where("DeviceUser.userID", $this->getID())
            ->where("DeviceUser.active", true)
            ->where("Device.enabled", true)
            ->read();
        if ($deviceUser) {
            return $deviceUser->getDeviceID();
        }
        return 0;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    }

    /**
     * @return bool
     */
    public function getIsAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin(bool $isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return DateTime
     */
    public function getDateAdded(): DateTime
    {
        return getDisplayTime($this->dateAdded);
    }

    /**
     * @param string $dateAdded
     */
    public function setDateAdded(string $dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @return DateTime|null
     */
    public function getLastLogin(): ?DateTime
    {
        if (isset($this->lastLogin)) {
            return getDisplayTime($this->lastLogin);
        }
        return null;
    }

    /**
     * @param string $lastLogin
     */
    public function setLastLogin(string $lastLogin)
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * @return string|null
     */
    public function getLastLoginIP(): ?string
    {
        return $this->lastLoginIP;
    }

    /**
     * @param  string  $lastLoginIP
     */
    public function setLastLoginIP(string $lastLoginIP): void
    {
        $this->lastLoginIP = $lastLoginIP;
    }

    /**
     * @return string
     */
    public function getDelay(): string
    {
        return $this->delay;
    }

    /**
     * @param string $delay
     * @throws Exception
     */
    public function setDelay(string $delay)
    {
        self::isValidDelay($delay);
        $this->delay = $delay;
    }

    /**
     * @return int
     */
    public function getUssdDelay(): int
    {
        return $this->ussdDelay;
    }

    /**
     * @param int $ussdDelay
     */
    public function setUssdDelay(int $ussdDelay): void
    {
        $this->ussdDelay = $ussdDelay;
    }

    /**
     * @return int|null
     */
    public function getDevicesLimit(): ?int
    {
        if (isset($this->devicesLimit)) {
            return (int)$this->devicesLimit;
        }
        return null;
    }

    /**
     * @param int|null $devicesLimit
     */
    public function setDevicesLimit(?int $devicesLimit)
    {
        $this->devicesLimit = $devicesLimit;
    }

    /**
     * @return int|null
     */
    public function getContactsLimit(): ?int
    {
        if (isset($this->contactsLimit)) {
            return (int)$this->contactsLimit;
        }
        return null;
    }

    /**
     * @param int|null $contactsLimit
     */
    public function setContactsLimit(?int $contactsLimit)
    {
        $this->contactsLimit = $contactsLimit;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return int
     */
    public function getReportDelivery(): int
    {
        return $this->reportDelivery;
    }

    /**
     * @param int $reportDelivery
     */
    public function setReportDelivery(int $reportDelivery)
    {
        $this->reportDelivery = $reportDelivery;
    }

    /**
     * @return int
     */
    public function getAutoRetry(): int
    {
        return $this->autoRetry;
    }

    /**
     * @param int $autoRetry
     */
    public function setAutoRetry(int $autoRetry)
    {
        $this->autoRetry = $autoRetry;
    }

    /**
     * @return int|null
     */
    public function getCredits(): ?int
    {
        if (isset($this->credits)) {
            return (int)$this->credits;
        }
        return null;
    }

    /**
     * @param int|null $credits
     */
    public function setCredits(?int $credits)
    {
        $this->credits = $credits;
    }

    /**
     * @return string
     */
    public function getTimeZone(): string
    {
        return $this->timeZone;
    }

    /**
     * @param string $timeZone
     */
    public function setTimeZone(string $timeZone)
    {
        $this->timeZone = $timeZone;
    }

    /**
     * @return string|null
     */
    public function getWebHook(): ?string
    {
        return $this->webHook;
    }

    /**
     * @param string|null $webHook
     */
    public function setWebHook(?string $webHook)
    {
        $this->webHook = $webHook;
    }

    /**
     * @return string
     */
    public function getExpiryDate(): ?string
    {
        return $this->expiryDate;
    }

    /**
     * @param string|null $expiryDate
     */
    public function setExpiryDate(?string $expiryDate)
    {
        $this->expiryDate = $expiryDate;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;
    }

    /**
     * @return bool
     */
    public function getSmsToEmail(): bool
    {
        return $this->smsToEmail;
    }

    /**
     * @param bool $smsToEmail
     */
    public function setSmsToEmail(bool $smsToEmail)
    {
        $this->smsToEmail = $smsToEmail;
    }

    /**
     * @return int
     */
    public function isUseProgressiveQueue(): int
    {
        return $this->useProgressiveQueue;
    }

    /**
     * @param int $useProgressiveQueue
     */
    public function setUseProgressiveQueue(int $useProgressiveQueue): void
    {
        $this->useProgressiveQueue = $useProgressiveQueue;
    }

    /**
     * @return string
     */
    public function getReceivedSmsEmail(): ?string
    {
        return $this->receivedSmsEmail;
    }

    /**
     * @param string|null $receivedSmsEmail
     */
    public function setReceivedSmsEmail(?string $receivedSmsEmail)
    {
        $this->receivedSmsEmail = $receivedSmsEmail;
    }

    /**
     * @return string|null
     */
    public function getSleepTime(): ?string
    {
        return $this->sleepTime;
    }

    /**
     * @param string|null $sleepTime
     */
    public function setSleepTime(?string $sleepTime): void
    {
        $this->sleepTime = $sleepTime;
    }

    /**
     * @return false|string
     * @throws Exception
     */
    public function getQRCode()
    {
        $credentials = [
            'server' => getServerURL(),
            'key' => $this->getApiKey()
        ];

        return (new QRCode(
            new QROptions([
                'imageBase64' => false
            ])
        ))->render(json_encode($credentials));
    }

    /**
     * @param int $deviceID
     * @param int $userID
     * @return DeviceUser
     * @throws Exception
     */
    public static function getDeviceUser(int $deviceID, int $userID): DeviceUser
    {
        $deviceUser = DeviceUser::getById($deviceID, $userID);
        if ($deviceUser) {
            if ($deviceUser->isActive() && $deviceUser->getDevice()->getEnabled()) {
                return $deviceUser;
            } else {
                $deviceName = strval($deviceUser);
                if (session_status() == PHP_SESSION_NONE || (isset($_SESSION["userID"]) && $deviceUser->getUser()->getID() == $_SESSION["userID"])) {
                    throw new Exception(__("error_unable_to_connect_user", ["device" => $deviceName]));
                } else {
                    throw new Exception(
                        __("error_unable_to_connect_other", ["user" => $deviceUser->getUser()->getName(), "userEmail" => $deviceUser->getUser()->getEmail(), "device" => $deviceName])
                    );
                }
            }
        } else {
            throw new Exception(__("error_device_not_found"));
        }
    }

    public function hasActiveSubscription(): bool
    {
        return Subscription::where("Subscription.expiryDate", date("Y-m-d H:i:s"), ">")
                ->where("Subscription.userID", $this->getID())
                ->count() > 0;
    }

    /**
     * @param string $email
     * @param string $password
     * @return bool|User
     * @throws Exception
     */
    public static function login(string $email, string $password)
    {
        $user = new User();
        $user->setEmail($email);
        if ($user->read() && password_verify($password, $user->getPassword())) {
            return $user;
        } else {
            return false;
        }
    }

    /**
     * @param int $id
     * @return bool|User
     * @throws Exception
     */
    public static function getById(int $id)
    {
        $user = new User();
        $user->setID($id);
        return $user->read();
    }

    /**
     * @return User
     * @throws Exception
     */
    public static function getAdmin(): User
    {
        $admin = new User();
        $admin->setIsAdmin(true);
        $admin->read();
        return $admin;
    }

    /**
     * @throws Exception
     */
    public function getBlacklistedNumbers(): array
    {
        $entries = Blacklist::where('userID', $this->getID())->read_all(false);
        $numbers = [];
        foreach ($entries as $entry) {
            $numbers[] = $entry->getNumber();
        }
        return $numbers;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isActiveDevicesLimitReached(): bool
    {
        if ($this->getDevicesLimit() !== null) {
            return Device::where("Device.userID", $this->getID())
                    ->where("Device.enabled", true)
                    ->count() >= $this->getDevicesLimit();
        } else {
            return false;
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getSims(): array
    {
        return Sim::getSims($this->getID());
    }

    /**
     * @param int $userID
     * @return array
     * @throws Exception
     */
    public static function getDeviceIds(int $userID): array
    {
        $deviceUsers = DeviceUser::where('userID', $userID)
            ->read_all(false);
        $deviceIds = [];
        foreach ($deviceUsers as $deviceUser) {
            $deviceIds[] = $deviceUser->getDeviceID();
        }
        return $deviceIds;
    }

    /**
     * @param bool $onlyEnabled
     * @param int|null $minAndroidVersion
     * @param bool $owned
     * @return DeviceUser[]
     * @throws Exception
     */
    public function getDevices(bool $onlyEnabled = true, ?int $minAndroidVersion = null, bool $owned = false): array
    {
        $deviceUser = DeviceUser::where("DeviceUser.userID", $this->getID());
        if ($onlyEnabled) {
            $deviceUser->where("DeviceUser.active", true)
                ->where("Device.enabled", true);
        }
        if ($owned) {
            $deviceUser->where('Device.userID', $this->getID());
        }
        if ($minAndroidVersion) {
            $deviceUser->where("Device.androidVersion", $minAndroidVersion, ">=");
        }
        return $deviceUser->read_all();
    }

    /**
     * @param int $selectedDevice
     * @param int|null $minAndroidVersion
     * @param bool $owned
     * @throws Exception
     */
    public function generateDevicesList(int $selectedDevice, ?int $minAndroidVersion = null, bool $owned = false)
    {
        $deviceUsers = $this->getDevices(true, $minAndroidVersion, $owned);
        foreach ($deviceUsers as $deviceUser) {
            $selected = $selectedDevice == $deviceUser->getDeviceID();
            $value = $deviceUser->getDeviceID();
            $option = htmlentities(strval($deviceUser), ENT_QUOTES);
            createOption($option, $value, $selected);
        }
    }

    /**
     * @param int[] $selectedDevice
     * @param bool $onlyEnabled
     * @throws Exception
     */
    public function generateDeviceSimsList(array $selectedDevice, bool $onlyEnabled = true)
    {
        $deviceUsers = $this->getDevices($onlyEnabled);
        $sims = $this->getSims();
        foreach ($deviceUsers as $deviceUser) {
            $selected = in_array($deviceUser->getDeviceID(), $selectedDevice);
            if (isset($sims[$deviceUser->getDeviceID()]) && count($sims[$deviceUser->getDeviceID()]) > 1) {
                foreach ($sims[$deviceUser->getDeviceID()] as $simSlot => $simName) {
                    $value = $deviceUser->getDeviceID() . "|" . $simSlot;
                    $option = htmlentities(strval($deviceUser), ENT_QUOTES) . " | " . $simName;
                    createOption($option, $value, $selected);
                }
            } else {
                $value = $deviceUser->getDeviceID();
                $option = htmlentities(strval($deviceUser), ENT_QUOTES);
                createOption($option, $value, $selected);
            }
        }
    }

    /**
     * @param int|null $newLimit
     * @throws Exception
     */
    public function changeDevicesLimit(?int $newLimit)
    {
        $oldLimit = $this->getDevicesLimit();
        $this->setDevicesLimit($newLimit);
        if ($newLimit != null) {
            if ($oldLimit == null || $oldLimit > $newLimit) {
                $userDevices = Device::where("userID", $this->getID())->where("enabled", true)->read_all();
                $userDevicesCount = count($userDevices);
                if ($userDevicesCount > $newLimit) {
                    $disableDevicesCount = $userDevicesCount - $newLimit;
                    for ($i = 0; $i < $disableDevicesCount; $i++) {
                        $userDevice = $userDevices[$i];
                        $userDevice->setEnabled(0);
                        $userDevice->save();
                    }
                }
            }
        }
    }


    /**
     * @param int|null $newLimit
     * @throws Exception
     */
    public function changeContactLimit(?int $newLimit)
    {
        $oldLimit = $this->getContactsLimit();
        $this->setContactsLimit($newLimit);
        if ($newLimit != null) {
            if ($oldLimit == null || $oldLimit > $newLimit) {
                $userContacts = Contact::where("ContactsList.userID", $this->getID())->where("subscribed", true)->read_all();
                $userContactsCount = count($userContacts);
                if ($userContactsCount > $newLimit) {
                    $unsubscribeContactsCount = $userContactsCount - $newLimit;
                    for ($i = 0; $i < $unsubscribeContactsCount; $i++) {
                        $userContact = $userContacts[$i];
                        $userContact->delete();
                    }
                }
            }
        }
    }

    public function getLimits(?string &$devices, ?string &$contacts, ?string &$credits, ?string &$expiryDate)
    {
        $devices = __("unlimited_devices");
        if ($this->getDevicesLimit() !== null) {
            if ($this->getDevicesLimit() > 1) {
                $devices = "{$this->getDevicesLimit()} " . strtolower(__('devices'));
            } else if ($this->getDevicesLimit() == 1) {
                $devices = "1 " . strtolower(__("device"));
            } else {
                $devices = __("shared_devices");
            }
        }
        $contacts = __("unlimited_contacts");
        if ($this->getContactsLimit() !== null) {
            if ($this->getContactsLimit() > 1) {
                $contacts = "{$this->getContactsLimit()} " . strtolower(__('contacts'));
            } else {
                $contacts = "{$this->getContactsLimit()} " . __("contact");
            }
        }
        $credits = __("unlimited_credits");
        if ($this->getCredits() !== null) {
            if ($this->getCredits() > 1) {
                $credits = "{$this->getCredits()} " . strtolower(__('credits'));
            } else {
                $credits = "{$this->getCredits()} " . __("credit");
            }
        }
        $expiryDate = is_null($this->getExpiryDate()) ? __("forever") : getDisplayTime($this->getExpiryDate())->format(DATE_RFC850);
    }

    public function sendUpdatedLimitsEmail()
    {
        try {
            global $currentLanguage;
            if ($this->getLanguage() != $currentLanguage) {
                $tempLanguage = setLanguage($this->getLanguage());
            }
            $admin = User::getAdmin();
            $from = array($admin->getEmail(), $admin->getName());
            $to = array($this->getEmail(), $this->getName());
            $subject = __("edit_user_subject", [
                "app" => __("application_title")
            ]);
            $this->getLimits($devices, $contacts, $credits, $expiryDate);
            $body = __("edit_user_email_body", [
                "app" => __("application_title"),
                "user" => htmlentities($this->getName(), ENT_QUOTES),
                "admin" => htmlentities($admin->getName(), ENT_QUOTES),
                "adminEmail" => $admin->getEmail(),
                "credits" => $credits,
                "expiryDate" => $expiryDate,
                "devices" => $devices,
                "contacts" => $contacts,
            ]);
            if (!empty($tempLanguage)) {
                setLanguage($currentLanguage);
            }
            sendEmail($from, $to, $subject, $body);
        } catch (Exception $e) {
        }
    }

    /**
     * @param array $numbers
     * @return array
     * @throws Exception
     */
    public function getContacts(array $numbers): array
    {
        $contacts = [];
        if (!empty($numbers)) {
            $contactObjects = Contact::where("ContactsList.userID", $this->getID())->where('number', $numbers, 'IN')->read_all();
            foreach ($contactObjects as $contactObject) {
                if (!empty($contactObject->getName())) {
                    if (!isset($contacts[$contactObject->getNumber()])) {
                        $contacts[$contactObject->getNumber()] = $contactObject->getName();
                    }
                }
            }
        }
        return $contacts;
    }

    /**
     * @param string $fieldName
     * @param string[] $allowedExtensions
     * @return array
     * @throws Exception
     */
    public function upload(string $fieldName, array $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'aac', '3gp', 'amr', 'mp3', 'm4a', 'wav', 'mp4', 'txt', 'vcf', 'html']): array
    {
        $attachments = [];
        if (isset($_FILES[$fieldName]['name'])) {
            $total = count($_FILES[$fieldName]['name']);
            for ($i = 0; $i < $total; $i++) {
                $tempPath = $_FILES[$fieldName]['tmp_name'][$i];
                if (is_uploaded_file($tempPath)) {
                    $file = $_FILES[$fieldName]['name'][$i];
                    $filename = random_str(4) . "-" . basename($file);
                    $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        throw new Exception(__("error_blocked_file_extension"));
                    }
                    $uploadDirectory = __DIR__ . "/../uploads/{$this->getID()}";
                    if (is_dir($uploadDirectory) || mkdir($uploadDirectory, 0755, true)) {
                        if (move_uploaded_file($tempPath, "{$uploadDirectory}/{$filename}")) {
                            $encodedFileName = rawurlencode($filename);
                            $attachments[] = "uploads/{$this->getID()}/{$encodedFileName}";
                        } else {
                            throw new Exception(__("error_uploading_attachment"));
                        }
                    } else {
                        throw new Exception(__("error_creating_upload_directory"));
                    }
                }
            }
        }
        return $attachments;
    }

    /**
     * @param string $type
     * @param mixed $data
     */
    public function callWebhook(string $type, $data)
    {
        if (empty($this->getWebHook())) {
            return;
        }

        $client = new Client();
        $payload = json_encode($data);
        $signature = base64_encode(hash_hmac('sha256', $payload, $this->getApiKey(), true));
        try {
            $client->request('POST', $this->getWebHook(), ['form_params' => [$type => $payload], 'headers' => ['X-SG-Signature' => $signature], 'verify' => false]);
        } catch (GuzzleException $t) {
            error_log($t->getMessage());
        }
    }

    /**
     * @param string $value
     * @throws Exception
     */
    public static function isValidDelay(string $value)
    {
        $delays = explode('-', $value);
        if (count($delays) <= 2) {
            foreach ($delays as $delay) {
                if (ctype_digit($delay)) {
                    if ($delay < 0 || $delay > 120) {
                        throw new Exception(__("error_delay_limit"));
                    }
                } else {
                    throw new Exception(__("error_delay_not_numeric"));
                }
            }
            if (count($delays) === 2) {
                if ($delays[1] < $delays[0]) {
                    throw new Exception(__("error_max_delay_smaller"));
                }
            }
        } else {
            throw new Exception(__("error_invalid_delay"));
        }
    }

    /**
     * @throws Exception
     */
    public function assignSharedDevices()
    {
        $sharedDevices = Device::where('sharedToAll', 1)->read_all();
        $deviceUsers = [];
        foreach ($sharedDevices as $sharedDevice) {
            $deviceUsers[] = [
                'deviceID' => $sharedDevice->getID(),
                'userID' => $this->getID()
            ];
        }
        DeviceUser::insertMultiple($deviceUsers, ['active' => 1]);
    }

    public function __toString()
    {
        $name = htmlentities($this->getName(), ENT_QUOTES);
        return "{$name} ({$this->getEmail()})";
    }

    public function depleteCredits(int $count): void
    {
        if (is_null($this->getCredits())) {
            return;
        }
        MysqliDb::getInstance()->rawQuery("UPDATE User SET credits = credits - {$count} WHERE ID = {$this->getID()}");
        $this->setCredits($this->getCredits() - $count);
    }
}