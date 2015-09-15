<?php

namespace TranslateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Language
 *
 * @ORM\Table(name="ter_language")
 * @ORM\Entity
 * @UniqueEntity("code")
 */
class Language
{

    use ORMBehaviors\Sluggable\Sluggable,
        ORMBehaviors\Timestampable\Timestampable,
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
     * @ORM\Column(name="name", type="string", length=45 ,nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="45")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=2, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="2")
     */
    private $code;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_locked", type="boolean")
     */
    private $locked = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_default", type="boolean")
     */
    private $default = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_published", type="boolean")
     */
    private $published = false;


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
     * Set name
     *
     * @param string $name
     * @return Language
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
     * Set code
     *
     * @param string $code
     * @return Language
     */
    public function setCode($code)
    {
        $this->code = strtolower($code);

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
     * Set isLocked
     *
     * @param boolean $isLocked
     * @return Language
     */
    public function setLocked($isLocked)
    {
        $this->isLocked = $isLocked;

        return $this;
    }

    /**
     * Get isLocked
     *
     * @return boolean 
     */
    public function isLocked()
    {
        return $this->locked;
    }

    /**
     * Set isDefault
     *
     * @param boolean $isDefault
     * @return Language
     */
    public function setDefault($isDefault)
    {
        $this->default = $isDefault;

        return $this;
    }

    /**
     * Get isDefault
     *
     * @return boolean 
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param boolean $isPublished
     * @return Language
     */
    public function setPublished($isPublished)
    {
        $this->published = $isPublished;

        return $this;
    }

    /**
     * Les champs qui vont composer le slug
     *
     * @return array
     */
    public function getSluggableFields()
    {
        return [ 'code', 'name' ];
    }

    /**
     * Génération du slug
     *
     * @param $values
     * @return string
     */
    public function generateSlugValue($values)
    {
        return strtolower(implode('-', $values));
    }
}
