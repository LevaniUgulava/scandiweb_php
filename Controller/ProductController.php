<?php

namespace Controller;

use Db;
use Exception;
use Model\Book;
use Model\DVD;
use Model\Furniture;
use Validation\Validation;

require_once 'Model/Book.php';
require_once 'Model/DVD.php';
require_once 'Model/Furniture.php';
require_once "Validation.php";

class ProductController
{
    private static $typeMap = [
        'Book' => Book::class,
        'DVD' => DVD::class,
        'Furniture' => Furniture::class

    ];

    public function create($data)
    {
        $errors = Validation::validateInfo($data);

        if (!empty($errors)) {
            http_response_code(422);
            header("HTTP/1.1 422 Unprocessable Entity");
            header('Content-Type: application/json');
            echo json_encode(['errors' => $errors]);
            exit;
        }
        $db = new Db();
        $check = $db->CheckSku($data->sku);
        if ($check) {
            $type = $data->type;
            $className = self::$typeMap[$type] ?? null;

            if ($className === null) {
                throw new Exception("Invalid product type");
            }

            $product = new $className(
                $data->sku,
                $data->name,
                $data->price,
                $type,
                ...array_values((array)$data->details)
            );
            $product->save();
        } else {
            echo "Sku isnot unique";
        }
    }

    public function display()
    {
        $db = new Db();
        $connection = $db->getConnection();
        $sql = "SELECT * FROM Product";

        if ($stmt = $connection->prepare($sql)) {
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result === false) {
                    echo "Error retrieving result set: " . $connection->error;
                    return [];
                }
                $products = [];
                while ($row = $result->fetch_assoc()) {
                    $products[] = $row;
                }

                $stmt->close();
                $db->closeConnection();


                return $products;
            } else {
                echo "Error executing statement: " . $stmt->error;
            }
        } else {
            echo "Error preparing statement: " . $connection->error;
        }

        $db->closeConnection();
        return [];
    }

    public function massdelete($idarray)
    {
        $db = new Db();
        $connection = $db->getConnection();

        $sql = "DELETE FROM Product WHERE id = ?";
        $stmt = $connection->prepare($sql);

        if (!$stmt) {
            echo "Error preparing statement: " . $connection->error;
            return;
        }

        foreach ($idarray as $id) {
            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                echo "Error executing statement for ID $id: " . $stmt->error;
            }
        }

        $stmt->close();
        $db->closeConnection();
    }
}
