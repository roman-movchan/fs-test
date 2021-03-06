<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as JMS;
use Doctrine\Common\Collections as Collections;

/**
 * Person
 *
 * @ORM\Table(name="person")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonRepository")
 * @UniqueEntity("email")
 * @JMS\ExclusionPolicy("all")
 * @JMS\XmlRoot("person")
 */
class Person
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\XmlAttribute
     * @JMS\Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=100)
     * @Assert\NotBlank
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=100)
     * @Assert\NotBlank
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     * @Assert\Email()
     * @JMS\Expose
     * @JMS\XmlElement(cdata=false)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="datetime")
     * @Assert\NotBlank
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="shoeSize", type="decimal", precision=3, scale=1)
     * @Assert\NotBlank
     * @Assert\Range(
     *      min = 10,
     *      max = 48,
     * )
     */
    private $shoeSize;

    /**
     * @ORM\OneToOne(targetEntity="PersonDetail", mappedBy="person")
     * @JMS\Expose
     */
    private $personDetail;

    /**
     * @ORM\Column(name="ip_address", type="bigint", nullable = TRUE)
     */
    private $ipAddress;

    /**
     * @JMS\Expose
     * @ORM\OneToMany(targetEntity="ErrorLog", mappedBy="person")
     *
     */
    private $errors;

    public function __construct()
    {
        $this->errors = new Collections\ArrayCollection();
    }

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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Person
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Person
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Person
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Person
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set shoeSize
     *
     * @param string $shoeSize
     *
     * @return Person
     */
    public function setShoeSize($shoeSize)
    {
        $this->shoeSize = $shoeSize;

        return $this;
    }

    /**
     * Get shoeSize
     *
     * @return string
     */
    public function getShoeSize()
    {
        return $this->shoeSize;
    }



    /**
     * Set personDetail
     *
     * @param \AppBundle\Entity\PersonDetail $personDetail
     *
     * @return Person
     */
    public function setPersonDetail(\AppBundle\Entity\PersonDetail $personDetail = null)
    {
        $this->personDetail = $personDetail;

        return $this;
    }

    /**
     * Get personDetail
     *
     * @return \AppBundle\Entity\PersonDetail
     */
    public function getPersonDetail()
    {
        return $this->personDetail;
    }

    /**
     * Set ipAddress
     *
     * @param integer $ipAddress
     *
     * @return Person
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return integer
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("complete")
     * @return bool
     */
    public function isComplete()
    {
        return $this->getPersonDetail() ? true : false;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("ip")
     * @JMS\XmlElement(cdata=false)
     * @return string
     */
    public function ipString()
    {
        return long2ip($this->getIpAddress());
    }

    /**
     * Add error
     *
     * @param \AppBundle\Entity\ErrorLog $error
     *
     * @return Person
     */
    public function addError(\AppBundle\Entity\ErrorLog $error)
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * Remove error
     *
     * @param \AppBundle\Entity\ErrorLog $error
     */
    public function removeError(\AppBundle\Entity\ErrorLog $error)
    {
        $this->errors->removeElement($error);
    }

    /**
     * Get errors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
