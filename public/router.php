<?php

declare(strict_types=1);

if (php_sapi_name() == "cli-server") {
    if (preg_match('/\.(?:woff2?)(\?[a-zA-Z0-9]+)?$/', $_SERVER["REQUEST_URI"])) {
        return false;
    }
    if (preg_match('/\.(?:css|js|json|tiff|gif|png|jpe?g)(\?id=[a-zA-Z0-9]+)?$/', $_SERVER["REQUEST_URI"])) {
        return false;
    }
}

require_once 'index.php';
