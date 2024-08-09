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

    public function getAll()
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

    public function getById($idP)
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

    public function deleteById($idP)
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

    //---------------------------------------------Metodos de creacion y modificacion de tablas---------------------------------------------------//

    public function updateById($idP, $data)
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

        $existeEnTabla = $this->getById($id);

        if ($existeEnTabla) {
            return $this->updateById($id, $data);
        } else {
            return $this->insert($data);
        }
    }

    //---------------------------------------------------------------------------------------------------------//

    //------------------------------------ metodos de paginacion----------------------------------------------//
    public function paginacion($page, $limit)
    {
        try {
            $offSet = ($page - 1) * $limit;
            // obtener la cantidad de rows
            $res = mysqli_query($this->bd, "SELECT COUNT(*) FROM {$this->table}");
            $row = mysqli_fetch_array($res);
            $rows = $row[0];

            $pages = ceil($rows / $limit);

            $consulta = "SELECT * FROM {$this->table} LIMIT {$offSet},{$limit}";
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
                    return [
                        'data' => $registro,
                        'page' => $page,
                        'limit' => $limit,
                        'pages' => $pages,
                        'rows' => $rows,
                    ];
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
    /**
     * Muestra segun:
     * @param int $page es la pagina en la que se encuentra.
     * @param int $limit es la cantidad de elementos que se van a mostrar por pagina.
     * @param string $columns te permite especificar qué columnas deseas seleccionar en la consulta SQL. Por defecto, se seleccionan todas las columnas ('*').
     * @param string $conditions es el parametro que puedes usar para especificar las condiciones que deseas aplicar a la consulta SQL, el cual por defecto esta vacio para mostrar todo el contenido de la tabla si asi se quiere. 
     * Ejemplo de uso:
     *  $page = 1;
     *  $limit = 10;
     *  $contents = 'nombre, edad'; // Selecciona solo las columnas 'nombre' y 'edad'
     *  $conditions = 'WHERE edad > 25'; // Filtra los registros donde 'edad' es mayor de 25
     *
     *  $resultado = $yourObject->paginationWithConditions($page, $limit, $contents, $conditions);
     */

    public function paginationWithConditions($page, $limit, $columns = '*', $conditions = "")
    {
        try {
            $offSet = ($page - 1) * $limit;

            // Obtener la cantidad de filas con condiciones aplicadas
            $countQuery = "SELECT COUNT(*) FROM {$this->table} {$conditions}";
            $res = mysqli_query($this->bd, $countQuery);
            $row = mysqli_fetch_array($res);
            $rows = $row[0];

            $pages = ceil($rows / $limit);

            $consulta = "SELECT {$columns} FROM {$this->table} {$conditions} LIMIT {$offSet}, {$limit}";
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
                    return [
                        'data' => $registro,
                        'page' => $page,
                        'limit' => $limit,
                        'pages' => $pages,
                        'rows' => $rows,
                    ];
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

    //---------------------------------------------------------------------------------------------------------//

    //-----------------------------verifica si existe en una columna un dato especifico----------------------------//

    public function exists($col, $colData)
    {
        try {
            $consulta = "SELECT * FROM {$this->table} WHERE $col = ?";
            $stmt = mysqli_prepare($this->bd, $consulta);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $colData);

                if (mysqli_stmt_execute($stmt)) {
                    $resultado = mysqli_stmt_get_result($stmt);
                    $registro = mysqli_fetch_assoc($resultado);

                    // Verifica si se encontró algún registro en la base de datos
                    if ($registro) {
                        return true;
                    } else {
                        return false;
                    }
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

    //---------------------------------------------------------------------------------------------------------//

    //--------------------------------------- Busqueda de registros relacionados entre tablas ------------------------------------------------//


    public function busquedaForanea($tabla, $idTabla, $idForanea)
    {
        try {
            $consulta = "SELECT {$this->table}.* FROM {$this->table} INNER JOIN {$tabla} ON  {$this->table}.{$idForanea} = {$tabla}.{$idTabla}";
            $stmt = mysqli_prepare($this->bd, $consulta);
            if ($stmt) {
                if (mysqli_stmt_execute($stmt)) {
                    $resultado = mysqli_stmt_get_result($stmt);
                    $registros = [];

                    while ($row = mysqli_fetch_assoc($resultado)) {
                        $registros[] = $row;
                    }

                    return $registros;
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

    /**
     * Busca registros relacionados en la tabla principal mediante una clave foránea en la tabla especificada.
     *
     * @param string $tabla Nombre de la tabla foránea.
     * @param string $idTabla Nombre de la columna que se utiliza como clave primaria en la tabla foránea.
     * @param string $idForanea Nombre de la columna que se utiliza como clave foránea en la tabla principal.
     * @param int $idBuscada Valor que se busca en la tabla foránea.
     *
     * @return array|false Un array de registros relacionados o false si hay un error.
     */

    public function buscarRegistrosRelacionados($tabla, $idTabla, $idForanea, $idBuscada)
    {
        try {
            $consulta = "SELECT {$this->table}.* FROM {$this->table} INNER JOIN {$tabla} ON {$this->table}.{$idForanea} = {$tabla}.{$idTabla} WHERE {$tabla}.{$idTabla} = ?";
            $stmt = mysqli_prepare($this->bd, $consulta);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $idBuscada);

                if (mysqli_stmt_execute($stmt)) {
                    $resultado = mysqli_stmt_get_result($stmt);
                    $registros = [];

                    while ($row = mysqli_fetch_assoc($resultado)) {
                        $registros[] = $row;
                    }

                    return $registros;
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

    //---------------------------------------------------------------------------------------------------------//
    //------------------------------------------------------------------------------------------------------------------------------------------------------------------//

}
