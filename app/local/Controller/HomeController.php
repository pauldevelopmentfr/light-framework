<?php

namespace App\Local\Controller;

use App\Core\Controller\AbstractController;
use App\Core\ViewModel;
use App\Core\Config;

class HomeController extends AbstractController
{
    /**
     * Index controller
     *
     * @return ViewModel
     */
    public function indexAction() : ViewModel
    {
        return new ViewModel('index.phtml');
    }

    /**
     * Language controller
     *
     * @param array $parameters
     */
    public function languageAction(array $parameters = [])
    {
        $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/';
        if (empty($parameters) || !isset($parameters['lang'])) {
            $this->redirect($redirectUrl);
            return;
        }

        $lang = htmlspecialchars($parameters['lang']);

        if (in_array($lang, Config::getConfig('languages'))) {
            $_SESSION['language'] = $lang;
        }

        $this->redirect($redirectUrl);
        return;
    }
}
