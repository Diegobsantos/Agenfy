<?php
$sitebase = 'http://localhost/Agenfy/';

define('APP_ROOT', __DIR__);

function site_base($path = '') {
    global $sitebase;
    return rtrim($sitebase, '/') . '/' . ltrim($path, '/');
}
?>



