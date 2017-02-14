<?php

namespace WP\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use WP\PlatformBundle\Entity\Image as Image;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="WP\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToOne(targetEntity="WP\PlatformBundle\Entity\Image", cascade={"persist"})
     */
    private $image;
    
    public function setImage(Image $image) {
        $this->image = $image;
        
        return $this;
    }
    
    public function getImage() {
        return $this->image;
    }
}

