<?php

namespace TranslateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Entité pour l'ajout des traductions
 *
 * @ORM\Table(
*       name="ter_wording",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="uniq_wording",
 *              columns={
 *                  "domain_id", "code"
 *              }
 *          )
 *      }
 * )
 * @ORM\Entity
 * @UniqueEntity({"domain", "code"})
 */
class Wording
{

    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable,
        ORMBehaviors\Loggable\Loggable;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Domain", inversedBy="wordings", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="domain_id", referencedColumnName="id")
     */
    private $domain;


    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex("/^[A-z.]+$/")
     * @Assert\Length(min="3", max="45")
     */
    private $code;


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
     * @return Wording
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
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     * @return Wording
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }
}
