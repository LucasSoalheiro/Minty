<?php

// Caminho do arquivo requisitado
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Se o arquivo existir na pasta public, serve direto
if ($uri !== '/' && file_exists(__DIR__ . '/src' . $uri)) {
    return false;
}

// Caso contrário, manda tudo pro index.php
require __DIR__ . '/src/index.php';