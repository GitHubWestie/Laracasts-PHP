<?php

// Connect to the database and execute a query
class Database
{
    public $connection;

    public function __construct()
    {
        $DSN = "mysql:host=localhost;port=3306;user=<user>;password=<passowrd>!;dbname=beginner_php;charset=utf8mb4";

        $this->connection = new PDO($DSN);
    }

    public function query($query)
    {
        $statement = $this->connection->prepare($query);
        
        $statement->execute();
        
        return $statement;
    }
}