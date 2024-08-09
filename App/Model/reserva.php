<?php
class Reserva extends Orm
{
    public function __construct($connect)
    {
        parent::__construct('reservaID', 'reserva', $connect);
    }
}
