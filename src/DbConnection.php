<?php

namespace SashaMart\CartDiscount;

class DbConnection
{
    private static $_instance = null;
    private $conn;

    private function __construct()
    {
        try {
            $this->conn = new \SQLite3('cart.sqlite3');

            $this->conn->exec("CREATE TABLE IF NOT EXISTS orders(
                                      id  INTEGER PRIMARY KEY AUTOINCREMENT, 
                                      items TEXT,
                                      user_id INT,
                                      sum DECIMAL(10, 2),
                                      sum_with_discount DECIMAL(10, 2))");
        } catch (\PDOException $e) {
            // Print PDOException message
            echo $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if(!self::$_instance)
        {
            self::$_instance = new DbConnection();
        }

        return self::$_instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}