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
     * Dispatch action on model and render result
     *
     * @param string $action
     * @param array $parameters
     */
    public function dispatch(string $action, array $parameters = [])
    {
        if (!empty($parameters)) {
            $result = $this->$action($parameters);
        } else {
            $result = $this->$action();
        }

        if ($result instanceof ViewModel) {
            echo $result->renderHtml();
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
     * Make a 302 redirection to an url
     */
    public function redirect(string $url)
    {
        header("Location: {$url}", true, 302);
    }
}
