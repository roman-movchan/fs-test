<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PersonDetail
 *
 * @ORM\Table(name="person_detail")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonDetailRepository")
 */
class PersonDetail
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
     * @ORM\OneToOne(targetEntity="Person", inversedBy="detail")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    protected $person;

    /**
     * @var int
     *
     * @ORM\Column(name="personId", type="integer", unique=true)
     */
    private $personId;

    /**
     * @var string
     *
     * @ORM\Column(name="iceCream", type="string", length=100)
     */
    private $iceCream;

    /**
     * @var string
     *
     * @ORM\Column(name="favSuperhero", type="string", length=100)
     */
    private $favSuperhero;

    /**
     * @var string
     *
     * @ORM\Column(name="favMovieStar", type="string", length=100)
     */
    private $favMovieStar;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="worldEnd", type="datetime")
     */
    private $worldEnd;

    /**
     * @var string
     *
     * @ORM\Column(name="superBrowl", type="string", length=100)
     */
    private $superBrowl;


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
     * Set personId
     *
     * @param integer $personId
     *
     * @return PersonDetail
     */
    public function setPersonId($personId)
    {
        $this->personId = $personId;

        return $this;
    }

    /**
     * Get personId
     *
     * @return int
     */
    public function getPersonId()
    {
        return $this->personId;
    }

    /**
     * Set iceCream
     *
     * @param string $iceCream
     *
     * @return PersonDetail
     */
    public function setIceCream($iceCream)
    {
        $this->iceCream = $iceCream;

        return $this;
    }

    /**
     * Get iceCream
     *
     * @return string
     */
    public function getIceCream()
    {
        return $this->iceCream;
    }

    /**
     * Set favSuperhero
     *
     * @param string $favSuperhero
     *
     * @return PersonDetail
     */
    public function setFavSuperhero($favSuperhero)
    {
        $this->favSuperhero = $favSuperhero;

        return $this;
    }

    /**
     * Get favSuperhero
     *
     * @return string
     */
    public function getFavSuperhero()
    {
        return $this->favSuperhero;
    }

    /**
     * Set favMovieStar
     *
     * @param string $favMovieStar
     *
     * @return PersonDetail
     */
    public function setFavMovieStar($favMovieStar)
    {
        $this->favMovieStar = $favMovieStar;

        return $this;
    }

    /**
     * Get favMovieStar
     *
     * @return string
     */
    public function getFavMovieStar()
    {
        return $this->favMovieStar;
    }

    /**
     * Set worldEnd
     *
     * @param \DateTime $worldEnd
     *
     * @return PersonDetail
     */
    public function setWorldEnd($worldEnd)
    {
        $this->worldEnd = $worldEnd;

        return $this;
    }

    /**
     * Get worldEnd
     *
     * @return \DateTime
     */
    public function getWorldEnd()
    {
        return $this->worldEnd;
    }

    /**
     * Set superBrowl
     *
     * @param string $superBrowl
     *
     * @return PersonDetail
     */
    public function setSuperBrowl($superBrowl)
    {
        $this->superBrowl = $superBrowl;

        return $this;
    }

    /**
     * Get superBrowl
     *
     * @return string
     */
    public function getSuperBrowl()
    {
        return $this->superBrowl;
    }
}

