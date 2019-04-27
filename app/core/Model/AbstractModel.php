<?php

namespace App\Core\Model;

use App\Core\Light;
use App\Core\Config;
use \Exception;

abstract class AbstractModel
{
    /**
     * Contains database
     *
     * @var PDO $db
     */
    protected $db;

    /**
     * Contains model name
     *
     * @var string $modelName
     */
    protected $modelName;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = Light::getDatabase();
    }

    /**
     * Make translation on a string
     *
     * @param string $string
     *
     * @return string
     */
    public function __(string $text) : string
    {
        $authorizedLanguages = Light::getLanguages();
        $language = $_SESSION['language'] ?? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        $defaultLanguage = Config::getConfig('default_language');

        if ($language === $defaultLanguage) {
            return $text;
        }

        if (!in_array($language, $authorizedLanguages)) {
            $language = $defaultLanguage;
        }

        $filePath = getcwd() . "/app/language/{$language}/%s.csv";

        if (file_exists(sprintf($filePath, $this->modelName))) {
            $filePath = sprintf($filePath, $this->modelName);
        } else {
            $filePath = sprintf($filePath, 'Global');
        }

        if (!file_exists($filePath)) {
            throw new Exception(
                "The translation for \"{$language}\" language doesn't exists for {$this->modelName}Model"
            );
        }

        $file = fopen(
            $filePath,
            'r'
        );
        
        while (!feof($file)) {
            $read = fgets($file);
            $read = str_replace("\n", '', $read);

            $line = explode('","', $read);
            
            if (is_array($line) && count($line) == 2) {
                $line[0] = substr($line[0], 1);
                $line[1] = substr($line[1], 0, strlen($line[1]) - 1);
            }

            return ($line[0] == $text) ? $line[1] : $text;
        }
        
        fclose($file);
    }
}
