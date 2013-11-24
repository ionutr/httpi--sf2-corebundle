<?php

namespace Httpi\Bundle\CoreBundle\Library\File;

use Symfony\Component\DependencyInjection\ContainerAware;
use Doctrine\ORM\EntityManager;

use Httpi\Bundle\CoreBundle\Entity\Info;
use Httpi\Bundle\CoreBundle\Library\Info\Info as InfoLib;
use Httpi\Bundle\CoreBundle\Library\Status\Status as StatusLib;
use Httpi\Bundle\CoreBundle\Library\File\FileUploadResponse;
use Httpi\Bundle\CoreBundle\Entity\File;

class FileUpload extends ContainerAware {

    protected $em;

    protected $dbStorage = true; //@TODO: move default values to config, instantiate defaults in constructor

    protected $mimetypes; //@TODO: move default values to config, instantiate defaults in constructor

    protected $fileMaxSize = 10240; //@TODO: move default values to config, instantiate defaults in constructor

    protected $totalMaxSize = 65000; //@TODO: move default values to config, instantiate defaults in constructor

    protected $destinationPath; //@TODO: move default values to config, instantiate defaults in constructor

    protected $files;

    protected $data;

    protected $valid;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setTotalMaxSize($size)
    {
        $this->totalMaxSize = $size;
    }

    public function setFileMaxSize($size)
    {
        $this->fileMaxSize = $size;
    }

    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    public function setMimeTypes(array $mimetypes) {
        $this->mimetypes = $mimetypes; //@TODO: verify each one
    }

    public function setDestinationPath($path) {
        //@TODO: verify path for integrity
        $this->destinationPath = $path;
    }

    public function upload()
    {
        $this->process();
        return new FileUploadResponse($this);

    }

    private function process()
    {
        // validate first
        $this->validate();

        // do some process
        if ($this->isValid()) {
            $this->data = array('list' => array());
            $this->doUpload();

        }
    }

    private function validate()
    {
        $this->valid = true;
        //@TODO: validation here

    }

    public function isValid()
    {
        return $this->valid;
    }

    private function doUpload()
    {
        foreach ($this->files['tmp_name'] as $key => $tmpFile) {

            // filenames and path handling
            $originalFilename = $this->files['name'][$key];
            $fileExtension = substr($originalFilename, strrpos($originalFilename, '.'));
            $generatedFilename = $this->generateFilename($originalFilename, $fileExtension);

            // do upload
            move_uploaded_file($tmpFile, $this->destinationPath . $generatedFilename);

            // db storage
            if ($this->dbStorage === true) {

                $status = $this->em->find('HttpiCoreBundle:Status', StatusLib::STATUS_ACTIVE);

                $dbFile = new File();
                $dbFile->setMimetype($this->files['type'][$key]);
                $dbFile->setPath($this->destinationPath);
                $dbFile->setFilename($generatedFilename);
                $dbFile->setOriginalFilename($originalFilename);
                $dbFile->setSize($this->files['size'][$key]);
                $dbFile->setInfo(InfoLib::stamp(new Info()));
                $dbFile->setStatus($status);

                $this->em->persist($dbFile);
                $this->em->flush();

                $this->data['list'][] = array(
                    'fileId' => $dbFile->getId(),
                    'filename' => $generatedFilename,
                    'originalFilename' => $originalFilename
                );
            } else {
                $this->data['list'][] = array(
                    'fileId' => null,
                    'filename' => $generatedFilename,
                    'originalFilename' => $originalFilename
                );
            }
        }
    }

    private function generateFilename($originalFilename, $fileExtension)
    {
        return md5($originalFilename . time()) . $fileExtension;
    }

    public function getFilesData()
    {
        return $this->data;
    }
}