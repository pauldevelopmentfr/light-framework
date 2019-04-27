<?php

namespace App\Core\Model;

use App\Core\Model\AbstractModel;

class AppModel extends AbstractModel
{
    /**
     * Contains model name
     *
     * @var string $modelName
     */
    protected $modelName = 'App';

    /**
     * Get Light config by name
     *
     * @param string $name
     *
     * @return string
     */
    public function getLightConfig(string $name) : string
    {
        $selectLightConfig = $this->db->prepare('SELECT * FROM light_config WHERE title = :name');
        $selectLightConfig->execute([
            'name' => $name
        ]);
        $lightConfig = $selectLightConfig->fetch();

        return $lightConfig['value'];
    }
}
