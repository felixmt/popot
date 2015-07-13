<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Gallery
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\GalleryRepository")
 * @UniqueEntity(fields="route", message="Un chemin d'accès identique existe déjà.")
 */
class Gallery
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=255, unique=true)
     */
    private $route;

    /**
     * @var string
     *
     * @ORM\Column(name="folder", type="string", length=255, unique=true)
     */
    private $folder;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="isHomeGallery", type="boolean", nullable=true)
     */
    private $isHomeGallery;

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
     * Set name
     *
     * @param string $name
     * @return MenuItem
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
     * Set route
     *
     * @param string $route
     * @return MenuItem
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string 
     */
    public function getRoute()
    {
        return $this->route;
    }
    
    /**
     * Set folder
     *
     * @param string $folder
     * @return string
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
    }


    /**
     * Get folder
     *
     * @return string 
     */
    public function getFolder()
    {
        return $this->folder;
    }
    
    /**
     * Set isHomepage
     *
     * @param boolean $isHomepage
     * @return Page
     */
    public function setIsHomeGallery($isHomeGallery)
    {
        $this->isHomeGallery = $isHomeGallery;
        
        return $this;
    }


    /**
     * Get isHomeGallery
     *
     * @return boolean 
     */
    public function getIsHomeGallery()
    {
        return $this->isHomeGallery;
    }
}
