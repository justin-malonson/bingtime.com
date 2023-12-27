<?php

use GuzzleHttp\Exception\GuzzleException;

class Subscription extends Entity
{
    public $planID;

    public $expiryDate;

    public $subscribedDate;

    public $cyclesCompleted;

    public $status;

    public $userID;

    public $subscriptionID;

    public $paymentMethod;

    public $plan;

    public $user;

    public static $relations = [
        "Plan" => ["ID", "planID"],
        "User" => ["ID", "userID"]
    ];

    public function __construct()
    {
        $this->plan = new Plan();
        $this->user = new User();
    }

    /**
     * @return int
     */
    public function getPlanID(): int
    {
        return $this->planID;
    }

    /**
     * @param int $planID
     */
    public function setPlanID(int $planID)
    {
        $this->planID = $planID;
    }

    /**
     * @return DateTime
     */
    public function getExpiryDate(): DateTime
    {
        return getDisplayTime($this->expiryDate);
    }

    /**
     * @param string $expiryDate
     */
    public function setExpiryDate(string $expiryDate)
    {
        $this->expiryDate = $expiryDate;
    }

    /**
     * @return DateTime
     */
    public function getSubscribedDate(): DateTime
    {
        return getDisplayTime($this->subscribedDate);
    }

    /**
     * @param string $subscribedDate
     */
    public function setSubscribedDate(string $subscribedDate)
    {
        $this->subscribedDate = $subscribedDate;
    }

    /**
     * @return int
     */
    public function getCyclesCompleted(): int
    {
        return (int)$this->cyclesCompleted;
    }

    /**
     * @param int $cyclesCompleted
     */
    public function setCyclesCompleted(int $cyclesCompleted)
    {
        $this->cyclesCompleted = $cyclesCompleted;
    }

    /**
     * @return string|null
     */
    public function getSubscriptionID(): ?string
    {
        return $this->subscriptionID;
    }

    /**
     * @param string|null $subscriptionID
     */
    public function setSubscriptionID(?string $subscriptionID)
    {
        $this->subscriptionID = $subscriptionID;
    }

    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethod
     */
    public function setPaymentMethod(string $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
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
     * @return Plan
     */
    public function getPlan(): Plan
    {
        return $this->plan;
    }

    /**
     * @param Plan $plan
     */
    public function setPlan(Plan $plan)
    {
        $this->plan = $plan;
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
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @throws Exception|GuzzleException
     */
    public function cancel(string $reason = "Don't need it anymore")
    {
        if ($this->getPaymentMethod() == "PayPal") {
            PayPal::cancelSubscription($this->getSubscriptionID(), $reason);
        }
        $this->setStatus("CANCELLED");
        $this->save();
    }

    /**
     * @param string $expiryDate
     * @throws Exception
     */
    public function renew(string $expiryDate)
    {
        $user = new User();
        $user->setID($this->getUserID());
        $user->read();
        $user->setCredits($this->getPlan()->getCredits());
        $user->changeDevicesLimit($this->getPlan()->getDevices());
        $user->changeContactLimit($this->getPlan()->getContacts());
        $user->setExpiryDate($expiryDate);
        $user->sendUpdatedLimitsEmail();
        $user->save(false);
    }
}