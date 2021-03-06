<?php

namespace App\Core\Model;

class AppModel extends AbstractModel
{
    /**
     * Contains translation file name
     *
     * @var string $translationFile
     */
    protected $translationFile = 'Global';

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
