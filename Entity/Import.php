<?php

namespace Httpi\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Import
 *
 * @ORM\Table(name="import")
 * @ORM\Entity
 */
class Import
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="object_fqn", type="string", length=128, nullable=false)
     */
    private $objectFqn;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=128, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="data", type="object", nullable=true)
     */
    private $data;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="started_at", type="datetime", nullable=true)
     */
    private $startedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ended_at", type="datetime", nullable=true)
     */
    private $endedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="success", type="boolean", nullable=true)
     */
    private $success;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=32, nullable=true)
     */
    private $size;

    /**
     * @var integer
     *
     * @ORM\Column(name="rows", type="smallint", nullable=true)
     */
    private $rows;

    /**
     * @var \Httpi\Bundle\CoreBundle\Entity\Info
     *
     * @ORM\OneToOne(targetEntity="Httpi\Bundle\CoreBundle\Entity\Info", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="info_id", referencedColumnName="id", unique=true)
     * })
     */
    private $info;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Httpi\Bundle\CoreBundle\Entity\File", inversedBy="imports", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinTable(name="import_file",
     *   joinColumns={
     *     @ORM\JoinColumn(name="import_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="file_id", referencedColumnName="id", unique=true)
     *   }
     * )
     */
    private $files;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

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
     * Set objectFqn
     *
     * @param string $objectFqn
     * @return Import
     */
    public function setObjectFqn($objectFqn)
    {
        $this->objectFqn = $objectFqn;
    
        return $this;
    }

    /**
     * Get objectFqn
     *
     * @return string 
     */
    public function getObjectFqn()
    {
        return $this->objectFqn;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Import
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Import
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set data
     *
     * @param \stdClass $data
     * @return Import
     */
    public function setData($data)
    {
        $this->data = $data;
    
        return $this;
    }

    /**
     * Get data
     *
     * @return \stdClass 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set startedAt
     *
     * @param \DateTime $startedAt
     * @return Import
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;
    
        return $this;
    }

    /**
     * Get startedAt
     *
     * @return \DateTime 
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * Set endedAt
     *
     * @param \DateTime $endedAt
     * @return Import
     */
    public function setEndedAt($endedAt)
    {
        $this->endedAt = $endedAt;
    
        return $this;
    }

    /**
     * Get endedAt
     *
     * @return \DateTime 
     */
    public function getEndedAt()
    {
        return $this->endedAt;
    }

    /**
     * Set success
     *
     * @param boolean $success
     * @return Import
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    
        return $this;
    }

    /**
     * Get success
     *
     * @return boolean 
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Import
     */
    public function setMessage($message)
    {
        $this->message = $message;
    
        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set size
     *
     * @param string $size
     * @return Import
     */
    public function setSize($size)
    {
        $this->size = $size;
    
        return $this;
    }

    /**
     * Get size
     *
     * @return string 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set rows
     *
     * @param integer $rows
     * @return Import
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
    
        return $this;
    }

    /**
     * Get rows
     *
     * @return integer 
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Set info
     *
     * @param \Httpi\Bundle\CoreBundle\Entity\Info $info
     * @return Import
     */
    public function setInfo(\Httpi\Bundle\CoreBundle\Entity\Info $info = null)
    {
        $this->info = $info;
    
        return $this;
    }

    /**
     * Get info
     *
     * @return \Httpi\Bundle\CoreBundle\Entity\Info 
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Add files
     *
     * @param \Httpi\Bundle\CoreBundle\Entity\File $files
     * @return Import
     */
    public function addFile(\Httpi\Bundle\CoreBundle\Entity\File $files)
    {
        $this->files[] = $files;
    
        return $this;
    }

    /**
     * Remove files
     *
     * @param \Httpi\Bundle\CoreBundle\Entity\File $files
     */
    public function removeFile(\Httpi\Bundle\CoreBundle\Entity\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }
}