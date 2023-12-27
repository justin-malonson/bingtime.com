<?php

use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MessageTarget;

class Device extends Entity implements JsonSerializable
{
    public $androidID;

    public $token;

    public $model;

    public $androidVersion;

    public $appVersion;

    public $userID;

    public $enabled;

    public $sharedToAll;

    public $useOwnerSettings;

    private $user;

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
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @param string $model
     */
    public function setModel(string $model)
    {
        $this->model = $model;
    }

    /**
     * @return string|null
     */
    public function getAndroidVersion(): ?string
    {
        return $this->androidVersion;
    }

    /**
     * @param string $androidVersion
     */
    public function setAndroidVersion(string $androidVersion)
    {
        $this->androidVersion = $androidVersion;
    }

    /**
     * @return string|null
     */
    public function getAppVersion(): ?string
    {
        return $this->appVersion;
    }

    /**
     * @param string $appVersion
     */
    public function setAppVersion(string $appVersion)
    {
        $this->appVersion = $appVersion;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     */
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getAndroidID(): string
    {
        return $this->androidID;
    }

    /**
     * @param string $androidID
     */
    public function setAndroidID(string $androidID)
    {
        $this->androidID = $androidID;
    }

    /**
     * @return int
     */
    public function getEnabled(): int
    {
        return $this->enabled;
    }

    /**
     * @param int $enabled
     * @throws Exception
     */
    public function setEnabled(int $enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return int
     */
    public function getSharedToAll(): int
    {
        return $this->sharedToAll;
    }

    /**
     * @param int $sharedToAll
     */
    public function setSharedToAll(int $sharedToAll): void
    {
        $this->sharedToAll = $sharedToAll;
    }

    /**
     * @return int
     */
    public function getUseOwnerSettings(): int
    {
        return $this->useOwnerSettings;
    }

    /**
     * @param int $useOwnerSettings
     */
    public function setUseOwnerSettings(int $useOwnerSettings): void
    {
        $this->useOwnerSettings = $useOwnerSettings;
    }

    /**
     * @return User
     * @throws Exception
     */
    public function getUser(): User
    {
        if ($this->user == null) {
            $user = new User();
            $user->setID($this->getUserID());
            if ($user->read()) {
                $this->user = $user;
            }
        }
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function __toString()
    {
        $deviceUser = DeviceUser::getById($this->getID(), $this->getUserID());
        return empty($deviceUser->getName()) ? "{$this->getModel()} [{$this->getID()}]" : "{$deviceUser->getName()} [{$this->getID()}]";
    }

    /**
     * @param array $sims
     * @throws Exception
     */
    public function saveSims(array $sims)
    {
        if (!empty($sims)) {
            foreach ($sims as $sim) {
                $obj = new Sim();
                $obj->setSlot($sim->slot);
                $obj->setDeviceID($this->getID());
                if ($obj->read(false)) {
                    $sim->ID = $obj->getID();
                } else if (!$sim->enabled) {
                    continue;
                }
                $sim = Sim::fromObject($sim);
                $sim->setDeviceID($this->getID());
                $sim->save(false);
            }
        }
    }

    /**
     * @throws \Kreait\Firebase\Exception\MessagingException
     * @throws \Kreait\Firebase\Exception\FirebaseException
     * @throws \Exception
     */
    public function sendPushNotification(array $data = []): void
    {
        $messaging = (new Factory)
            ->withServiceAccount(Setting::get('firebase_service_account_json'))
            ->createMessaging();

        $config = AndroidConfig::new()->withHighMessagePriority();

        $message = CloudMessage::withTarget(MessageTarget::TOKEN, $this->getToken())
            ->withAndroidConfig($config)
            ->withData($data);

        $messaging->send($message, empty($data));
    }

    /**
     * @param array $groups
     * @param User|null $user
     * @throws Exception
     */
    public static function processRequests(array $groups, User $user = null): void
    {
        $errors = [];
        foreach ($groups as $group) {
            /** @var Device $device */
            $device = $group["device"];

            try {
                if (!empty($device->getToken()) && !empty(Setting::get('firebase_service_account_json'))) {
                    $device->sendPushNotification($group["data"]);
                }
                $user?->depleteCredits($group["count"]);
            } catch (MessagingException|FirebaseException $e) {
                $error = json_decode($e->getMessage())?->error ?? $e->getMessage();
                if (str_contains($error, $device->getToken())) {
                    $device->setEnabled(0);
                    $device->save();
                    $errors[] = $device . " : NotRegistered";
                } else {
                    $errors[] = $device . " : $error";
                }
            }
        }

        if (count($errors) > 0) {
            $error = "You have the following errors while sending messages.\n";
            $error .= "\n";
            foreach ($errors as $err) {
                $error .= "* {$err}\n";
            }
            throw new Exception($error);
        }
    }

    /**
     * @param int $id
     * @param int $userID
     * @return bool|Device
     * @throws Exception
     */
    public static function getById(int $id, int $userID)
    {
        $currentDevice = new Device();
        $currentDevice->setID($id);
        $currentDevice->setUserID($userID);
        return $currentDevice->read();
    }

    /**
     * @param string $string
     * @return bool|array
     */
    public static function isValidIdentifier(string $string)
    {
        $identifiers = explode('|', $string);
        if (count($identifiers) > 2) {
            return false;
        }
        foreach ($identifiers as $identifier) {
            if (!ctype_digit($identifier)) {
                return false;
            }
        }
        return $identifiers;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getUsers(): array
    {
        $deviceUsers = DeviceUser::where("deviceID", $this->getID())
            ->where("active", 1)
            ->read_all();
        $users = [];
        foreach ($deviceUsers as $deviceUser) {
            $users[] = $deviceUser->getUserID();
        }
        return $users;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     * @throws Exception
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}