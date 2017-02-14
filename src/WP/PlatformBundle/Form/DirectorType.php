<?php

namespace WP\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WP\PlatformBundle\Form\ImageType;

class DirectorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('required'=>true,'label'=>'Nom'))
            ->add('firstname', TextType::class, array('required'=>true,'label'=>'Prénom'))
            ->add('birth', DateType::class, array('widget' => 'single_text','required'=>true,'label'=>'Date de naissance'))
            ->add('death', DateType::class, array('widget' => 'single_text','required'=>false,'label'=>'Date de mort'))
            ->add('country', TextType::class, array('label'=>'Pays d\'origine'))
            ->add('image', ImageType::class, array('required'=>true,'label'=>'Tronche'))
            ->add('submit', SubmitType::class, array('label'=>'Ajouter'))
            ->setMethod('POST')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WP\PlatformBundle\Entity\Director'
        ));
    }
}
