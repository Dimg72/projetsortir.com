<?php

namespace App\Form;



use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class FilterActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Campus', EntityType::class,[
                'class'=>Campus::class,
                'choice_label' => 'nom'

            ])
            ->add('Search', TextType::class, [
                'label' => 'Le nom de la sortie contient : ',
                'required' => false
            ])
            ->add('DateStart', DateType::class, [
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('DateEnd',DateType::class, [
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('Filter', ChoiceType::class,[
                'choices'=>[
                    'Sorties dont je suis l\'organisateur/trice' => 'Organisateur',
                    'Sorties auxquelles je suis incrit/e' => 'inscrit',
                    'Sorties auxquelles je ne suis pas incrit/e' => 'non inscrit',
                    'Sorties passés' => 'historique'
                ],
                'multiple' => true,
                'expanded'  =>true,
                'label'=> ' '


            ])
        ;
    }

}
