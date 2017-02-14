<?php

namespace WP\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Viewlist
 *
 * @ORM\Table(name="viewlist")
 * @ORM\Entity(repositoryClass="WP\PlatformBundle\Repository\ViewlistRepository")
 */
class Viewlist
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="view", type="boolean")
     */
    private $view;

    /**
     * @ORM\ManyToOne(targetEntity="WP\PlatformBundle\Entity\Movie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie;

    /**
     * @ORM\ManyToOne(targetEntity="WP\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set view
     *
     * @param boolean $view
     *
     * @return Viewlist
     */
    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Get view
     *
     * @return bool
     */
    public function getView()
    {
        return $this->view;
    }
    
    public function setMovie($movie) {
        $this->movie = $movie;
        
        return $this;
    }
    
    public function getMovie() {
        return $this->movie;
    }
    
    public function setUser($user) {
        $this->user = $user;
        
        return $this->user;
    }
    
    public function getUser() {
        return $this->user;
    }
}

