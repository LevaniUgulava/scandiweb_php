<?php

namespace Model;

use Abstract\Product;
use Db;

require_once 'Abstract/Product.php';
require_once "Db.php";
class DVD extends Product
{
    private $size;

    public function __construct($sku, $name, $price, $type, $size)
    {
        parent::__construct($sku, $name, $price, $type);
        $this->setSize($size);
    }

    public function setSize($size)
    {
        $this->size = $size;
    }
    public function getSize()
    {
        return $this->size;
    }

    public function save()
    {

        $db = new Db();
        $connection = $db->getConnection();

        $sql = "INSERT INTO Product (sku,name,price,type,details) VALUES (?,?,?,?,?)";

        $Jsondetails = json_encode(['size' => $this->getSize()]);

        if ($stmt = $connection->prepare($sql)) {
            $sku = $this->getSku();
            $name = $this->getName();
            $price = $this->getPrice();
            $type = $this->getType();
            $details = $Jsondetails;

            $stmt->bind_param("ssdss", $sku, $name, $price, $type, $details);
            if ($stmt->execute()) {
                echo "DVD saved successfully!";
            } else {
                echo "Error saving book: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $connection->error;
        }
        $db->closeConnection();
    }
}
