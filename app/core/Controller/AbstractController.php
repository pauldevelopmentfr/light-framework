<?php

namespace App\Core\Controller;

use App\Core\ViewModel;

abstract class AbstractController
{
    /**
     * Contains request
     *
     * @var array $request
     */
    protected $request = [];
    
    /**
     * Contains model variables
     *
     * @var array $extraDatas
     */
    protected $extraDatas = [];

    /**
     * Dispatch action on model and render result
     *
     * @param string $action
     */
    public function dispatch(string $action)
    {
        $result = $this->$action();

        if ($result instanceof ViewModel) {
            echo $result->renderHtml($this->extraDatas);
        }
    }

    /**
     * Set request
     *
     * @param array $request
     */
    public function setRequest(array $request)
    {
        $this->request = $request;
    }

    /**
     * Set model variables
     *
     * @param array $extraDatas
     */
    public function setExtraDatas(array $extraDatas = [])
    {
        $this->extraDatas = $extraDatas;
    }

    /**
     * Make a 302 redirection to an url
     */
    public function redirect(string $url)
    {
        header("Location: {$url}", true, 302);
    }
}
