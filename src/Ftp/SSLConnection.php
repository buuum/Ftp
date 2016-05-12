<?php

namespace Buuum\Ftp;


class SSLConnection extends Connection
{
    protected function doConnect()
    {
        return @ftp_ssl_connect($this->getHost(), $this->getPort(), $this->getTimeout());
    }
}