<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('sites', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Site::class,
                'placeholder'=> "Tous les sites",
                'expanded' => false,
                'choice_label' => 'nom',
                'multiple' => false
            ])
           
            ->add('organisateur', CheckboxType::class, [
                'label' => 'Sorties que j\'organise',
                'required' => false,
            ])

            ->add('participant', CheckboxType::class, [
                'label' => 'Sorties auxquelles je participe',
                'required' => false,
            ])

            ->add('participant', CheckboxType::class, [
                'label' => 'Sorties auxuqelles je ne participe pas',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}