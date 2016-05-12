Library for PHP FTP functions
=============================

[![Packagist](https://img.shields.io/packagist/v/buuum/Ftp.svg?maxAge=2592000)](https://packagist.org/packages/buuum/ftp)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg?maxAge=2592000)](#license)

## Install

### System Requirements

You need PHP >= 5.5.0 to use Buuum\Ftp but the latest stable version of PHP is recommended.

### Composer

Buuum\Ftp is available on Packagist and can be installed using Composer:

```
composer require buuum/ftp
```

### Manually

You may use your own autoloader as long as it follows PSR-0 or PSR-4 standards. Just put src directory contents in your vendor directory.

##  INITIALIZE

```php
$host = 'my__host';
$username = 'my_username';
$password = 'my_pass';

$connection = new Connection($host, $username, $password);
//$connection = new AnonymousConnection($host);
//$connection = new SSLConnection($host, $username, $password);
try {
    $connection->open();
} catch (\Exception $e) {
    echo $e->getMessage();
}

$ftp = new FtpWrapper($connection);
```

## Close Connection
```php
$connection->close();
```

## FTP WRAPPER
* get($localFile, $remoteFile)
* put($remoteFile, $localFile)
* makedir($path)
* removedir($path)
* nlist($directory = false)
* rawlist($directory = false, $recursive = false)
* rename($oldname, $newname)
* delete($path)

###  GET
automatic create local dir recursive
```php
$localFile = 'demofile.txt';
$remoteFile = 'folder/demofile.txt'
$ftp->get($localFile, $remoteFile);
```

###  PUT
automatic create dir in ftp recursive
```php
$ftp->put($remoteFile, $localFile);
```

###  MAKEDIR
automatic create dir in ftp recursive
```php
$path = 'folder/subfolder1/subfolder2';
$ftp->makedir($path);
```

###  REMOVEDIR
remove dir recursive
```php
$path = 'folder';
$ftp->removedir($path);
```

###  NLIST
```php
$list = $ftp->nlist($path);
```
output
```php
array(7) {
  [0]=>
  string(1) "."
  [1]=>
  string(2) ".."
  [2]=>
  string(4) "demo"
  [3]=>
  string(9) "demo1.txt"
  [4]=>
  string(9) "demo2.txt"
  [5]=>
  string(7) "folder"
}
```

###  RAWLIST
```php
$list = $ftp->rawlist($path, $recursive);
```
output
```php
array(48) {
  [0]=>
  string(63) "drwxr-xr-x    4 user      user            4096 May 12 05:53 ."
  [1]=>
  string(64) "drwxr-xr-x    4 user      user            4096 May 12 05:53 .."
  [2]=>
  string(66) "drwxr-xr-x    2 user      user            4096 May 12 05:56 demo"
  [3]=>
  string(71) "-rw-r--r--    1 user      user            1544 May 12 05:07 demo1.txt"
  [4]=>
  string(71) "-rw-r--r--    1 user      user            1544 May 12 05:07 demo2.txt"
  [5]=>
  string(69) "drwxr-xr-x    3 user      user            4096 May 12 05:07 folder"
  [6]=>
  string(0) ""
  ....
```

###  RENAME
```php
$oldname = 'folder/subfolder/filetochange.txt';
$newname = 'folder/subfolder/filechange.txt';
$ftp->rename($oldname, $newname);
```

###  DELETE
```php
$path_file = 'folder/subfolder/filechange.txt';
$ftp->delete($path_file);
```


## LICENSE

The MIT License (MIT)

Copyright (c) 2016

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.