# ZTE F609 UNOFFICIAL API

Library ini merupakan emboh. TITIK

----------
# Support me
- ![Paypal](https://raw.githubusercontent.com/reek/anti-adblock-killer/gh-pages/images/paypal.png) Paypal: [Se-Ikhlasnya Saja](https://www.paypal.me/walangkaji)
----------

### Cara Pakai

```php
require_once 'ZTEF609.php';

$ipModem  = '192.168.1.1';
$username = 'admin';
$password = 'password';
$debug    = false;

$zteF609  = new ZTEF609($ipModem, $username, $password, $debug);
$login    = $GTrack->login();

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

$zteF609  = new ZTEF609($ipModem, $username, $password, $debug, $proxy);
$login    = $GTrack->login();

var_dump($login);
// Terusno dewe

```

### Contoh Untuk Reboot / Restart modem

```php
$ipModem  = '192.168.1.1';
$username = 'admin';
$password = 'password';

$zteF609  = new ZTEF609($ipModem, $username, $password);
$login    = $GTrack->login();

if (!$login) {
    echo 'Login gagal' . PHP_EOL;
    exit();
}

$reboot = $zteF609->reboot();
if ($reboot) {
    echo 'Berhasil reboot modem.' . PHP_EOL;
}

```

### Available Methods
```php
ZTEF609::login()
ZTEF609::reboot()
```

### TODO
Masih buuuwanyak fitur yang bisa di masukkan. Nanti sambil nangis dikerjakan.
Pada dasarnya ini dibuat karena kebutuhan. Nek ra butuh yo ra dibuat.
Apabila kalian pengen nambahin ya monggo dengan senang hati akan diterima.


Cukup sekian dan Matursuwun.

Jangan lupa kalo mau support seikhlasnya bisa lewat sini:
- ![Paypal](https://raw.githubusercontent.com/reek/anti-adblock-killer/gh-pages/images/paypal.png) Paypal: [Se-Ikhlasnya Saja](https://www.paypal.me/walangkaji)