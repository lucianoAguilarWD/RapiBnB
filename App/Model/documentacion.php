<?php
class Documentacion extends Orm
{
    public function __construct($connect)
    {
        parent::__construct('certificacionID', 'certificacion', $connect);
    }
}
