<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductLabel\Controller\Admin;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use PrestaShop\PrestaShop\Core\Grid\GridFactoryInterface;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteria;

class ProductLabelController extends FrameworkBundleAdminController
{
    public function index(): Response
    {
        /** @var GridFactoryInterface $gridFactory */
        $gridFactory = $this->get('productlabel.grid.product_label_grid_factory');
        $grid = $gridFactory->getGrid(new SearchCriteria());
        $gridView = $this->presentGrid($grid);


        return $this->render('@Modules/productlabel/views/templates/admin/label/index.html.twig', [
            'message' => 'Label admin works!',
            'grid' => $gridView,
        ]);
    }

    public function edit(int $id, Request $request): Response
    {
        return $this->render('@Modules/productlabel/views/templates/admin/label/edit.html.twig', [
            'message' => 'Label admin edit works!',
        ]);
    }
}
