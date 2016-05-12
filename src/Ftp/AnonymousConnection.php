<?php

namespace Buuum\Ftp;

class AnonymousConnection extends Connection
{

    public function __construct($host, $port = 21, $timeout = 90, $passive = false)
    {
        parent::__construct($host, 'anonymous', 'guest', $port, $timeout, $passive);
    }

}