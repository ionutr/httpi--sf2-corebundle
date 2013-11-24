<?php

namespace Httpi\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Info
 *
 * @ORM\Table(name="info")
 * @ORM\Entity
 */
class Info
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by_user_id", type="integer")
     */
    private $created_by_user_id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_at", type="datetime", nullable=true)
     */
    private $modified_at;

    /**
     * @var integer
     *
     * @ORM\Column(name="last_modified_by_user_id", type="integer", nullable=true)
     */
    private $last_modified_by_user_id;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Info
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set created_by_user_id
     *
     * @param integer $createdByUserId
     * @return Info
     */
    public function setCreatedByUserId($createdByUserId)
    {
        $this->created_by_user_id = $createdByUserId;
    
        return $this;
    }

    /**
     * Get created_by_user_id
     *
     * @return integer 
     */
    public function getCreatedByUserId()
    {
        return $this->created_by_user_id;
    }

    /**
     * Set modified_at
     *
     * @param \DateTime $modifiedAt
     * @return Info
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modified_at = $modifiedAt;
    
        return $this;
    }

    /**
     * Get modified_at
     *
     * @return \DateTime 
     */
    public function getModifiedAt()
    {
        return $this->modified_at;
    }

    /**
     * Set last_modified_by_user_id
     *
     * @param integer $lastModifiedByUserId
     * @return Info
     */
    public function setLastModifiedByUserId($lastModifiedByUserId)
    {
        $this->last_modified_by_user_id = $lastModifiedByUserId;
    
        return $this;
    }

    /**
     * Get last_modified_by_user_id
     *
     * @return integer 
     */
    public function getLastModifiedByUserId()
    {
        return $this->last_modified_by_user_id;
    }
}