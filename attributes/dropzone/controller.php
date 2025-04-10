<?php

namespace Concrete\Package\Bitter\DropzoneAttribute\Attribute\Dropzone;

use Bitter\DropzoneAttribute\Entity\Attribute\Key\Settings\DropzoneSettings;
use Bitter\DropzoneAttribute\Entity\Attribute\Value\Value\DropzoneSelectedFiles;
use Bitter\DropzoneAttribute\Entity\Attribute\Value\Value\DropzoneValue;
use Concrete\Core\Attribute\Controller as AttributeTypeController;
use Concrete\Core\Attribute\FontAwesomeIconFormatter;
use Concrete\Core\Backup\ContentExporter;
use Concrete\Core\Backup\ContentImporter\ValueInspector\ValueInspector;
use Concrete\Core\Entity\File\File as FileEntity;
use Concrete\Core\Entity\File\Version;
use Concrete\Core\Error\ErrorList\Error\Error;
use Concrete\Core\Error\ErrorList\Error\FieldNotPresentError;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Error\ErrorList\Field\AttributeField;
use Concrete\Core\File\File;
use Concrete\Core\Form\Service\Validation;
use HtmlObject\Element;
use SimpleXMLElement;

class Controller extends AttributeTypeController
{
    public function getIconFormatter(): FontAwesomeIconFormatter
    {
        return new FontAwesomeIconFormatter('files-o');
    }

    public function getSearchIndexValue(): bool
    {
        return false;
    }

    public function getAttributeKeySettingsClass(): string
    {
        return DropzoneSettings::class;
    }

    public function getAttributeValueClass(): string
    {
        return DropzoneValue::class;
    }

    public function createAttributeValueFromRequest()
    {
        $data = $this->post();

        if (isset($data['value'])) {
            return $this->createAttributeValue($data['value']);
        }

        return $this->createAttributeValue();
    }

    protected function load(): void
    {
        $attributeKey = $this->getAttributeKey();

        if (!is_object($this->attributeKey)) {
            return;
        }

        $this->set('attributeKey', $attributeKey);
    }

    public function type_form()
    {
        $this->load();
    }

    /**
     * @param array $data
     * @return DropzoneSettings
     */
    public function saveKey($data): DropzoneSettings
    {
        /** @var DropzoneSettings $type */
        $type = $this->getAttributeKeySettings();
        return $type;
    }

    public function importValue(SimpleXMLElement $item)
    {
        $files = [];

        foreach ($item->children() as $fileItem) {
            $fIDVal = (string)$fileItem;
            /** @var ValueInspector $valueInspector */
            $inspector = $this->app->make('import/value_inspector');
            $result = $inspector->inspect($fIDVal);
            $files[] = $result->getReplacedValue();
        }

        return $this->createAttributeValue($files);
    }

    public function importKey(SimpleXMLElement $element): DropzoneSettings
    {
        /** @var DropzoneSettings $type */
        $type = $this->getAttributeKeySettings();

        return $type;
    }

    public function validateForm($data)
    {
        $this->load();

        if (!is_array($data['value'])) {
            return new FieldNotPresentError(new AttributeField($this->getAttributeKey()));
        }

        return true;
    }

    public function createAttributeValue($mixed = null): DropzoneValue
    {
        $attributeValue = new DropzoneValue();

        if (is_array($mixed) && count($mixed) > 0) {
            foreach ($mixed as $fileId) {
                $file = File::getByID($fileId);

                if ($file instanceof FileEntity) {
                    $attributeValueFile = new DropzoneSelectedFiles();
                    $attributeValueFile->setFile($file);
                    $attributeValueFile->setAttributeValue($attributeValue);
                    $attributeValue->getSelectedFiles()->add($attributeValueFile);
                }
            }
        }

        return $attributeValue;
    }

    public function createAttributeKeySettings(): DropzoneSettings
    {
        return new DropzoneSettings();
    }

    public function getDisplayValue(): string
    {
        $ul = new Element("ul");

        $currentFilesValue = $this->attributeValue->getValue();

        if (is_object($currentFilesValue)) {
            /** @var FileEntity[] $currentFiles */
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            $currentFiles = $currentFilesValue->getFileObjects();

            if (count($currentFiles) > 0) {
                foreach ($currentFiles as $fileEntity) {
                    $approvedFileVersion = $fileEntity->getApprovedVersion();

                    if ($approvedFileVersion instanceof Version) {
                        $ul->appendChild(
                            new Element(
                                "li",
                                new Element(
                                    "a",
                                    $approvedFileVersion->getFileName(),
                                    [
                                        "href" => $approvedFileVersion->getDownloadURL(),
                                        "target" => "_blank"
                                    ]
                                )
                            )
                        );
                    }
                }
            }
        }
        if (count($ul->getChildren()) > 0) {
            return (string)$ul->render();
        } else {
            return t("No files selected.");
        }
    }

    public function form()
    {

        if (is_object($this->attributeValue)) {
            $currentFilesValue = $this->attributeValue->getValue();

            if ($currentFilesValue) {
                /** @noinspection PhpPossiblePolymorphicInvocationInspection */
                $this->set('currentFiles', $currentFilesValue->getFileObjects());
            }
        }

        $this->load();
    }

    public function exportValue(SimpleXMLElement $akn)
    {
        $currentFilesValue = $this->getAttributeValue()->getValue();

        if (is_object($currentFilesValue)) {
            /** @var FileEntity[] $currentFiles */
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            $currentFiles = $currentFilesValue->getFileObjects();

            if (count($currentFiles) > 0) {
                foreach ($currentFiles as $fileEntity) {
                    $akn->addChild('file', ContentExporter::replaceFileWithPlaceHolder($fileEntity->getFileID()));
                }
            }
        }
    }
}
