<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductLabel\Controller\Admin;

use PrestaShop\Module\ProductLabel\Entity\ProductLabel;
use PrestaShop\Module\ProductLabel\Form\ProductLabelType;
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

    public function edit(ProductLabel $label, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ProductLabelType::class, $label);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($label);
            $em->flush();
    
            $this->addFlash('success', 'Label saved.');
    
            return $this->redirectToRoute('admin_product_label_edit', ['id' => $label->getId()]);
        }
    
        return $this->render('@Modules/productlabel/views/templates/admin/label/edit.html.twig', [
            'label' => $label,
            'form' => $form->createView()
        ]);
    }
}
