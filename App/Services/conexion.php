<?php
class Conexion
{
    private $conexion;

    public function __construct()
    {

        try {
            $this->conexion = mysqli_connect("localhost", "root", "", "rapibnb_test");
        } catch (Exception $error) {
            echo $error->getMessage();
        }
    }

    public function getConexion()
    {
        return $this->conexion;
    }

    public function cerrarConexion()
    {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }

    public function __destruct()
    {
        $this->cerrarConexion();
    }
}
