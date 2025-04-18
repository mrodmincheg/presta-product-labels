<?php

namespace PrestaShop\Module\ProductLabel\Grid\Data;

use PrestaShop\PrestaShop\Core\Grid\Data\Factory\GridDataFactoryInterface;
use PrestaShop\PrestaShop\Core\Grid\Data\GridData;
use PrestaShop\Module\ProductLabel\Entity\ProductLabel;
use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;
use PrestaShop\PrestaShop\Core\Grid\Record\RecordCollection;


class ProductLabelGridDataFactory implements GridDataFactoryInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getData(SearchCriteriaInterface $searchCriteria)
    {
        $labels = $this->em->getRepository(ProductLabel::class)->findAll();

        $records = [];
        foreach ($labels as $label) {
            $records[] = [
                'id' => $label->id,
                'name' => $label->name,
                'color' => $label->color,
                'visible' => $label->visible ? 'Yes' : 'No',
            ];
        }

        return new GridData(new RecordCollection($records), count($records));
    }
}
