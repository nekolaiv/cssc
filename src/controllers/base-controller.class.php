<?php
namespace Src\Controllers;

class BaseController {
    protected function render($viewPath, $data = [], $baseDirs = ['resources/views']) {
        foreach ($baseDirs as $baseDir) {
            $fullPath = __DIR__ . '/../' . $baseDir . '/' . $viewPath . '.php';
            
            if (!empty($data)) {
                extract($data);
            }

            if (file_exists($fullPath)) {
                require $fullPath;
                return;
            }
        }
        echo "View not found: $viewPath in directories: " . implode(', ', $baseDirs);
    }
}

?>