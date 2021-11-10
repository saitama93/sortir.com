<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, $this->getConfiguration("Username", "Veuillez saisir votre username"))
            ->add('plainPassword',PasswordType::class, $this->getConfiguration("Mot de passe", "Rentrez votre mot de passe",  [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
            ]))
            ->add('mail', EmailType:: class, $this->getConfiguration("Adresse mail", "Saisir votre adresse mail"))
            ->add('telephone', TextType::class, $this->getConfiguration("Numéro de téléphone", "Saisir votre numéro"))
            ->add('nom', TextType::class, $this->getConfiguration("Nom", "Saisir votre nom"))
            ->add('prenom', TextType::class, $this->getConfiguration("Prénom", "Saisir votre prénom"))
            ->add('isActif', CheckboxType::class, $this->getConfiguration("Cocher pour activer le compte", "" , [
                'mapped' => true
            ]))
            ->add('isAdmin', CheckboxType::class, $this->getConfiguration("Cocher si c'est un compte administrateur", "", [
                'required' => false
            ]))
            ->add('agreeTerms', CheckboxType::class, $this->getConfiguration("Termes", "", [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos termes',
                    ]),
                ],
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
