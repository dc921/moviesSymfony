<?php

namespace WP\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use WP\PlatformBundle\Form\ImageType;

class UserType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('username', null, array('label' => 'Pseudo', 'translation_domain' => 'FOSUserBundle', 'attr' => array('placeholder' => 'Entrez votre pseudo')))
                ->add('email', EmailType::class, array('label' => 'Email', 'translation_domain' => 'FOSUserBundle', 'attr' => array('placeholder' => 'Entrez votre email')))
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'options' => array('translation_domain' => 'FOSUserBundle'),
                    'first_options' => array('label' => 'Mot de passe', 'attr' => array('placeholder' => 'Entrez votre mot de passe')),
                    'second_options' => array('label' => 'Confirmation du mot de passe', 'attr' => array('placeholder' => 'Confirmez votre mot de passe')),
                    'invalid_message' => 'Les mots de passe ne correspondent pas.',
                ))
                ->add('image', ImageType::class, array('label'=>'Avatar'))
                ->add('submit', SubmitType::class, array('label' => 'S\'inscrire'))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'WP\UserBundle\Entity\User'
        ));
    }

}
