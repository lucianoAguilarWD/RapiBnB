<?php
class Usuario extends Orm
{

    public function __construct($connect)
    {
        parent::__construct('usuarioID', 'usuarios', $connect);
    }
}
