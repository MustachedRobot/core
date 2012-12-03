<?php

namespace User\FormType;
use \User\Manager as Manager;

/**
 * This class describes the strategy to create a form and submit datas from a form for the creation of a user
 */
class AddFormType extends AbstractFormType {

	// Email with which to optionnaly populate the email field of the form
	private $email;

	public function __construct(Manager $um, $email = null)
	{
		parent::__construct($um);	
		$this->email = $email;
	}

	/**
	 * Return the fieldset for the creation form
	 * @return \Fieldset [description]
	 */
	public function getFieldset()
	{

		$input = \Lang::get('mustached.user.form.add');

		$fieldset = \Fieldset::forge()->add_model('User\Model_User', '', 'set_add_fields');
        
        if($this->email) {
        	$fieldset->populate(array('email' => $email, true));
        }        
        $fieldset->add('company', '', array('type' => 'text', 'id' => 'companies', 'placeholder' => __('mustached.user.company')));
        $fieldset->add('submit', '', array('type' => 'submit', 'value' => $input, 'class' => 'btn btn-large btn-primary'));

		$fieldset->repopulate();

        return $fieldset;

	}

	/**
	 * Submit the datas 
	 * @param  Array $fields Array of the fields to submit
	 * @return mixed Return the id of the newly created user on success or an error message on failure   
	 */
	public function submit($fields)
	{		
		return $this->um->create_user($fields);
	}    



}