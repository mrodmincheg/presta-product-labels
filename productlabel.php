<?php

if (!defined('_PS_VERSION_')) exit;

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShopBundle\Entity\Repository\TabRepository;
use PrestaShop\Module\ProductLabel\Form\Modifier\ProductFormModifier;

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
            $this->installTab();
    }

    public function hookActionProductFormBuilderModifier($params)
    {
        /** @var ProductFormModifier $productFormModifier */
        $productFormModifier = $this->get(ProductFormModifier::class);
        $productId = (int) $params['id'];

        $productFormModifier->modify($productId, $params['form_builder']);

    }

    private function createDatabaseTable(): bool
    {
        $sql = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "product_label` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `color` VARCHAR(7) NOT NULL,
        `visible` TINYINT(1) NOT NULL DEFAULT 1
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
}
