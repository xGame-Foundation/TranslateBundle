<?php
/**
 * Created by PhpStorm.
 * User: tdubuffet
 * Date: 30/06/15
 * Time: 12:42
 */

namespace TranslateBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\FormBuilderInterface;

abstract class TranslateAbstractType extends AbstractType
{
    /** @var array Les locales pour les traductions */
    protected $locales      = array();

    /** @var array La locale par defaut */
    protected $localeDefaut  = null;

    public function __construct($locales = array(), $localeDefaut = null)
    {
        $this->locales = $locales;
        $this->localeDefaut = $localeDefaut;

    }
}