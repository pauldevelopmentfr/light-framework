<?php

use App\Core\Router;

Router::get(
    '/',
    ['HomeController', 'indexAction']
);
