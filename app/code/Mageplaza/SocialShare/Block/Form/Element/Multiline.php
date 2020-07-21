<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mageplaza\SocialShare\Block\Form\Element;

use Magento\Ui\Component\Form\Element\AbstractElement;
use Magento\Ui\Component\Form\Field;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

/**
 * @api
 * @since 100.0.2
 */
class Multiline extends AbstractElement
{
	const NAME = 'multiline';

	const FORM_ELEMENT = 'input';

	const DATA_TYPE = 'text';

	/**
	 * UI component factory
	 *
	 * @var UiComponentFactory
	 */
	protected $uiComponentFactory;

	/**
	 * Constructor
	 *
	 * @param ContextInterface $context
	 * @param UiComponentFactory $uiComponentFactory
	 * @param UiComponentInterface[] $components
	 * @param array $data
	 */
	public function __construct(
		ContextInterface $context,
		UiComponentFactory $uiComponentFactory,
		array $components = [],
		array $data = []
	) {
		$this->uiComponentFactory = $uiComponentFactory;
		parent::__construct($context, $components, $data);
	}

	/**
	 * Get component name
	 *
	 * @return string
	 */
	public function getComponentName()
	{
		return static::NAME;
	}

	/**
	 * Prepare component configuration
	 *
	 * @return void
	 */
	public function prepare()
	{
		$size = abs((int) $this->getData('config/size'));
		$validation = [$this->getData('config/validation')];

		if ($this->context->getNamespace() == 'customer_address_form' && $this->getName() == 'street')
		{
			$config = $this->getData('config');
			$config['sortOrder'] = 100;
			$this->setData('config', (array)$config);
		}

		while ($size--) {
			$identifier = $this->getName() . '_' . $size;
			$arguments = [
				'data' => [
					'name' => $identifier,
					'config' => [
						'dataScope' => $size,
						'dataType' => static::DATA_TYPE,
						'formElement' => static::FORM_ELEMENT,
						'sortOrder' => $size,
					]
				]
			];

			if (!empty($validation[$size])) {
				$arguments['data']['config']['validation'] = $validation[$size];
			}

			if ($this->context->getNamespace() == 'customer_address_form' && $this->getName() == 'street')
				$arguments['data']['config']['listens'] = ['${ $.provider }:data.cncity' => 'setStreet'];

			$component = $this->uiComponentFactory->create($identifier, Field::NAME, $arguments);
			$component->prepare();

			$this->components[$identifier] = $component;
		}
		parent::prepare();
	}
}
