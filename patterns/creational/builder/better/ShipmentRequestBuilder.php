<?php

class ShipmentRequestBuilder
{

    private $recipientName;
    private $address;
    private $packages = 1;
    private $totalWeight = 1;

    public function setRecipientName($recipientName)
    {
        $this->recipientName = $recipientName;
        return $this;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function setPackages($packages)
    {
        $this->packages = $packages;
        return $this;
    }

    public function setTotalWeight($totalWeight)
    {
        $this->totalWeight = $totalWeight;
        return $this;
    }

    public function build()
    {
        $request = new ShipmentRequest();
        if (empty($this->recipientName)) {
            throw new RuntimeException('Empty recipient name');
        }
        $request->setRecipientName($this->recipientName);
        if (empty($this->address)) {
            throw new RuntimeException('Empty address');
        }
        $request->setAddress($this->address);
        $request->setPackages($this->packages);
        $request->setTotalWeight($this->totalWeight);

        return $request;
    }
}