<?php

if (!defined('_PS_VERSION_')) exit;

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShopBundle\Entity\Repository\TabRepository;
use PrestaShop\Module\ProductLabel\Form\Modifier\ProductFormModifier;
use PrestaShop\Module\ProductLabel\Entity\ProductLabel as LabelEntity;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

class ProductLabel extends Module implements WidgetInterface
{

    public function __construct()
    {
        $this->name = 'productlabel';
        $this->version = '1.0.0';
        $this->author = 'Test Task';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Product Custom Labels');
        $this->description = $this->l('Add and manage custom labels for products.');
    }

    public function install()
    {
        return parent::install() &&
            $this->createDatabaseTable() &&
            $this->createJoinTable() &&
            $this->registerHook('displayProductExtraContent') &&
            $this->registerHook(['actionProductFormBuilderModifier']) &&
            $this->registerHook(['actionAfterUpdateProductFormHandler']) &&
            $this->registerHook(['displayAfterProductThumbs']) &&
            $this->registerHook('header') &&
            $this->installTab();
    }

    public function hookDisplayAfterProductThumbs($params)
    {
        if(!Configuration::get('PRODUCTLABEL_ENABLED')) {
            return;
        }

        $entityManager = $this->get('doctrine.orm.default_entity_manager');
        $dql = '
            SELECT l
            FROM PrestaShop\Module\ProductLabel\Entity\ProductLabel l
            JOIN l.products p
            WHERE p.id = :productId
            AND l.visible = true
        ';

        $query = $entityManager->createQuery($dql);
        $query->setParameter('productId', 1);

        $labels = $query->getResult();

        $moveToTitle = 0;
        if (Configuration::get('PRODUCTLABEL_POSITION') == 'above_title') {
            $moveToTitle = 1;
        }

        $this->context->smarty->assign([
            'labels' => $labels,
            'moveToTitle' => $moveToTitle
        ]);

        return $this->fetch('module:productlabel/views/templates/hook/product-labels.tpl');
    }



    public function hookActionProductFormBuilderModifier($params)
    {
        /** @var ProductFormModifier $productFormModifier */
        $productFormModifier = $this->get(ProductFormModifier::class);
        $productId = (int) $params['id'];

        $productFormModifier->modify($productId, $params['form_builder']);
    }

    public function hookActionAfterUpdateProductFormHandler($params)
    {
        $productId = $params['id'];
        $labelIds = $params['form_data']['description']['product_labels'] ?? [];

        $em = $this->get('doctrine.orm.entity_manager');
        $conn = $em->getConnection();

        $conn->executeStatement('DELETE FROM product_label_product WHERE product_id = :id', [
            'id' => $productId,
        ]);

        if (!empty($labelIds)) {

            $values = [];
            $params = [];

            foreach (array_values($labelIds) as $index => $labelId) {
                $values[] = "(:lid{$index}, :pid{$index})";
                $params["lid{$index}"] = $labelId;
                $params["pid{$index}"] = $productId;
            }

            $sql = 'INSERT INTO product_label_product (label_id, product_id) VALUES ' . implode(', ', $values);
            $conn->executeStatement($sql, $params);
        }
    }

    public function hookHeader()
    {
        if (Configuration::get('PRODUCTLABEL_ENABLED')) {
            $this->context->controller->registerStylesheet(
                'module-productlabel-style',
                'modules/' . $this->name . '/views/css/labels.css',
                ['media' => 'all', 'priority' => 150]
            );
        }
    }

    private function createDatabaseTable(): bool
    {
        $sql = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "product_label` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `color` VARCHAR(7) NOT NULL,
        `visible` TINYINT(1) DEFAULT 1
        ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8mb4;";

        return Db::getInstance()->execute($sql);
    }

    private function createJoinTable(): bool
    {
        $sql = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "product_label_product` (
        `label_id` INT NOT NULL,
        `product_id` INT UNSIGNED NOT NULL,
        PRIMARY KEY (`label_id`, `product_id`),
        FOREIGN KEY (`label_id`) REFERENCES `" . _DB_PREFIX_ . "product_label`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`product_id`) REFERENCES `" . _DB_PREFIX_ . "product`(`id_product`) ON DELETE CASCADE
        ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8mb4;";

        return \Db::getInstance()->execute($sql);
    }

    private function installTab()
    {
        /** @var TabRepository $tabRepository */
        $tabRepository = $this->get('prestashop.core.admin.tab.repository');

        $parentTab = $tabRepository->findOneByClassName('AdminCatalog');

        $tab = new Tab();
        $tab->class_name = 'AdminProductLabel';
        $tab->module = $this->name;
        $tab->id_parent = $parentTab->getId();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Product Custom Labels';
        }
        return $tab->save();
    }

    public function hookDisplayProductExtraContent($params)
    {
        return 'Label placeholder';
    }

    public function renderWidget($hookName, array $configuration)
    {
        return $this->hookDisplayProductExtraContent($configuration);
    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        return [];
    }

    public function getContent()
    {
        if (Tools::isSubmit('submitProductLabelConfig')) {
            Configuration::updateValue('PRODUCTLABEL_ENABLED', (bool) Tools::getValue('PRODUCTLABEL_ENABLED'));
            Configuration::updateValue('PRODUCTLABEL_POSITION', Tools::getValue('PRODUCTLABEL_POSITION'));
        }

        return $this->renderForm();
    }

    private function renderForm(): string
    {
        $defaultLang = (int) Configuration::get('PS_LANG_DEFAULT');

        $fieldsForm = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Product Label Settings'),
                ],
                'input' => [
                    [
                        'type' => 'switch',
                        'label' => $this->l('Enable labels'),
                        'name' => 'PRODUCTLABEL_ENABLED',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'enabled_on', 'value' => 1, 'label' => $this->l('Enabled')],
                            ['id' => 'enabled_off', 'value' => 0, 'label' => $this->l('Disabled')],
                        ],
                    ],
                    [
                        'type' => 'select',
                        'label' => $this->l('Display position'),
                        'name' => 'PRODUCTLABEL_POSITION',
                        'options' => [
                            'query' => [
                                ['id' => 'above_title', 'name' => $this->l('Above title')],
                                ['id' => 'below_image', 'name' => $this->l('Below image')],
                            ],
                            'id' => 'id',
                            'name' => 'name',
                        ],
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = $defaultLang;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitProductLabelConfig';
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->fields_value = [
            'PRODUCTLABEL_ENABLED' => (bool) Configuration::get('PRODUCTLABEL_ENABLED'),
            'PRODUCTLABEL_POSITION' => Configuration::get('PRODUCTLABEL_POSITION'),
        ];

        return $helper->generateForm([$fieldsForm]);
    }
}
