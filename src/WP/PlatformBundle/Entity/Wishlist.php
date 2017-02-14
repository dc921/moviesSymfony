<?php

namespace WP\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wishlist
 *
 * @ORM\Table(name="wishlist")
 * @ORM\Entity(repositoryClass="WP\PlatformBundle\Repository\WishlistRepository")
 */
class Wishlist
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
     * @ORM\Column(name="wish", type="boolean")
     */
    private $wish;

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
     * Set wish
     *
     * @param boolean $wish
     *
     * @return Wishlist
     */
    public function setWish($wish)
    {
        $this->wish = $wish;

        return $this;
    }

    /**
     * Get wish
     *
     * @return bool
     */
    public function getWish()
    {
        return $this->wish;
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

