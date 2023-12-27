<?php

use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;

class DeviceUser extends Entity
{
    public $name;

    /**
     * @var integer
     */
    public $deviceID;

    /**
     * @var integer
     */
    public $userID;

    /**
     * @var boolean
     */
    public $active;

    /**
     * @var Device
     */
    public $device;

    /**
     * @var User
     */
    public $user;

    public static $relations = [
        "Device" => ["ID", "deviceID"],
        "User" => ["ID", "userID"]
    ];

    public function __construct()
    {
        $this->device = new Device();
        $this->user = new User();
    }

    /**
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param ?string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getDeviceID(): int
    {
        return $this->deviceID;
    }

    /**
     * @param int $deviceID
     */
    public function setDeviceID(int $deviceID): void
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
    public function setUserID(int $userID): void
    {
        $this->userID = $userID;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return Device
     */
    public function getDevice(): Device
    {
        return $this->device;
    }

    /**
     * @param Device $device
     */
    public function setDevice(Device $device): void
    {
        $this->device = $device;
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

    public function __toString()
    {
        return empty($this->getName()) ? "{$this->getDevice()->getModel()} [{$this->getDeviceID()}]" : "{$this->getName()} [{$this->getDeviceID()}]";
    }

    /**
     * @param int $deviceID
     * @param int $userID
     * @return bool|DeviceUser
     * @throws Exception
     */
    public static function getById(int $deviceID, int $userID)
    {
        $deviceUser = new DeviceUser();
        $deviceUser->setDeviceID($deviceID);
        $deviceUser->setUserID($userID);
        return $deviceUser->read();
    }

    /**
     * @param int $userID
     * @param bool $enable
     * @throws Exception
     */
    public static function toggleDemoDevices(int $userID, bool $enable = false) {
        DeviceUser::where('DeviceUser.userID', $userID)
            ->where('Device.sharedToAll', 2)
            ->update_all(['active' => $enable ? 1 : 0], true);
    }

    /**
     * @param string $request
     * @param int $userID
     * @param int $deviceID
     * @param int|null $simSlot
     * @return Ussd
     * @throws Exception
     */
    public static function initiateUssdRequest(string $request, int $userID, int $deviceID, ?int $simSlot = null): Ussd
    {
        $deviceUser = User::getDeviceUser($deviceID, $userID);
        if ($deviceUser->getDevice()->getUserID() == $userID) {
            if ($simSlot != null) {
                $sim = Sim::where('Sim.deviceID', $deviceID)
                    ->where('Sim.slot', $simSlot)
                    ->read();
                if (!$sim) {
                    throw new InvalidArgumentException(__("error_sim_not_exist", ["simSlot" => $_POST["sim"], "deviceId" => $_POST["device"]]));
                }
            }
        } else {
            throw new Exception(__("error_device_not_owned"));
        }

        if ($deviceUser->getUser()->getExpiryDate() !== null && new DateTime($deviceUser->getUser()->getExpiryDate()) < new DateTime()) {
            throw new Exception(__("error_subscription_expired"));
        }

        if ($deviceUser->getUser()->getCredits() != null && $deviceUser->getUser()->getCredits() < 1) {
            throw new Exception(__("error_credits_depleted"));
        }

        return $deviceUser->sendUssdRequest($request, $simSlot);
    }

    /**
     * @param string $request
     * @param int|null $simSlot
     * @return Ussd
     * @throws Exception
     */
    public function sendUssdRequest(string $request, ?int $simSlot = null): Ussd
    {
        MysqliDb::getInstance()->startTransaction();
        $ussd = new Ussd();
        $ussd->setRequest($request);
        $ussd->setSentDate(date("Y-m-d H:i:s"));
        $ussd->setUserID($this->getUserID());
        $ussd->setDeviceID($this->getDeviceID());
        $ussd->setSimSlot($simSlot);
        $ussd->save();
        $data = [
            "ussdId" => $ussd->getID(),
            "ussdRequest" => $request,
            'delay' => $this->getUser()->getUssdDelay()
        ];
        if ($simSlot != null) {
            $data["simSlot"] = $simSlot;
        }
        try {
            if (!empty($this->getDevice()->getToken()) && !empty(Setting::get('firebase_service_account_json'))) {
                $this->getDevice()->sendPushNotification($data);
            }
            $this->getUser()?->depleteCredits(1);
            MysqliDb::getInstance()->commit();
            return $ussd;
        } catch (MessagingException|FirebaseException $e) {
            MysqliDb::getInstance()->rollback();
            $error = json_decode($e->getMessage())?->error ?? $e->getMessage();
            if (str_contains($error, $this->getDevice()->getToken())) {
                $this->getDevice()->setEnabled(0);
                $this->getDevice()->save();
                $error = $this->getDevice() . " : NotRegistered";
            } else {
                $error = $this->getDevice() . " : $error";
            }
            throw new Exception($error);
        }
    }
}