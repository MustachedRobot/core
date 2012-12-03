<?php

namespace User\Form;
use \User\Manager as Manager;

/**
 * This class use the Strategy pattern to handle the form creation and submission on the update of a user
 */

class updateForm extends AbstractForm {

    public function __construct(Manager $um, $id)
    {
        parent::__construct(new \User\FormType\UpdateFormType($um, $id));
    }

}
