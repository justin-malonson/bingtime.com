<?php

class Plan extends Entity
{
    public $name;

    public $devices;

    public $contacts;

    public $credits;

    public $price;

    public $currency;

    public $frequency;

    public $frequencyUnit;

    public $totalCycles;

    public $paypalPlanID;

    public $enabled;

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
     * @return float
     */
    public function getPrice(): float
    {
        return (int)$this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price)
    {
        $this->price = $price;
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
     * @return int
     */
    public function getFrequency(): int
    {
        return (int)$this->frequency;
    }

    /**
     * @param int $frequency
     */
    public function setFrequency(int $frequency)
    {
        $this->frequency = $frequency;
    }

    /**
     * @return string
     */
    public function getFrequencyUnit(): string
    {
        return $this->frequencyUnit;
    }

    /**
     * @param string $frequencyUnit
     */
    public function setFrequencyUnit(string $frequencyUnit)
    {
        $this->frequencyUnit = $frequencyUnit;
    }

    /**
     * @return int
     */
    public function getDevices(): ?int
    {
        if (isset($this->devices)) {
            return $this->devices;
        }
        return null;
    }

    /**
     * @param int|null $devices
     */
    public function setDevices(?int $devices)
    {
        $this->devices = $devices;
    }

    /**
     * @return int|null
     */
    public function getContacts(): ?int
    {
        if (isset($this->contacts)) {
            return (int)$this->contacts;
        }
        return null;
    }

    /**
     * @param int|null $contacts
     */
    public function setContacts(?int $contacts)
    {
        $this->contacts = $contacts;
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
     * @return int
     */
    public function getTotalCycles(): int
    {
        return $this->totalCycles;
    }

    /**
     * @param int $totalCycles
     */
    public function setTotalCycles(int $totalCycles)
    {
        $this->totalCycles = $totalCycles;
    }

    /**
     * @return string
     */
    public function getPaypalPlanID(): ?string
    {
        return $this->paypalPlanID;
    }

    /**
     * @param string $paypalPlanID
     */
    public function setPaypalPlanID(string $paypalPlanID)
    {
        $this->paypalPlanID = $paypalPlanID;
    }

    /**
     * @return int
     */
    public function getEnabled(): int
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;
    }

    public function getFrequencyInSeconds(): int
    {
        return PayPal::getSecondsFromCycle($this->getFrequency(), $this->getFrequencyUnit());
    }
}