<?php

namespace Bitter\DropzoneAttribute\Entity\Attribute\Value\Value;

use Concrete\Core\Entity\File\File;
use Concrete\Core\Entity\File\Version;
use Concrete\Core\File\FileProviderInterface;
use Concrete\Core\Entity\Attribute\Value\Value\AbstractValue;
use Concrete\Core\Support\Facade\Url;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="DropzoneValue")
 */
class DropzoneValue extends AbstractValue implements FileProviderInterface
{
    /**
     * @var ArrayCollection|DropzoneSelectedFiles[]
     * @ORM\OneToMany(targetEntity="\Bitter\DropzoneAttribute\Entity\Attribute\Value\Value\DropzoneSelectedFiles", cascade={"persist", "remove"}, mappedBy="value")
     * @ORM\JoinColumn(name="avID", referencedColumnName="avID")
     */
    protected $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    public function getSelectedFiles()
    {
        return $this->files;
    }

    public function setSelectedFiles($files)
    {
        $this->files = $files;
    }

    /**
     * @return File[]|array
     */
    public function getFileObjects(): array
    {
        $files = array();
        $values = $this->getSelectedFiles();
        if ($values->count()) {
            foreach ($values as $f) {
                $files[] = $f->getFile();
            }
        }

        return $files;
    }
}
