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
            ->add('username', TextType::class, $this->getConfiguration("Username", "Veuillez saisir votre username",[
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le username ne peut pas être vide',
                    ]),
                    
                    
                ]
            ]))
            ->add('plainPassword',PasswordType::class, $this->getConfiguration("Mot de passe", "Rentrez votre mot de passe",  [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'required' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe ne peut pas être vide',
                    ]),
                    new Length([
                        'minMessage' => 'Le mot de passe doit faire minimum 5 caractères',
                        'maxMessage' => 'Le mot de passe doit faire maximum 20 caractères',
                        'min' => 5,
                        'max' => 20

                    ])
                    
                ],
            ]))
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
