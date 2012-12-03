<?php

namespace User\FormType;
use \User\Manager as Manager;

/**
 * This class defines the strategy for the form creation and submission on a user
 */

abstract class AbstractFormType {

	protected $um;

	public function __construct(Manager $um)
	{
		$this->um = $um;
	}

	abstract function getFieldset();
	abstract function submit($fields);

}