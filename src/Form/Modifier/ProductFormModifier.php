<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductLabel\Form\Modifier;

use PrestaShopBundle\Form\FormBuilderModifier;
use PrestaShop\Module\ProductLabel\Entity\ProductLabel;
use PrestaShop\Module\ProductLabel\Form\LabelChoice;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PrestaShop\Module\ProductLabel\Repository\ProductLabelRepository;
use PrestaShop\PrestaShop\Core\Form\ConfigurableFormChoiceProviderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class ProductFormModifier
{

    private FormBuilderModifier $formBuilderModifier;

    private ProductLabelRepository $productLabelRepository;

    private ConfigurableFormChoiceProviderInterface $labelChoice;


    public function __construct(
        FormBuilderModifier $formBuilderModifier,
        ProductLabelRepository $productLabelRepository,
        ConfigurableFormChoiceProviderInterface $labelChoice
    ) {
        $this->formBuilderModifier = $formBuilderModifier;
        $this->productLabelRepository = $productLabelRepository;
        $this->labelChoice = $labelChoice;
    }


    public function modify(
        int $productId,
        FormBuilderInterface $productFormBuilder
    ): void {

        $allLabels = $this->productLabelRepository->findAll();
        $allSelected = $this->productLabelRepository->findByProductId($productId);


        $choices = $this->labelChoice->getChoices($allLabels);
        $data = $this->labelChoice->getChoices($allSelected);

        $seoTabFormBuilder = $productFormBuilder->get('description');
        $this->formBuilderModifier->addAfter(
            $seoTabFormBuilder, 
            'description', 
            'product_labels', 
            ChoiceType::class, [
                'placeholder' => 'Select labels',
                'multiple' => true,
                'required' => false,
                'label' => 'Product Labels',
                'choices' => $choices,
                'data' => $data,
                'attr' => [
                    'data-toggle' => 'select2',
                    'data-minimumResultsForSearch' => '2',
                ],
            ]
        );
    }

    protected function getSelectedLabels(array $labels)
    {
        $selectedLabels = [];

        foreach ($labels as $row) {
            if ($row['selected']) {
                $selectedLabels[] = $row['label'];
            }
        }

        return $selectedLabels;
    }
}
