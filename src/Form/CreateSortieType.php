<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('nom', TextType::class, [
                'label'=>'Nom de la sortie: '
            ])
            ->add('dateHeureDebut', DateType::class,[
                'label'=>'Date et heure de sortie: ',
                'html5'=>true,
                'widget'=>'single_text'
            ])
            ->add('duree',IntegerType::class,[
                'label'=>'Durée (min): '
            ])
            ->add('dateLimiteInscription',DateType::class, [
                'label'=>'Date limite d\'inscription: ',
                'html5'=>true,
                'widget'=>'single_text'
            ])
            ->add('nbInscriptionsMax',IntegerType::class,[
                'label'=>'Nombre de places'
            ])
            ->add('infosSortie', TextareaType::class,[
                'label'=>'Description et infos: '
            ])
            ->add('lieu',EntityType::class,[
                'class'=>Lieu::class,
                'choice_label'=> 'nom',
                'label'=>'Lieu: '
            ])
            ->add('ville', EntityType::class,[
                'class'=>Ville::class,
                'choice_label'=>'nom',
                'label'=>'Ville: '
            ])
            ->add('campus', TextType::class,[
                'label' => "Campus"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
