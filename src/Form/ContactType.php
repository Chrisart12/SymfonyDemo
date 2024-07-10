<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', TextType::class, [
                'label' => 'subject',
            ])
            ->add('service', ChoiceType::class, [
                'choices' => [
                    'counted' => 'compta@gmail.com',
                    'management' => 'gestion@gmail.com',
                    'direction' => 'direction@gmail.com',
                ],
                'label' => 'office',
            ])
            ->add('email', EmailType::class, [
                'label' => 'email',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'message',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
