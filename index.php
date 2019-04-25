<?php

/*
|-------------------------------------------------------------
| Light Framework - Developed by Paul Sinnah
|-------------------------------------------------------------
|
| This framework is based on Benjamin Denizart's course as a teacher
| of the UIT classroom of Sophia Antipolis - 2017-2019 Promotion.
|
*/

require_once 'app/core/Autoloader.php';

\App\Core\Autoloader::register();

(new \App\Core\Application)->run();
