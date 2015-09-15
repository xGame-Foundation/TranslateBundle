<?php

namespace TranslateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Entité pour l'ajout des traductions

 *
 * @ORM\Table(name="ter_wording_translation")
 * @ORM\Entity
 */
class WordingTranslation
{

    use ORMBehaviors\Translatable\Translation,
        ORMBehaviors\Loggable\Loggable;


    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text")
     */
    protected $value;

    /**
     * Set value
     *
     * @param string $value
     * @return Wording
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }
}
