<?php

namespace PrestaShop\Module\ProductLabel\Form;

use PrestaShop\Module\ProductLabel\Entity\ProductLabel;
use PrestaShop\Module\ProductLabel\Validator\Constraints\ValidHexColor;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
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
                'attr' => [
                    'class' => 'form-control form-control-color',
                    'style' => 'max-width: 100px; padding: 0; height: 38px;'
                ],
                'constraints' => [
                    new ValidHexColor(),
                ],
            ])
            ->add('visible', SwitchType::class, [
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
