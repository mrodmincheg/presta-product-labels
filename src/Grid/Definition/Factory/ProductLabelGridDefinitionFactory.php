<?php

namespace PrestaShop\Module\ProductLabel\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\GridActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\DataColumn;

class ProductLabelGridDefinitionFactory extends AbstractGridDefinitionFactory
{
    protected function getName()
    {
        return 'Product label';
    }

    public function getId(): string
    {
        return 'product_label';
    }

    protected function getColumns(): ColumnCollection
    {
        return (new ColumnCollection())
            ->add((new DataColumn('id'))->setName('ID')->setOptions(['field' => 'id']))
            ->add((new DataColumn('name'))->setName('Name')->setOptions(['field' => 'name']))
            ->add((new DataColumn('color'))->setName('Color')->setOptions(['field' => 'color']))
            ->add((new DataColumn('visible'))->setName('Visible')->setOptions(['field' => 'visible']))
            ->add(
                (new ActionColumn('actions'))
                    ->setName($this->trans('Actions', [], 'Admin.Global'))
                    ->setOptions([
                        'actions' => (new RowActionCollection())
                            ->add(
                                (new LinkRowAction('edit'))
                                    ->setName('Edit')
                                    ->setIcon('edit')
                                    ->setOptions([
                                        'route' => 'admin_product_label_edit',
                                        'route_param_name' => 'id',
                                        'route_param_field' => 'id',
                                    ])
                            )
                    ])
            );
    }

    protected function getFilters(): FilterCollection
    {
        return new FilterCollection();
    }

    protected function getGridActions(): GridActionCollection
    {
        return new GridActionCollection();
    }
}
