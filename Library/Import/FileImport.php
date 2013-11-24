<?php

namespace Httpi\Bundle\CoreBundle\Library\Import;

use Httpi\Bundle\CoreBundle\Library\Status\Status as StatusLib;
use Symfony\Component\Config\Definition\Exception\Exception;

class FileImport extends Import {

    protected $fileIds;

    public function execute()
    {
        // call parent core execution first
        if (!is_null($this->keepTransactionFlag) && $this->keepTransactionFlag === true) {
            parent::execute();
        } else {
            $this->keepTransaction(true);
            parent::execute();
            $this->keepTransaction(false);
        }

        $this->fileIds = array();

        // get connection from entityManager
        $connection = $this->em->getConnection();

        try {

            // file info
            $connection->insert('info', array(
                'created_at' => date("Y-m-d H:i:s"),
                'created_by_user_id' => 1
            ));
            $infoId = $connection->lastInsertId();

            // fetch&verify source
            $source = $this->getSource();
            if (is_null($source) || !file_exists($source) || !is_readable($source)) {
                throw new \Exception('Input file not found or not readable.');
            }

            $filename = basename($source);

            // file
            $connection->insert('file', array(
                'info_id' => $infoId,
                'status_id' => StatusLib::STATUS_ACTIVE,
                'mimetype' => 'text/csv',//@TODO: get via getMimeType() somehow
                'path' => dirname($source) . DIRECTORY_SEPARATOR,
                'filename' => $filename,//@TODO: get via getMimeType() somehow
                'original_filename' => $filename,
                'size' => filesize($source)
            ));
            $fileId = $connection->lastInsertId();
            $this->fileIds[] = $fileId;

            // import file link table entry
            $connection->insert('import_file', array(
                'import_id' => $this->getId(),
                'file_id' => $fileId
            ));

            if (!$this->keepTransactionFlag) {
                $this->commitTransaction();
            }
        } catch (\Exception $e) {
            $this->rollbackTransaction();
        }
    }

}