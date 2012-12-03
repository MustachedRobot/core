<?php

namespace User\Form;
use \User\Manager as Manager;


/**
 * Using the Strategy pattern, this class handles the form creation and submission on the User module
 * The different form strategies are implemented under the children of the AbstractFormType classes
 */

abstract class AbstractForm {

    private $formType;    

    public function __construct(\User\FormType\AbstractFormType $formType)
    {
        $this->formType = $formType;
    }

    /**
	  *	Create and optionnaly populate a full Fieldset
	  *	@return \Fieldset The complete fieldset with additional data and submit button
      */
    public function create_form()
    {
        return $this->formType->getFieldSet();
    }


    /**
     * Submit the fieldset after a POST submission
     * @param  \Fieldset $fieldset The fieldset after a POST submission
     * @return mixed. See the different implementation for exact result return
     */
    public function submit($fieldset)
    {
    	if ($fieldset->validation()->run() == true)
        {
            $fields = $fieldset->validated();
            return $this->formType->submit($fields);            
        }
        else
        {
            return $fieldset->validation()->error();
        }
        return \Lang::get('mustached.user.save_error');

    }



}
