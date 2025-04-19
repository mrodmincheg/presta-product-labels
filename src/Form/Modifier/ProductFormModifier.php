<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductLabel\Form\Modifier;

use PrestaShopBundle\Form\FormBuilderModifier;
use PrestaShop\Module\ProductLabel\Entity\ProductLabel;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PrestaShop\Module\ProductLabel\Repository\ProductLabelRepository;

final class ProductFormModifier
{

    private FormBuilderModifier $formBuilderModifier;

    private ProductLabelRepository $productLabelRepository;


    public function __construct(
        FormBuilderModifier $formBuilderModifier,
        ProductLabelRepository $productLabelRepository
    ) {
        $this->formBuilderModifier = $formBuilderModifier;
        $this->productLabelRepository = $productLabelRepository;
    }


    public function modify(
        int $productId,
        FormBuilderInterface $productFormBuilder
    ): void {

        $assignedLabels = $this->productLabelRepository->findByProductId($productId);

        $seoTabFormBuilder = $productFormBuilder->get('description');
        $this->formBuilderModifier->addAfter(
            $seoTabFormBuilder, 
            'description', 
            'demo_module_custom_field', 
            EntityType::class, [
                'class' => ProductLabel::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'label' => 'Product Labels',
                'data' => $assignedLabels,
                'attr' => ['class' => 'form-control chosen'],
            ]
        );
    }
}
