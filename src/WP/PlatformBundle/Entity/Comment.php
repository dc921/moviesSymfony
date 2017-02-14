<?php

namespace WP\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="WP\PlatformBundle\Repository\CommentRepository")
 */
class Comment {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

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
    public function getId() {
        return $this->id;
    }

    public function setComment($comment) {
        $this->comment = $comment;

        return $this;
    }

    public function getComment() {
        return $this->comment;
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
