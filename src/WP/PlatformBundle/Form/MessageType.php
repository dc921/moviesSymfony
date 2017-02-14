<?php

namespace WP\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, array('required'=>true,'label'=>'pseudo','attr'=>array('placeholder'=>'Entrez votre pseudo')))
            ->add('mail', EmailType::class, array('required'=>true,'label'=>'Email', 'attr'=>array('placeholder'=>'Entrez votre email')))
            ->add('subject', TextType::class, array('required'=>true,'label'=>'Sujet', 'attr'=>array('placeholder'=>'Entrez le sujet de votre message')))
            ->add('message', TextareaType::class, array('required'=>true,'label'=>'Message','attr'=>array('placeholder'=>'Entrez votre message')))
            ->add('submit', SubmitType::class, array('label'=>'Envoyer'))
            ->setMethod('POST')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WP\PlatformBundle\Entity\Message'
        ));
    }
}
