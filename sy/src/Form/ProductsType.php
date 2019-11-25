<?php

namespace App\Form;

use App\Entity\Products;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productsku')
            ->add('productname')
            ->add('productprice')
            ->add('productweight')
            ->add('productcartdesc')
            ->add('productshortdesc')
            ->add('productlongdesc')
            ->add('productthumb')
            ->add('productimage')
            ->add('productcategoryid')
            ->add('productupdatedate')
            ->add('productstock')
            ->add('productlive')
            ->add('productunlimited')
            ->add('productlocation')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
