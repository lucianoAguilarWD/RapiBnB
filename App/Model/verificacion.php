<?php

class Verificacion extends Orm
{
    public function __construct($connect)
    {
        parent::__construct('verificacionID', 'verificacion_cuenta', $connect);
    }
}
