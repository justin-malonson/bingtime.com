<?php

use GuzzleHttp\Exception\GuzzleException;

class Payment extends Entity
{
    public $subscriptionID;

    public $amount;

    public $transactionFee;

    public $currency;

    public $dateAdded;

    public $userID;

    public $status;

    public $transactionID;

    public $subscription;

    public static $relations = [
        "Subscription" => ["ID", "subscriptionID"]
    ];

    public function __construct()
    {
        $this->subscription = new Subscription();
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
     * @return int
     */
    public function getSubscriptionID(): int
    {
        return $this->subscriptionID;
    }

    /**
     * @param int $subscriptionID
     */
    public function setSubscriptionID(int $subscriptionID)
    {
        $this->subscriptionID = $subscriptionID;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function getTransactionFee(): float
    {
        return $this->transactionFee;
    }

    /**
     * @param float $transactionFee
     */
    public function setTransactionFee(float $transactionFee)
    {
        $this->transactionFee = round($transactionFee);
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
     * @return string
     */
    public function getTransactionID(): string
    {
        return $this->transactionID;
    }

    /**
     * @param string $transactionID
     */
    public function setTransactionID(string $transactionID)
    {
        $this->transactionID = $transactionID;
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
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return Subscription
     */
    public function getSubscription(): Subscription
    {
        return $this->subscription;
    }

    /**
     * @param Subscription $subscription
     */
    public function setSubscription(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * @throws Exception|GuzzleException
     */
    public function refund()
    {
        if ($this->getSubscription()->getPaymentMethod() === "PayPal") {
            PayPal::refundPayment($this->getTransactionID());
        }
        $this->setStatus("REFUNDED");
        $this->save();
    }
}