<?php

namespace PrestaShop\Module\ProductLabel\Form;

use PrestaShop\Module\ProductLabel\Entity\ProductLabel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductLabelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Label Name',
                'required' => true,
            ])
            ->add('color', ColorType::class, [
                'label' => 'Color',
                'required' => true,
            ])
            ->add('visible', CheckboxType::class, [
                'label' => 'Visible',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductLabel::class,
        ]);
    }
}
