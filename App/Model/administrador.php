<?php
class Administrador extends Orm
{
    public function __construct($connect)
    {
        parent::__construct('administradorID', 'administrador', $connect);
    }
}
