<?php

use App\Core\Router;

Router::get(
    '/',
    ['HomeController', 'indexAction']
);

Router::get(
    '/language/{lang}',
    ['HomeController', 'languageAction']
);
