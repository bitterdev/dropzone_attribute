<?php

namespace Bitter\DropzoneAttribute\Entity\Attribute\Value\Value;

use Concrete\Core\Entity\File\File;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DropzoneSelectedFiles")
 */
class DropzoneSelectedFiles
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $avsID;

    /**
     * @ORM\ManyToOne(targetEntity="\Bitter\DropzoneAttribute\Entity\Attribute\Value\Value\DropzoneValue")
     * @ORM\JoinColumn(name="avID", referencedColumnName="avID", onDelete="CASCADE")
     */
    protected $value;

    /**
     * @var File|null
     * @ORM\ManyToOne(targetEntity="\Concrete\Core\Entity\File\File")
     * @ORM\JoinColumn(name="fID", referencedColumnName="fID", onDelete="CASCADE")
     */
    protected $file;

    /**
     * @return File
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     */
    public function setFile(?File $file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getAttributeValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setAttributeValue($value)
    {
        $this->value = $value;
    }
}
