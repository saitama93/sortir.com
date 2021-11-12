<?php

namespace App\Form;


use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Sortie;
use App\Form\LieuType;
use App\Entity\Site;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, $this->getConfiguration("Nom", "Nom de la sortie"))
            ->add('dateHeureDebut', DateTimeType::class, $this->getConfiguration("Heure de début", "Heure de début de la sortie", [
                "widget" => "single_text"
            ]))
            ->add('dateHeureFin', DateTimeType::class, $this->getConfiguration("Heure de fin", "Heure de fin de la sortie", [
                "widget" => "single_text"
            ]))
            ->add('nbInscriptionsMax', IntegerType::class, $this->getConfiguration("Maximum d'inscriptions", "Maximum d'inscriptions de la sortie"))
            ->add('dateLimiteInscription', DateTimeType::class, $this->getConfiguration("Date limite", "Date limite de l'inscription", [
                "widget" => "single_text"
            ]))
            ->add('infoSortie', TextareaType::class, $this->getConfiguration("Informations", "Informations complémentaires sur la sortie"))
            ->add('lieu', LieuType::class)
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
