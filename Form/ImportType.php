<?php

namespace Httpi\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImportType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'attr' => array(
                    'size' => 103,
                    'class' => 'input text'
                )
            ))
            ->add('description', 'textarea', array(
                'attr' => array(
                    'cols' => 100,
                    'rows' => 10,
                    'class' => 'input textarea'
                )
            ))
            ->add('withDuplicates', 'choice', array(
                'mapped' => false,
                //'expanded' => true,
                'choices' => array(
                    "overwrite" => "Overwrite data",
                    "ignore" => "Ignore data",
                    "duplicate" => "Add duplicate"
                ),
                'attr' => array(
                    'class' => 'input select'
                )
            ))
            ->add('fileIds', 'hidden', array(
                'mapped' => false,
                'attr' => array(
                    'class' => 'input hidden'
                )
            ))
            ->add('Import', 'submit');
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Httpi\Bundle\CoreBundle\Entity\Import'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'httpi_bundle_corebundle_import';
    }
}
