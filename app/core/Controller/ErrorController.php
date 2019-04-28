<?php

namespace App\Core\Controller;

use App\Core\Controller\AbstractController;
use App\Core\ViewModel;

class ErrorController extends AbstractController
{
    /**
     * Index controller
     *
     * @return ViewModel
     */
    public function notFoundAction() : ViewModel
    {
        return new ViewModel('errors/404.phtml');
    }
}
