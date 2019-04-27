<?php

use App\Core\Router;

Router::get(
    '/',
    ['HomeController', 'indexAction']
);

Router::get(
    '/language',
    ['HomeController', 'languageAction']
);
