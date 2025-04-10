<?php

namespace Bitter\DropzoneAttribute\Entity\Attribute\Key\Settings;

use Concrete\Core\Entity\Attribute\Key\Settings\Settings;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DropzoneSettings")
 */
class DropzoneSettings extends Settings
{

    public function getAttributeTypeHandle(): string
    {
        return 'dropzone';
    }

}
