<?php
class AplicaOferta extends Orm
{
    public function __construct($connect)
    {
        parent::__construct('aplicacionID', 'aplicacion_a_oferta_alquiler', $connect);
    }
}
