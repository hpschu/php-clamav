<?php
function bootstrap($dir, &$res = array()) {
    $files = scandir($dir);
    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            if (str_ends_with($path, '.php')) require_once($path);
            $res[] = $path;
        } else if ($value != "." && $value != "..") {
            bootstrap($path, $res);
            $res[] = $path;
        }
    }
    return $res;
}