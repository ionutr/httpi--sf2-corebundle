<?php

namespace Httpi\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * File
 *
 * @ORM\Table(name="file")
 * @ORM\Entity
 */
class File
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
     * @ORM\Column(name="mimetype", type="string", length=128, nullable=true)
     */
    private $mimetype;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="text", nullable=false)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=128, nullable=false)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="original_filename", type="string", length=128, nullable=false)
     */
    private $originalFilename;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=32, nullable=true)
     */
    private $size;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="text", nullable=true)
     */
    private $file;

    /**
     * @var \Httpi\Bundle\CoreBundle\Entity\Info
     *
     * @ORM\ManyToOne(targetEntity="Httpi\Bundle\CoreBundle\Entity\Info", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="info_id", referencedColumnName="id")
     * })
     */
    private $info;

    /**
     * @var \Httpi\Bundle\CoreBundle\Entity\Status
     *
     * @ORM\ManyToOne(targetEntity="Httpi\Bundle\CoreBundle\Entity\Status")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     * })
     */
    private $status;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Httpi\Bundle\CoreBundle\Entity\Import", mappedBy="files")
     */
    private $imports;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->imports = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set mimetype
     *
     * @param string $mimetype
     * @return File
     */
    public function setMimetype($mimetype)
    {
        $this->mimetype = $mimetype;
    
        return $this;
    }

    /**
     * Get mimetype
     *
     * @return string 
     */
    public function getMimetype()
    {
        return $this->mimetype;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return File
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return File
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    
        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set originalFilename
     *
     * @param string $originalFilename
     * @return File
     */
    public function setOriginalFilename($originalFilename)
    {
        $this->originalFilename = $originalFilename;
    
        return $this;
    }

    /**
     * Get originalFilename
     *
     * @return string 
     */
    public function getOriginalFilename()
    {
        return $this->originalFilename;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return File
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
     * Set size
     *
     * @param string $size
     * @return File
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
     * Set file
     *
     * @param string $file
     * @return File
     */
    public function setFile($file)
    {
        $this->file = $file;
    
        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set info
     *
     * @param \Httpi\Bundle\CoreBundle\Entity\Info $info
     * @return File
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
     * Set status
     *
     * @param \Httpi\Bundle\CoreBundle\Entity\Status $status
     * @return File
     */
    public function setStatus(\Httpi\Bundle\CoreBundle\Entity\Status $status = null)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return \Httpi\Bundle\CoreBundle\Entity\Status 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add imports
     *
     * @param \Httpi\Bundle\CoreBundle\Entity\Import $imports
     * @return File
     */
    public function addImport(\Httpi\Bundle\CoreBundle\Entity\Import $imports)
    {
        $this->imports[] = $imports;
    
        return $this;
    }

    /**
     * Remove imports
     *
     * @param \Httpi\Bundle\CoreBundle\Entity\Import $imports
     */
    public function removeImport(\Httpi\Bundle\CoreBundle\Entity\Import $imports)
    {
        $this->imports->removeElement($imports);
    }

    /**
     * Get imports
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImports()
    {
        return $this->imports;
    }
}