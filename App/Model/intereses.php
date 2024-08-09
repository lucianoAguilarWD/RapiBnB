<?php
class intereses extends Orm
{
    public function __construct($connect)
    {
        parent::__construct('interesID', 'interes', $connect);
    }
}
