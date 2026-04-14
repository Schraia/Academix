<?php

return [
    'node_binary' => env('BROWSERSHOT_NODE_BINARY'),
    'npm_binary' => env('BROWSERSHOT_NPM_BINARY'),
    'chrome_path' => env('BROWSERSHOT_CHROME_PATH'),
    'node_module_path' => env('BROWSERSHOT_NODE_MODULE_PATH', base_path('node_modules')),
];