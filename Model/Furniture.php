<?php

namespace Model;

use Abstract\Product;
use Db;

require_once 'Abstract/Product.php';
require_once 'Db.php';

class Furniture extends Product
{
    private $height;
    private $width;
    private $length; // Fixed spelling

    public function __construct($sku, $name, $price, $type, $height, $width, $length)
    {
        parent::__construct($sku, $name, $price, $type);
        $this->setHeight($height);
        $this->setWidth($width);
        $this->setLength($length);
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setLength($length)
    {
        $this->length = $length;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function save()
    {
        $db = new Db();
        $connection = $db->getConnection();

        $sql = "INSERT INTO Product (sku, name, price, type, details) VALUES (?, ?, ?, ?, ?)";

        $jsonDetail = json_encode([
            'height' => $this->getHeight(),
            'width' => $this->getWidth(),
            'length' => $this->getLength()
        ]);

        if ($stmt = $connection->prepare($sql)) {
            $sku = $this->getSku();
            $name = $this->getName();
            $price = $this->getPrice();
            $type = $this->getType();
            $details = $jsonDetail;

            // Bind parameters: s = string, d = double (for price), s = string
            $stmt->bind_param("ssdss", $sku, $name, $price, $type, $details);

            if ($stmt->execute()) {
                echo "Furniture saved successfully!";
            } else {
                echo "Error saving furniture: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $connection->error;
        }

        $db->closeConnection();
    }
}
