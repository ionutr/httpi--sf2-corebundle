<?php

namespace Httpi\Bundle\CoreBundle\Library\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Httpi\Bundle\CoreBundle\Form\DataTransformer\EntityToArrayTransformer;

trait ModelTrait {

    protected $data = array();

    public function setData(array $data)
    {


        $transformer = new EntityToArrayTransformer;
        $this->data = array_merge($transformer->transform($this), $data);
        foreach ($data as $key => $val) {
            $this->$key = $val;
        }
    }

    public function getData()
    {
        return $this->data;
    }
}