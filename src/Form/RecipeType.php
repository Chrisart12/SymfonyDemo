<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Category;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'title',
                // 'constraints' => new Length(min: 5)
            ])
            ->add('slug',  TextType::class, [
                'label' => 'slug',
                'required' => false,
                // 'constraints' => [
                //     new Length(min: 5),
                //     new Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: 'Ceci n\'est pas un slug valable')
                // ]
            ])
            // recipeFile est le nom associé à l'image à uploader mais pas le nom enregistré en base de données
            ->add('recipeFile', FileType::class, array('data_class' => null)) // Important d'ajouter array('data_class' => null)
            // ->add('recipeFilename', FileType::class, [
            //     'label' => 'Image',
            //     'mapped' => false,
            //     'constraints' => [
            //         new Image()
            //     ]
            // ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'content'
            ])
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('updatedAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('duration')
            ->add('save', SubmitType::class, [
                'label' => 'envoyer'
            ])
            // Permet de gérer automatiquement les slugs
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoSlug(...) )
        ;
    }

    public function autoSlug(PreSubmitEvent $event): void
    {
        $data = $event->getData();

        $slugger = new AsciiSlugger();
        $data['slug'] = strtolower($slugger->slug($data['title']));

        $event->setData($data);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
