<?php

namespace Abstract;

abstract class Product
{
    private $sku;
    private $name;
    private $price;
    private $type;

    public function __construct($sku, $name, $price, $type)
    {
        $this->setSku($sku);
        $this->setName($name);
        $this->setPrice($price);
        $this->setType($type);
    }

    abstract public function save();

    public function setSku($sku)
    {
        $this->sku = $sku;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getSku()
    {
        return $this->sku;
    }
    public function getName()
    {
        return  $this->name;
    }
    public function getPrice()
    {
        return $this->price;
    }

    public function getType()
    {
        return  $this->type;
    }
    public function setType($type)
    {
        $this->type = $type;
    }
}
