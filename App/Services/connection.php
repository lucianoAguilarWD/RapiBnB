<?php
class Connection
{
    private $connection;

    public function __construct($host = "localhost", $user = "root", $password = "", $dbname = "rapibnb_test")
    {
  
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->connection = new mysqli($host, $user, $password, $dbname);
            $this->connection->set_charset("utf8mb4");
        } catch (mysqli_sql_exception $error) {
            error_log("Error al conectar a la base de datos: " . $error->getMessage());
            throw $error;
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

    public function __destruct()
    {
        $this->closeConnection();
    }
}
