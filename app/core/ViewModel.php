<?php

namespace App\Core;

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
     * Render HTML
     *
     * @return string
     */
    public function renderHtml(array $variables = []) : string
    {
        $file = getcwd() . "/public/view/{$this->view}";
        $html = '';
    
        if (file_exists($file)) {
            ob_start();
            extract($variables);
            require_once $file;
            $html = ob_get_clean();
        }

        return $html;
    }
}
