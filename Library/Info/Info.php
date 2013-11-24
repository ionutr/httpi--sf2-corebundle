<?php

namespace Httpi\Bundle\CoreBundle\Library\Info;

use Httpi\Bundle\CoreBundle\Entity\Info as InfoModel;

class Info {
	
	public static function stamp(InfoModel $infoModel)
	{
		
		if (!$infoModel->getCreatedAt() instanceof \DateTime) {
			$infoModel = new InfoModel;
			$infoModel->setCreatedByUserId(1);
			$infoModel->setCreatedAt(new \DateTime());
		} else {
			$infoModel->setLastModifiedByUserId(1);
			$infoModel->setModifiedAt(new \DateTime());
		}

		//$em->persist($infoModel);
        //$em->flush();
		
		return $infoModel;
	}
	
}