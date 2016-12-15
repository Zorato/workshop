<?php

class ShipmentRequest
{

    private $recipientName;
    private $address;
    private $packages;
    private $totalWeight;

    public function getRecipientName()
    {
        return $this->recipientName;
    }

    public function setRecipientName($recipientName)
    {
        $this->recipientName = $recipientName;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getPackages()
    {
        return $this->packages;
    }

    public function setPackages($packages)
    {
        $this->packages = $packages;
    }

    public function getTotalWeight()
    {
        return $this->totalWeight;
    }

    public function setTotalWeight($totalWeight)
    {
        $this->totalWeight = $totalWeight;
    }

}