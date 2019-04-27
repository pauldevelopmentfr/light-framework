<?php

namespace App\Core\Model;

use App\Core\Light;

abstract class AbstractModel
{
    /**
     * Contains database
     *
     * @var PDO $db
     */
    protected $db;

    public function __construct()
    {
        $this->db = Light::getDatabase();
    }
}
