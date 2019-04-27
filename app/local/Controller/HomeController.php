<?php

namespace App\Local\Controller;

use App\Core\Controller\AbstractController;
use App\Core\ViewModel;

class HomeController extends AbstractController
{
    /**
     * Index controller
     */
    public function indexAction()
    {
        return new ViewModel('index.phtml');
    }
}
