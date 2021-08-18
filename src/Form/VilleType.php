<?php

namespace App\Form;

use App\Entity\Ville;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class VilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom doit être renseigné',
                    ]),
                ]
        ])
            ->add('codePostal', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le code postal est obligatoire'
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le code postal doit contenir 5 chiffres',
                        'max'=> 5,
                        'maxMessage'=> 'Le code postal doit contenir 5 chiffres',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ville::class,
        ]);
    }
}
