<?php

namespace Bitter\DropzoneAttribute\API\V1;

use Concrete\Core\Application\EditResponse;
use Concrete\Core\Entity\File\File;
use Concrete\Core\Entity\File\Version;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\File\Import\FileImporter;
use Concrete\Core\File\Import\ImportException;
use Concrete\Core\Http\Request;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Bitter\DropzoneAttribute\Entity\Attribute\Value\Value\DropzoneValue;

class Dropzone
{
    protected $request;
    protected $importer;
    protected $entityManager;
    protected $responseFactory;

    public function __construct(
        Request $request,
        FileImporter $importer,
        EntityManagerInterface $entityManager,
        ResponseFactory $responseFactory
    )
    {
        $this->request = $request;
        $this->importer = $importer;
        $this->entityManager = $entityManager;
        $this->responseFactory = $responseFactory;
    }

    public function downloadFiles(): Response
    {
        $user = new User;

        if ($user->isSuperUser()) {
            $avID = (int)$this->request->get("avID");

            $dropzoneValue = $this->entityManager->getRepository(DropzoneValue::class)->findOneBy(["generic_value" => $avID]);
    
            if ($dropzoneValue instanceof DropzoneValue) {
                $files = $dropzoneValue->getFileObjects();
    
                $zipFile = tempnam(sys_get_temp_dir(), "ZIP");

                $zip = new \ZipArchive();

                $zip->open($zipFile, \ZipArchive::CREATE);

                foreach($files as $file) {
                    if ($file instanceof File) {
                        $fileVersion = $file->getApprovedVersion();
    
                        if ($fileVersion instanceof Version) {
                            $fileResource = $fileVersion->getFileResource();
                            if ($fileResource instanceof \League\Flysystem\File) {
                                $zip->addFromString($fileVersion->getFileName(), $fileResource->read());
                            }
                        }
                    }
                }

                $zip->close();

                $response = new BinaryFileResponse($zipFile);
                $response->headers->set('Content-Type', "application/x-zip");
                $response->setContentDisposition(
                    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                    t("Archive.zip")
                );

                return $response;
            } else {
                return $this->responseFactory->notFound(t("Not Found"));
            }
        } else {
            return $this->responseFactory->forbidden("");
        }
    }

    public function uploadFile(): JsonResponse
    {
        $editResponse = new EditResponse();
        $errorList = new ErrorList();

        $uploadedFile = $this->request->files->get("file");

        if ($uploadedFile instanceof UploadedFile) {

            if ($uploadedFile->isValid()) {

                $fileName = $uploadedFile->getClientOriginalName();

                try {
                    $fileVersion = $this->importer->importUploadedFile($uploadedFile, $fileName);

                    if ($fileVersion instanceof Version) {
                        $editResponse->setMessage(t("File uploaded successfully."));
                        $editResponse->setAdditionalDataAttribute("file", $fileVersion->getJSONObject());
                    }
                } catch (ImportException $e) {
                    $errorList->add($e);
                }
            } else {
                $errorList->add(ImportException::describeErrorCode($uploadedFile->getError()));
            }
        } else {
            $errorList->add(t('File not received'));
        }

        $editResponse->setError($errorList);

        return new JsonResponse($editResponse);
    }
}
