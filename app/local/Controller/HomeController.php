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
     */
    public function languageAction(array $language = [])
    {
        if (empty($language) || !isset($language[0])) {
            $this->redirect('/');
            die;
        }

        $lang = htmlspecialchars($language[0]);

        if (in_array($lang, Config::getConfig('languages'))) {
            $_SESSION['language'] = $lang;
        }

        $this->redirect('/');
        die;
    }
}
