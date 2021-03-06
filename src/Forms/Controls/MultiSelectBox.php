<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Forms\Controls;

use Nette;


/**
 * Select box control that allows multiple items selection.
 */
class MultiSelectBox extends MultiChoiceControl
{
	/** @var array of option / optgroup */
	private $options = [];

	/** @var array */
	private $optionAttributes = [];


	public function __construct($label = NULL, array $items = NULL)
	{
		parent::__construct($label, $items);
		$this->setOption('type', 'select');
	}


	/**
	 * Sets options and option groups from which to choose.
	 * @return static
	 */
	public function setItems(array $items, bool $useKeys = TRUE)
	{
		if (!$useKeys) {
			$res = [];
			foreach ($items as $key => $value) {
				unset($items[$key]);
				if (is_array($value)) {
					foreach ($value as $val) {
						$res[$key][(string) $val] = $val;
					}
				} else {
					$res[(string) $value] = $value;
				}
			}
			$items = $res;
		}
		$this->options = $items;
		return parent::setItems(Nette\Utils\Arrays::flatten($items, TRUE));
	}


	/**
	 * Generates control's HTML element.
	 */
	public function getControl(): Nette\Utils\Html
	{
		$items = [];
		foreach ($this->options as $key => $value) {
			$items[is_array($value) ? $this->translate($key) : $key] = $this->translate($value);
		}

		return Nette\Forms\Helpers::createSelectBox(
			$items,
			[
				'selected?' => $this->value,
				'disabled:' => is_array($this->disabled) ? $this->disabled : NULL,
			] + $this->optionAttributes
		)->addAttributes(parent::getControl()->attrs)->multiple(TRUE);
	}


	/**
	 * @return static
	 */
	public function addOptionAttributes(array $attributes)
	{
		$this->optionAttributes = $attributes + $this->optionAttributes;
		return $this;
	}

}
