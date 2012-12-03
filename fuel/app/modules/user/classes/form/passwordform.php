<?php

namespace User\Form;
use \User\Manager as Manager;


/**
 * This class use the Strategy pattern to handle the form creation and submission on the creation of a user
 */

class passwordForm extends AbstractForm {

    public function __construct(Manager $um, $email = null)
    {
    	parent::__construct(new \User\FormType\PasswordFormType($um, $email));
    }


}



