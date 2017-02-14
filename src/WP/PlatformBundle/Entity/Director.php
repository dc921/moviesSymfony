<?php

namespace WP\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Director
 *
 * @ORM\Table(name="director")
 * @ORM\Entity(repositoryClass="WP\PlatformBundle\Repository\DirectorRepository")
 */
class Director
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth", type="date")
     */
    private $birth;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="death", type="date", nullable=true)
     */
    private $death;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @ORM\OneToOne(targetEntity="WP\PlatformBundle\Entity\Image", cascade={"persist"})
     */
    private $image;
    
    /**
     * @ORM\ManyToMany(targetEntity="WP\PlatformBundle\Entity\Movie", mappedBy="directors", cascade={"persist"})
     * @ORM\JoinTable(name="movie_director")
     */
    private $movies;
    
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
     * Set name
     *
     * @param string $name
     *
     * @return Director
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Director
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set birth
     *
     * @param \DateTime $birth
     *
     * @return Director
     */
    public function setBirth($birth)
    {
        $this->birth = $birth;

        return $this;
    }

    /**
     * Get birth
     *
     * @return \DateTime
     */
    public function getBirth()
    {
        return $this->birth;
    }

    /**
     * Set death
     *
     * @param \DateTime $death
     *
     * @return Director
     */
    public function setDeath($death)
    {
        $this->death = $death;

        return $this;
    }

    /**
     * Get death
     *
     * @return \DateTime
     */
    public function getDeath()
    {
        return $this->death;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Director
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }
    
    public function setImage(Image $image) {
        $this->image = $image;
    }
    
    public function getImage() {
        return $this->image;
    }
    
    public function addMovies(Movie $movie) {
        $this->movies[] = $movie;
    }
    
    public function removeMovie(Movie $movie) {
        $this->movies->removeElement($movie);
    }
    
    public function getMovies() {
        return $this->movies;
    }
    
    public function getDisplayName() {
        return $this->firstname.' '.$this->name;
    }
}

