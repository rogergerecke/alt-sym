<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    { //todo remove manuel translate to de
        $builder
            ->add(
                'name',
                null,
                [
                    'attr'     => ['placeholder' => 'Dein Name'],
                    'required' => true,
                    'label'    => false,
                ]
            )
            ->add('email', null, ['attr' => ['placeholder' => 'Email'], 'label' => false,])
            ->add(
                'agreeTerms',
                CheckboxType::class,
                [
                    'mapped'      => false,
                    'constraints' => [
                        new IsTrue(
                            [
                                'message' => 'please.agree.terms.of.use',
                            ]
                        ),
                    ],
                    'label'       => 'please.agree:',


                ]
            )
            ->add(
                'plainPassword',
                PasswordType::class,
                [
                    'attr'        => ['placeholder' => 'Password'],
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'mapped'      => false,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'Bitte ein Password eingeben',
                            ]
                        ),
                        new Length(
                            [
                                'min'        => 6,
                                'minMessage' => 'Your password should be at least {{ limit }} characters',
                                // max length allowed by Symfony for security reasons
                                'max'        => 4096,
                            ]
                        ),
                    ],
                    'label'       => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
