<?php

namespace App\Core;

use App\Core\Light;
use \BadMethodCallException;

class ViewModel
{
    /**
     * Contains view
     *
     * @var string $view
     */
    private $view;

    /**
     * Contains parameters
     *
     * @var array|null $parameters
     */
    private $parameters;

    /**
     * Constructor
     *
     * @param string $view
     * @param array $parameters
     */
    public function __construct(string $view, array $parameters = null)
    {
        $this->view = $view;
        $this->parameters = $parameters;
    }

    /**
     * Overload methods using $this->parameters
     *
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        if (substr($name, 0, 3) !== 'get') {
            $className = static::class;
            throw new BadMethodCallException("Call to undefined method {$className}->{$name}()");
        }

        $key = substr(strtolower(preg_replace('/([A-Z])/', "_$1", substr($name, 3))), 1);

        if (!isset($this->parameters[$key])) {
            throw new BadMethodCallException("The method {$name} doesn't exists !");
        }

        return $this->parameters[$key];
    }

    /**
     * Render HTML
     *
     * @return string
     */
    public function renderHtml() : string
    {
        $file = getcwd() . "/public/view/{$this->view}";
        $datas = $this->parameters;
        $html = '';
    
        if (file_exists($file)) {
            ob_start();
                ob_start();
                    extract($datas);
                    require_once getcwd() . "/public/view/template/header.phtml";
                    require_once $file;
                    require_once getcwd() . "/public/view/template/footer.phtml";
                $content = ob_get_clean();
                require_once getcwd() . "/public/view/template/app.phtml";
            $html = ob_get_clean();
            unset($content);
        }

        return $html;
    }
}
