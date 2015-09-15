<?php

namespace TranslateBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Domain
 *
 * @ORM\Table(name="ter_domain")
 * @ORM\Entity(repositoryClass="TranslateBundle\Entity\DomainRepository")
 * @UniqueEntity("code")
 */
class Domain
{

    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Loggable\Loggable;

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
     * @ORM\Column(name="code", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex("/^[A-z.]+$/")
     * @Assert\Length(min="3", max="45")
     *
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45)
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="45")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Assert\Length(max="255")
     */
    private $description;


    /**
     * @ORM\OneToMany(targetEntity="Wording", mappedBy="domain", cascade={"persist", "remove", "merge"})
     **/
    private $wordings;


    public function __construct()
    {
        $this->wordings = new ArrayCollection();
    }

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
     * Set code
     *
     * @param string $code
     * @return Domain
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Domain
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
     * Set description
     *
     * @param string $description
     * @return Domain
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getWordings()
    {
        return $this->wordings;
    }

    /**
     * @param mixed $wordings
     */
    public function setWordings($wordings)
    {
        $this->wordings = $wordings;
    }


    public function __toString()
    {
        return $this->code . ' - ' . $this->name;
    }
}
