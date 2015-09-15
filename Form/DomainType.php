<?php

namespace TranslateBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DomainType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', 'text', array(
                'label' => 'form.domain_code',
                'translation_domain' => 'TerTranslateBundle',
            ))
            ->add('name', 'text', array(
                'label' => 'form.domain_name',
                'translation_domain' => 'TerTranslateBundle',
            ))
            ->add('description', 'textarea', array(
                'label' => 'form.domain_description',
                'translation_domain' => 'TerTranslateBundle',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TranslateBundle\Entity\Domain'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ter_bundle_translatebundle_domain';
    }
}
