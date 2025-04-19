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

        $toolbarButtons = [
            'add' => [
                'href' => $this->generateUrl('admin_product_label_create'),
                'desc' => $this->trans('Add new label', 'Admin.Actions', []),
                'icon' => 'add_circle',
            ],
        ];


        return $this->render('@Modules/productlabel/views/templates/admin/label/index.html.twig', [
            'message' => 'Label admin works!',
            'grid' => $gridView,
            'layoutHeaderToolbarBtn' => $toolbarButtons
        ]);
    }

    public function edit(Request $request, ?int $id = null): Response
    {
        $em = $this->getDoctrine()->getManager();

        $label = $id
            ? $em->getRepository(ProductLabel::class)->find($id)
            : new ProductLabel();

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
