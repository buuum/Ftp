<?php

namespace Buuum\Ftp;


class Connection
{
    protected $host;
    protected $port;
    protected $timeout;
    protected $username;
    protected $password;
    protected $stream;
    protected $connected = false;
    protected $passive = false;
    protected $remotePath;


    public function __construct($host, $username, $password, $port = 21, $timeout = 90, $passive = false)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->passive = $passive;
    }

    public function open()
    {
        if ($this->isConnected()) {
            throw new \Exception("Connection is established");
        }

        $stream = $this->doConnect();
        if (false === $stream) {
            throw new \Exception(sprintf("Could not connect to server %s:%s", $this->getHost(), $this->getPort()));
        }
        if (!@ftp_login($stream, $this->getUsername(), $this->getPassword())) {
            throw new \Exception(sprintf(
                "Could not login using combination of username (%s) and password (%s)",
                $this->getUsername(),
                preg_replace("/./", "*", $this->getPassword())
            ));
        }
        if (true === $this->passive) {
            if (false === ftp_pasv($stream, true)) {
                throw new \Exception("Cold not turn on passive mode");
            }
        }
        $this->connected = true;
        $this->stream = $stream;
        $this->remotePath = ftp_pwd($stream);
        return true;
    }

    protected function doConnect()
    {
        return @ftp_connect($this->getHost(), $this->getPort(), $this->getTimeout());
    }

    public function close()
    {
        if (!$this->isConnected()) {
            throw new \Exception("Tried to close an unitialized connection");
        }
        ftp_close($this->stream);
        $this->connected = false;
        return true;
    }

    public function isConnected()
    {
        return $this->connected;
    }

    public function getStream()
    {
        if (!$this->isConnected()) {
            throw new \Exception("Cannot get stream context. Connection is not established");
        }
        return $this->stream;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setHost($host)
    {
        if ($this->isConnected()) {
            throw new \Exception("Cannot set new host. Connection is established");
        }
        $this->host = $host;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        if ($this->isConnected()) {
            throw new \Exception("Cannot set new username. Connection is established");
        }
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        if ($this->isConnected()) {
            throw new \Exception("Cannot set new password. Connection is established");
        }
        $this->password = $password;
    }

    public function getRemotePath()
    {
        return $this->remotePath;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setPort($port)
    {
        if ($this->isConnected()) {
            throw new \Exception("Cannot set new port. Connection is established");
        }
        $this->port = $port;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }

    public function setTimeout($timeout)
    {
        if ($this->isConnected()) {
            throw new \Exception("Cannot set new timeout. Connection is established");
        }
        $this->timeout = $timeout;
    }

    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->close();
        }
    }

}