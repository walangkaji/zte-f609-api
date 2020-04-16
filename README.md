# ZTE F609 UNOFFICIAL API

Library ini merupakan **Emboh**. TITIK

# Support me
- ![Paypal](https://raw.githubusercontent.com/walangkaji/emboh/master/img/paypal.png) Paypal: [Se-Ikhlasnya Saja](https://www.paypal.me/walangkaji)


## Install
#### Composer
```sh
$ composer require walangkaji/zte-f609-api
```
#### Clone
```sh
$ git clone https://github.com/walangkaji/zte-f609-api.git
$ cd zte-f609-api/
$ composer install
```

## Cara Pakai
```php
require 'vendor/autoload.php';

use walangkaji\ZteF609\ZteApi;

$ipModem  = '192.168.1.1';
$username = 'admin';
$password = 'password';
$debug    = false;

$zteF609  = new ZteApi($ipModem, $username, $password, $debug);
$login    = $zteF609->login();

var_dump($login);
// Terusno dewe

```
Jika pengen menggunakan proxy:
```php
$ipModem  = '192.168.1.1';
$username = 'admin';
$password = 'password';
$debug    = false;
$proxy    = 'xxx.xxx.xxx.xxx:xxxx'

$zteF609  = new ZteApi($ipModem, $username, $password, $debug, $proxy);
$login    = $zteF609->login();

var_dump($login);
// Terusno dewe

```

### Contoh Untuk Reboot / Restart modem

```php
$zteF609  = new ZteApi($ipModem, $username, $password);
$login    = $zteF609->login();

if (!$login) {
    echo 'Login gagal' . PHP_EOL;
    exit();
}

$reboot = $zteF609->reboot();
if ($reboot) {
    echo 'Berhasil reboot modem.' . PHP_EOL;
}
```
### Contoh Untuk Get Device Information

```php
$zteF609  = new ZteApi($ipModem, $username, $password);
$login    = $zteF609->login();
$info     = $zteF609->status->deviceInformation();

var_dump($info);
```
### Contoh Untuk Pon Information

```php
$zteF609  = new ZteApi($ipModem, $username, $password);
$login    = $zteF609->login();
$info     = $zteF609->status->NetworkInterface->ponInformation();

var_dump($info);
```

## Available Methods
```php
$zteF609->login();
$zteF609->reboot();
$zteF609->status->deviceInformation();
$zteF609->status->voIpStatus();
$zteF609->status->NetworkInterface->wanConnection();
$zteF609->status->NetworkInterface->wanConnection3Gor4G();
$zteF609->status->NetworkInterface->tunnelConnection4in6();
$zteF609->status->NetworkInterface->tunnelConnection6in4();
$zteF609->status->NetworkInterface->ponInformation();
$zteF609->status->NetworkInterface->mobileNetwork();
$zteF609->status->UserInterface->wlan();
$zteF609->status->UserInterface->ethernet();
$zteF609->status->UserInterface->usb();
```

## TODO
Masih buuuwanyak fitur yang bisa di masukkan. Nanti sambil nangis dikerjakan.
Pada dasarnya ini dibuat karena kebutuhan. Nek ra butuh yo ra dibuat.
Apabila kalian pengen nambahin ya monggo dengan senang hati akan diterima.


Cukup sekian dan Matursuwun.

Jangan lupa kalo mau support seikhlasnya bisa lewat sini:
- ![Paypal](https://raw.githubusercontent.com/walangkaji/emboh/master/img/paypal.png) Paypal: [Se-Ikhlasnya Saja](https://www.paypal.me/walangkaji)