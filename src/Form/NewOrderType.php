<?php

namespace App\Form;

use App\Entity\Pedidos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('PrecioFinal')
            ->add('Articulos')
            ->add('Enviado')
            ->add('Tracking')
            ->add('Devuelto')
            ->add('Recibido')
            ->add('FechaCreacion')
            ->add('FechaRecibido')
            ->add('IdCliente')
            ->add('factura')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pedidos::class,
        ]);
    }
}
