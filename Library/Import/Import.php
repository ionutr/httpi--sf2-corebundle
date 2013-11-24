<?php

namespace Httpi\Bundle\CoreBundle\Library\Import;

use Doctrine\ORM\EntityManager;

class Import {

    const SOURCE_TYPE_FILE = 1;

    const SOURCE_TYPE_URL = 2;

    protected $em;

    protected $id;

    protected $title;

    protected $description;

    protected $objectFqn;

    protected $sourceType;// file, url

    protected $source;

    protected $keepTransactionFlag = false;

    protected $transactionStarted = false;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSourceType()
    {
        return $this->sourceType;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSourceType($type = null)
    {
        $this->sourceType = $type;
    }

    public function setSource($path)
    {
        $this->source = $path;
    }

    public function setSourceFile($file)
    {
        $this->setSourceType(Import::SOURCE_TYPE_FILE);
        $this->setSource($file);
    }

    public function setSourceUrl($url)
    {
        $this->setSourceType(Import::SOURCE_TYPE_URL);
        $this->setSource($url);
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDescription($text)
    {
        $this->description = $text;
    }

    public function setObjectFqn($fqn)
    {
        $this->objectFqn = $fqn;
    }

    protected function process() {}

    public function execute()
    {
        // start the transaction
        if (!$this->transactionStarted) {
            $this->beginTransaction();
        }

        try {
            // do some processing
            $this->process();

            // core execution
            $this->doExecute();

            // end the transaction
            if (!$this->keepTransactionFlag) {
                $this->commitTransaction();
            }

        } catch (\Exception $e) {
            $this->rollbackTransaction();
        }
    }

    final protected function beginTransaction()
    {
        $this->em->beginTransaction();
    }

    final protected function commitTransaction()
    {
        $this->em->commit();
    }

    final protected function rollbackTransaction()
    {
        $this->em->rollback();
    }

    final protected function keepTransaction($bool)
    {
        $this->keepTransactionFlag = (bool)$bool;
    }

    private function doExecute()
    {
        $connection = $this->em->getConnection();

        // import info
        $connection->insert('info', array(
            'created_at' => date("Y-m-d H:i:s"),
            'created_by_user_id' => 1//@TODO: pretty clear, no?
        ));
        $infoId = $connection->lastInsertId();

        // import record
        $connection->insert('import', array(
            'info_id' => $infoId,
            'object_fqn' => $this->objectFqn,
            'title' => $this->title,
            'description' => $this->description,
            'started_at' => date("Y-m-d H:i:s")
        ));
        $this->id = $connection->lastInsertId();
    }
}