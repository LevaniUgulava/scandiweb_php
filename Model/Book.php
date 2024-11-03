<?php

namespace Model;

use Abstract\Product;
use Db;

require_once 'Abstract/Product.php';
require_once "Db.php";

class Book extends Product
{
    private $weight;

    public function __construct($sku, $name, $price, $type, $weight)
    {
        parent::__construct($sku, $name, $price, $type);
        $this->setWeight($weight);
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
    }
    public function getWeight()
    {
        return $this->weight;
    }


    public function save()
    {
        $db = new Db();
        $connection = $db->getConnection();

        $jsonWeight = json_encode(['weight' => $this->weight]);

        $sql = "INSERT INTO Product (sku, name, price,type,details) VALUES (?, ?, ?, ?,?)";

        if ($stmt = $connection->prepare($sql)) {
            $sku = $this->getSku();
            $name = $this->getName();
            $price = $this->getPrice();
            $type = $this->getType();
            $weight = $jsonWeight;

            $stmt->bind_param("ssdss", $sku, $name, $price, $type, $weight);
            if ($stmt->execute()) {
                echo "Book saved successfully!";
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
