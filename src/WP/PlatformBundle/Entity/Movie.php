<?php

namespace WP\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Movie
 *
 * @ORM\Table(name="movie")
 * @ORM\Entity(repositoryClass="WP\PlatformBundle\Repository\MovieRepository")
 */
class Movie {

    public function __construct() {
        $this->genres = new ArrayCollection();
        $this->directors = new ArrayCollection();
    }

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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="releasedate", type="date")
     */
    private $releasedate;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @ORM\ManyToMany(targetEntity="WP\PlatformBundle\Entity\Genre",cascade={"persist"})
     */
    private $genres;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="synopsis", type="text")
     */
    private $synopsis;

    /**
     * @ORM\OneToOne(targetEntity="WP\PlatformBundle\Entity\Image", cascade={"persist"})
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="WP\PlatformBundle\Entity\Director", inversedBy="movies", cascade={"persist"})
     * @ORM\JoinTable(name="movie_director")
     */
    private $directors;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Movie
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set releasedate
     *
     * @param \DateTime $releasedate
     *
     * @return Movie
     */
    public function setReleasedate($releasedate) {
        $this->releasedate = $releasedate;

        return $this;
    }

    /**
     * Get releasedate
     *
     * @return \DateTime
     */
    public function getReleasedate() {
        return $this->releasedate;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Movie
     */
    public function setDuration($duration) {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return int
     */
    public function getDuration() {
        return $this->duration;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Movie
     */
    public function setCountry($country) {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Set synopsis
     *
     * @param string $synopsis
     *
     * @return Movie
     */
    public function setSynopsis($synopsis) {
        $this->synopsis = $synopsis;

        return $this;
    }

    /**
     * Get synopsis
     *
     * @return string
     */
    public function getSynopsis() {
        return $this->synopsis;
    }

    public function setImage(Image $image) {
        $this->image = $image;
    }

    public function getImage() {
        return $this->image;
    }

    public function addDirectors(Director $director) {
        $this->directors[] = $director;
    }

    public function removeDirector(Director $director) {
        $this->directors->removeElement($director);
    }

    public function getDirectors() {
        return $this->directors;
    }

    public function addGenres(Genre $genre) {
        $this->genres[] = $genre;
    }

    public function removeGenre(Genre $genre) {
        $this->genres->removeElement($genre);
    }

    public function getGenres() {
        return $this->genres;
    }

}
