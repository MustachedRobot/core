<?php

namespace User\FormType;
use \User\Manager as Manager;

/**
 * This class describes the strategy to create a form and submit datas to update the password of a user
 */

class PasswordFormType extends AbstractFormType {

	// Id of the user to update
	private $email;

	public function __construct(Manager $um, $email)
    {
    	parent::__construct($um);
        $this->email = $email;
    }

	/**
	 * Return the fieldset for the update form
	 * @return \Fieldset [description]
	 */
	public function getFieldset()
	{
		$fieldset = \Fieldset::forge('edit_password');

	    $form = $fieldset->form();
	    $form->add('current_password', '', array('type' => 'password', 'placeholder' => \Lang::get('mustached.user.edit_password.current_password')), array(array('required')));
	    $form->add('password', '' , array('type' => 'password', 'placeholder' => \Lang::get('mustached.user.edit_password.new_password')), array(array('required')));
	    $form->add('submit', '', array('type' => 'submit', 'value' => \Lang::get('mustached.user.edit_password.action_label'), 'class' => 'btn btn-large btn-primary'));

        return $fieldset;
	}

	/**
	 * Submit the datas 
	 * @param  Array $fields Array of the fields to submit
	 * @return mixed 		 Return true on success or an error message on failure   	 
	 */
	public function submit($fields)
    {
    	$auth = \Auth::instance();
    	$return = $auth->change_password($fields['current_password'], $fields['password'], $this->email);
    }




}