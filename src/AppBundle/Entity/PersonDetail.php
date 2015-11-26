<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * PersonDetail
 *
 * @ORM\Table(name="person_detail")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonDetailRepository")
 * @JMS\ExclusionPolicy("all")
 *
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
     * @ORM\OneToOne(targetEntity="Person", inversedBy="personDetail")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    /**
     * @var string
     *
     * @ORM\Column(name="ice_cream", type="string", length=100)
     * @Assert\NotBlank()
     * @JMS\Expose
     */
    private $iceCream;

    /**
     * @var string
     *
     * @ORM\Column(name="fav_superhero", type="string", length=100)
     * @Assert\NotBlank()
     * @JMS\Expose
     */
    private $favSuperhero;

    /**
     * @var string
     *
     * @ORM\Column(name="fav_movie_star", type="string", length=100)
     * @Assert\NotBlank()
     * @JMS\Expose
     */
    private $favMovieStar;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="world_end", type="datetime")
     * @Assert\NotBlank()
     * @JMS\Expose
     */
    private $worldEnd;

    /**
     * @var string
     *
     * @ORM\Column(name="super_bowl", type="string", length=100)
     * @Assert\NotBlank()
     * @JMS\Expose
     */
    private $superBowl;


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



    /**
     * Set person
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return PersonDetail
     */
    public function setPerson(\AppBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \AppBundle\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set superBowl
     *
     * @param string $superBowl
     *
     * @return PersonDetail
     */
    public function setSuperBowl($superBowl)
    {
        $this->superBowl = $superBowl;

        return $this;
    }

    /**
     * Get superBowl
     *
     * @return string
     */
    public function getSuperBowl()
    {
        return $this->superBowl;
    }
}
