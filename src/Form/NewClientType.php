<?php

namespace App\Form;

use App\Entity\Cliente;
use App\Validator\Instagram;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dateTime = date('Y-m-d H:i:s');
        $builder
            ->add('Nombre')
            ->add('Apellidos')
            ->add('Direccion', TextareaType::class)
            ->add('Email', EmailType::class)
            ->add('Instagram');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cliente::class,
        ]);
    }
}
