<?php

namespace Httpi\Bundle\CoreBundle\Form\DataTransformer;

use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\DataTransformerInterface;

class EntityToArrayTransformer implements DataTransformerInterface {

    private $entityName;

    public function transform($entity)
    {
        // check if object
        if (!is_object($entity)) {
            throw new TransformationFailedException('Transform function expects $entity to be a object!');
        }

        // store entity name for reverse transformation
        $this->entityName = get_class($entity);

        return $this->doTransform($entity);
    }

    public function reverseTransform($array)
    {
        $entity = new $this->entityName;
        $entity->setData($array);

        return $entity;
    }

    private function doTransform($obj)
    {
        $data = array();
        if(!is_array($obj) && !is_object($obj)) return $obj;
        if(is_object($obj)) $arr = (array)$obj;

        foreach ($arr as $key => $val) {
            if (!is_object($val) && substr($key, 3, 4) != 'data') {
                $data[substr(str_replace($this->entityName, "", $key), 2)] = $val;
            }
        }

        return $data;
    }

}