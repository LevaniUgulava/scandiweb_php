<?php

class Db
{
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'scandiweb';
    private $connection = null;

    public function __construct()
    {
        $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->dbname);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }


    public function getConnection()
    {
        return $this->connection;
    }

    public function closeConnection()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }
    public function CheckSku($sku)
    {
        $connection = $this->getConnection();
        $sql = "SELECT COUNT(*) as count FROM Product WHERE sku = ?";

        if ($stmt = $connection->prepare($sql)) {
            $stmt->bind_param("s", $sku);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                if ($row['count'] > 0) {
                    return false;
                } else {
                    return true;
                }
            } else {
                echo "Error executing statement: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $connection->error;
        }

        $this->closeConnection();
        return false;
    }
}
