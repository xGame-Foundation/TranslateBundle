<?php

namespace TranslateBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WordingType extends TranslateAbstractType
{

    protected $showDomain = true;

    public function __construct($locales = array(), $localeDefaut = null, $showDomain = true)
    {
        $this->locales = $locales;
        $this->localeDefaut = $localeDefaut;
        $this->showDomain = $showDomain;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('code', 'text', array(
            'label' => 'label.wording_code',
            'translation_domain' => 'TerTranslateBundle',
        ));

        if ($this->showDomain) {
            $builder->add('domain', 'entity', array(
                'label' => 'label.wording_domain_name',
                'translation_domain' => 'TerTranslateBundle',
                'class' => 'TranslateBundle\Entity\Domain'
            ));
        }

        $builder->add('translations', 'a2lix_translations', array(
            'locales' => $this->locales,
            'required_locales' => array($this->localeDefaut),
            'default_locale' => $this->localeDefaut,
            'label' => 'label.translate',
            'translation_domain' => 'TerTranslateBundle',
            'fields' => array(
                'value' => array(
                    'label' => 'label.wording_value'
                )
            )
        ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TranslateBundle\Entity\Wording'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ter_bundle_translatebundle_wording';
    }
}
