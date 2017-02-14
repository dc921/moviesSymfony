<?php

namespace WP\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WP\PlatformBundle\Form\ImageType;

class MovieType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title', TextType::class, array('required' => true, 'label' => 'Titre', 'attr' => array('placeholder' => 'Entrez le titre du film')))
                ->add('releasedate', DateType::class, array('widget' => 'single_text', 'required' => true, 'label' => 'Date de sortie'))
                ->add('duration', IntegerType::class, array('required' => true, 'label' => 'Durée', 'attr' => array('placeholder' => 'Entrez la durée du film')))
                ->add('genres', EntityType::class, array(
                    'class' => 'WPPlatformBundle:Genre',
                    'choice_label' => 'genre',
                    'label' => 'Genres',
                    'multiple' => true,
                    'expanded' => true
                ))
                ->add('country', TextType::class, array('required' => true, 'label' => 'Pays', 'attr' => array('placeholder' => 'Entrez le pays d\'origine du film')))
                ->add('synopsis', TextareaType::class, array('required' => true, 'label' => 'Synopsis', 'attr' => array('placeholder' => 'Entrez le synopsis du film')))
                ->add('image', ImageType::class, array('required' => true, 'label' => 'Affiche'))
                ->add('directors', EntityType::class, array(
                    'class' => 'WPPlatformBundle:Director',
                    'choice_label' => function($director) {
                        return $director->getDisplayName();
                    },
                    'label' => 'Réalisateur',
                    'multiple' => true
                ))
                ->add('submit', SubmitType::class, array('label' => 'Ajouter'))
                ->setMethod('POST')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'WP\PlatformBundle\Entity\Movie'
        ));
    }

}
