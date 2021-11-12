<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Site;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdateAccountForm extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mail', EmailType:: class, $this->getConfiguration("Adresse mail", "Saisir votre adresse mail",[
                'required' => false
            ]))
            ->add('telephone', TextType::class, $this->getConfiguration("Numéro de téléphone", "Saisir votre numéro", [
                'required' => false
            ]))
            ->add('nom', TextType::class, $this->getConfiguration("Nom", "Saisir votre nom", [
                'required' => false
            ]))
            ->add('prenom', TextType::class, $this->getConfiguration("Prénom", "Saisir votre prénom",[
                'required' => false
            ]))
            ->add('siteRattachement', EntityType::class, [
                // looks for choices from this entity
                'class' => Site::class,

                'required'   => true,
                'placeholder' => '- Choisir un site -',
            
                // uses the User.username property as the visible option string
                'choice_label' => 'nom',
            
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
