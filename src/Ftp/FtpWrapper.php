<?php

namespace Buuum\Ftp;

class FtpWrapper
{
    const ASCII = FTP_ASCII;
    const TEXT = FTP_TEXT;
    const BINARY = FTP_BINARY;
    const IMAGE = FTP_IMAGE;
    const TIMEOUT_SEC = FTP_TIMEOUT_SEC;
    const AUTOSEEK = FTP_AUTOSEEK;
    const AUTORESUME = FTP_AUTORESUME;
    const FAILED = FTP_FAILED;
    const FINISHED = FTP_FINISHED;
    const MOREDATA = FTP_MOREDATA;

    /**
     * @var Connection
     */
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function get($localFile, $remoteFile, $mode = self::BINARY, $resumepos = 0)
    {
        $this->makeLocaldirs($localFile);
        return ftp_get($this->connection->getStream(), $localFile, $remoteFile, $mode, $resumepos);
    }

    public function put($remoteFile, $localFile, $mode = self::BINARY, $startpos = 0)
    {
        $this->makedirs($remoteFile);
        return ftp_put($this->connection->getStream(), $remoteFile, $localFile, $mode, $startpos);
    }

    protected function makeLocalDirs($path)
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    protected function makedirs($path)
    {
        if (strpos($path, '/') === false) {
            return true;
        }

        $parts = explode("/", $path);
        array_pop($parts);

        if ($this->chdir($this->connection->getRemotePath() . implode('/', $parts))) {
            $this->chdir($this->connection->getRemotePath());
            return true;
        }

        $fullpath = $this->connection->getRemotePath();
        foreach ($parts as $part) {
            $fullpath .= (substr($fullpath, -1) == '/') ? $part : '/' . $part;
            if (!$this->chdir($fullpath)) {
                $this->mkdir($part);
            }
            $this->chdir($part);
        }
        $this->chdir($this->connection->getRemotePath());
        return true;
    }

    public function chdir($directory)
    {
        return @ftp_chdir($this->connection->getStream(), $directory);
    }

    public function mkdir($directory)
    {
        return ftp_mkdir($this->connection->getStream(), $directory);
    }

    public function makedir($path)
    {
        $path = (substr($path, -1) !== '/') ? $path . '/' : $path;
        return $this->makedirs($path);
    }

    public function rmdir($directory)
    {
        return ftp_rmdir($this->connection->getStream(), $directory);
    }

    public function removedir($path)
    {
        $fullpath = $this->connection->getRemotePath();
        $fullpath .= (substr($fullpath, -1) == '/') ? $path : '/' . $path;
        if ($this->delete($path) === false) {
            if ($children = $this->nlist($path)) {
                foreach ($children as $p) {
                    if ($p != '.' && $p != '..') {
                        $this->removedir($fullpath . '/' . $p);
                    }
                }
            }
            $this->rmdir($path);
        }
    }

    public function nlist($directory = false)
    {
        $directory = ($directory)? $directory : $this->connection->getRemotePath();
        return ftp_nlist($this->connection->getStream(), $directory);
    }


    public function rawlist($directory = false, $recursive = false)
    {
        $directory = ($directory)? $directory : $this->connection->getRemotePath();
        return ftp_rawlist($this->connection->getStream(), $directory, $recursive);
    }

    public function pwd()
    {
        return ftp_pwd($this->connection->getStream());
    }

    public function chmod($mode, $filename)
    {
        return ftp_chmod($this->connection->getStream(), $mode, $filename);
    }

    public function rename($oldname, $newname)
    {
        return ftp_rename($this->connection->getStream(), $oldname, $newname);
    }

    public function delete($path)
    {
        return @ftp_delete($this->connection->getStream(), $path);
    }
}