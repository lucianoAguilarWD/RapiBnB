<?php
class Orm
{

    protected $id;
    protected $table;
    protected $bd;

    public function __construct($id, $table, $bd)
    {
        $this->id = $id;
        $this->table = $table;
        $this->bd = $bd;
    }


    //---------------------------------------------Metodos de CRUD---------------------------------------------------//

    public function findAll()
    {
        try {
            $consulta = "SELECT * FROM {$this->table}";
            $stmt = mysqli_prepare($this->bd, $consulta);
            if ($stmt) {
                if (mysqli_stmt_execute($stmt)) {
                    $resultado = mysqli_stmt_get_result($stmt);
                    $registro = [];
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        $registro[$i] = $row;
                        $i++;
                    }
                    return $registro;
                } else {
                    throw new Exception("Error al ejecutar la consulta:" . mysqli_error($this->bd));
                }
                mysqli_stmt_close($stmt);
            } else {
                throw new Exception("Error en la preparación de la consulta:" . mysqli_error($this->bd));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function find($idP)
    {
        try {
            $consulta = "SELECT * FROM {$this->table} WHERE {$this->id} = ?";
            $stmt = mysqli_prepare($this->bd, $consulta);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $idP);
                if (mysqli_stmt_execute($stmt)) {
                    $resultado = mysqli_stmt_get_result($stmt);
                    $registro = mysqli_fetch_assoc($resultado);
                    return $registro;
                } else {
                    throw new Exception("Error al ejecutar la consulta:" . mysqli_error($this->bd));
                }
                mysqli_stmt_close($stmt);
            } else {
                throw new Exception("Error en la preparación de la consulta:" . mysqli_error($this->bd));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete($idP)
    {
        try {
            $consulta = "DELETE FROM {$this->table} WHERE {$this->id} = ?";
            $stmt = mysqli_prepare($this->bd, $consulta);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $idP);
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Error al ejecutar la consulta:" . mysqli_error($this->bd));
                }
                mysqli_stmt_close($stmt);
            } else {
                throw new Exception("Error en la preparación de la consulta:" . mysqli_error($this->bd));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update($idP, $data)
    {
        try {
            // creamos un string para tomar cada dato que venga de data y asi transformarlo en un string que sirva para la consulta ej: nombre=?
            $setValues = '';
            // creamos un string para colocar los tipos que va a tener el bind param que vienen respectivamente del la data
            $types = '';
            foreach ($data as $key => $value) {
                $setValues .= $key . '=?, ';
                if (is_int($value)) {
                    $types .= 'i';
                } else {
                    $types .= 's';
                }
            }
            //quitamos la , que sobra en el string armado
            $setValues = rtrim($setValues, ", ");
            //agregamos el ultimo i que va con el id
            $types .= 'i';

            $consulta = "UPDATE {$this->table} SET $setValues WHERE {$this->id} = ?";
            $stmt = mysqli_prepare($this->bd, $consulta);
            if ($stmt) {
                //tomamos los valores del arreglo data para poder ponerlo de parametros
                $values = array_values($data);
                $values[] = $idP;
                mysqli_stmt_bind_param($stmt, $types, ...$values);
                if (mysqli_stmt_execute($stmt)) {
                    $mensaje = "Registro modificado con éxito";
                    return $mensaje;
                } else {
                    throw new Exception("Error al ejecutar la consulta:" . mysqli_error($this->bd));
                }
                mysqli_stmt_close($stmt);
            } else {
                throw new Exception("Error en la preparación de la consulta:" . mysqli_error($this->bd));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function insert($data)
    {
        try {
            $valuesTb = '';
            $types = '';
            $puntero = '';
            foreach ($data as $key => $value) {
                $valuesTb .= $key . ', ';
                $puntero .= '?, ';
                if (is_int($value)) {
                    $types .= 'i';
                } else {
                    $types .= 's';
                }
            }
            $valuesTb = rtrim($valuesTb, ', ');
            $puntero = rtrim($puntero, ', ');
            $consulta = "INSERT INTO {$this->table}($valuesTb) VALUES ($puntero)";
            $stmt = mysqli_prepare($this->bd, $consulta);
            if ($stmt) {
                $values = array_values($data);
                mysqli_stmt_bind_param($stmt, $types, ...$values);
                if (mysqli_stmt_execute($stmt)) {
                    $mensaje = "Registro agregado con éxito.";
                    return $mensaje;
                } else {
                    throw new Exception("Error al ejecutar la consulta: " . mysqli_error($this->bd));
                }
                mysqli_stmt_close($stmt);
            } else {
                throw new Exception("Error en la preparación de la consulta: " . mysqli_error($this->bd));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function upsert($id, $data)
    {

        $existeEnTabla = $this->find($id);

        if ($existeEnTabla) {
            return $this->update($id, $data);
        } else {
            return $this->insert($data);
        }
    }

    //---------------------------------------------------------------------------------------------------------//

}
