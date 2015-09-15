<?php

namespace TranslateBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class LanguageType
 * @package TranslateBundle\Form
 */
class LanguageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'form.lang_name',
                'translation_domain' => 'TerTranslateBundle',
            ))
            ->add('code', 'text', array(
                'label' => 'form.lang_code',
                'translation_domain' => 'TerTranslateBundle',
            ))
            ->add('published', 'checkbox', array(
                'label' => 'form.lang_published',
                'translation_domain' => 'TerTranslateBundle',
                'required' => false
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TranslateBundle\Entity\Language'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ter_bundle_translatebundle_language';
    }
}
