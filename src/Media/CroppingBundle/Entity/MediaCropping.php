<?php

namespace Media\CroppingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * MediaCropping
 * @UniqueEntity(
 *     fields={"media", "entityType", "entity" ,"sizeKey"},
 *     errorPath="media",
 *     message="Already croppped media exists for this size and linked entity"
 * )
 * @ORM\Table(name="media_cropping", uniqueConstraints={@ORM\UniqueConstraint(name="unique_pair", columns={"media_id", "entity_type", "entity", "size_key"})}, indexes={@ORM\Index(name="media_fk", columns={"media_id"})})
 * @ORM\Entity
 */
class MediaCropping
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

	/**
     * @var string
     *
     * @ORM\Column(name="path", type="text", nullable=false)
     */
    private $path;

	/**
     * @var string
     *
     * @ORM\Column(name="meta", type="text", nullable=false)
     */
    private $meta;

    /**
     * @var string
     *
     * @ORM\Column(name="entity_type", type="string", length=255, nullable=false)
     */
    private $entityType;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity", type="bigint", nullable=false)
     */
    private $entity;

    /**
     * @var string
     *
     * @ORM\Column(name="size_key", type="string", length=500, nullable=false)
     */
    private $sizeKey;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     * })
     */
    private $media;



    /**
     * Set name
     *
     * @param string $name
     * @return MediaCropping
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Set path
     *
     * @param string $path
     * @return MediaCropping
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
     * Set meta
     *
     * @param string $meta
     * @return MediaCropping
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * Get meta
     *
     * @return string
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Set entityType
     *
     * @param string $entityType
     * @return MediaCropping
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * Get entityType
     *
     * @return string 
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * Set entity
     *
     * @param integer $entity
     * @return MediaCropping
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return integer 
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set sizeKey
     *
     * @param string $sizeKey
     * @return MediaCropping
     */
    public function setSizeKey($sizeKey)
    {
        $this->sizeKey = $sizeKey;

        return $this;
    }

    /**
     * Get sizeKey
     *
     * @return string 
     */
    public function getSizeKey()
    {
        return $this->sizeKey;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return MediaCropping
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return MediaCropping
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
     * Set media
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $media
     * @return MediaCropping
     */
    public function setMedia(\Application\Sonata\MediaBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getMedia()
    {
        return $this->media;
    }
}
