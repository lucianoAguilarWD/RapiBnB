<?php
class OfertaAlquiler extends Orm
{
    public function __construct($connect)
    {
        parent::__construct('ofertaID', 'oferta_de_alquiler', $connect);
    }
}
