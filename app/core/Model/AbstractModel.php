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
     * Contains translation file name
     *
     * @var string $translationFile
     */
    protected $translationFile;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = Light::getDatabase();
    }

    /**
     * Search translation in file
     *
     * @param resource $filePath
     * @param string $text
     *
     * @return string
     */
    private function searchTranslation($filePath, string $text) : string
    {
        if (!file_exists($filePath)) {
            throw new Exception(
                "The translation file \"{$filePath}\" doesn't exists"
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

            if ($line[0] === $text) {
                $text = $line[1];
                break;
            }
        }

        fclose($file);

        return $text;
    }

    /**
     * Make translation on a string
     *
     * @param string $string
     * @param mixed $parameters
     *
     * @return string
     */
    public function __(string $text, ...$parameters) : string
    {
        $authorizedLanguages = Light::getLanguages();
        $language = $_SESSION['language'] ?? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        $defaultLanguage = Config::getConfig('default_language');

        if ($language === $defaultLanguage) {
            $this->replaceParameters($text, $parameters);
            return $text;
        }

        if (!in_array($language, $authorizedLanguages)) {
            $language = $defaultLanguage;
        }

        $genericalPath = getcwd() . "/app/language/{$language}/%s.csv";

        if (file_exists(sprintf($genericalPath, $this->translationFile))) {
            $filePath = sprintf($genericalPath, $this->translationFile);
        } else {
            $filePath = sprintf($genericalPath, 'Global');
        }

        $translatedText = $this->searchTranslation($filePath, $text);

        if ($translatedText == $text) {
            $filePath = sprintf($genericalPath, 'Global');

            $translatedText = $this->searchTranslation($filePath, $text);
        }

        self::replaceParameters($translatedText, $parameters);

        return $translatedText;
    }

    /**
     * Replace %s by parameters in a text
     *
     * @param string $text
     * @param array $parameters
     *
     * @return string
     */
    private static function replaceParameters(string &$text, array $parameters) : string
    {
        foreach ($parameters as $parameter) {
            $text = preg_replace("/%s/", $parameter, $text, 1);
        }

        return $text;
    }
}
