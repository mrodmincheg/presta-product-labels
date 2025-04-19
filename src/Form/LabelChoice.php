<?php

namespace PrestaShop\Module\ProductLabel\Form;

use PrestaShop\PrestaShop\Core\Form\ConfigurableFormChoiceProviderInterface;

class LabelChoice implements ConfigurableFormChoiceProviderInterface
{
    public function getChoices(array $options)
    {
        return ['1' => 'test'];
    }
}
