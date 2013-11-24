<?php

namespace Httpi\Bundle\CoreBundle\Library\File;

class FileUploadResponse {

    protected $code = 200;//@TODO: move to config, instantiate in constructor

    protected $data;

    public function __construct(\Httpi\Bundle\CoreBundle\Library\File\FileUpload $upload)
    {
        $this->data = array();

        if ($upload->isValid()) {
            $this->data['success'] = true;
            $this->data['files'] = $upload->getFilesData();
            $this->data['fileIdsString'] = '';
            foreach ($this->data['files']['list'] as $file) {
                $this->data['fileIdsString'] .= $file['fileId'] . ",";
            }
            $this->data['fileIdsString'] = substr($this->data['fileIdsString'], 0, -1);
        } else {
            $this->code = 409;
            $this->data = $upload->getErrorMessage();
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function getCode()
    {
        return $this->code;
    }
}