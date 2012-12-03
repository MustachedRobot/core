<?php

namespace User\FormType;
use \User\Manager as Manager;

/**
 * This class describes the strategy to create a form and submit datas from a form for the update of a user
 */

class UpdateFormType extends AbstractFormType {

	// Id of the user to update
	private $id;

	public function __construct(Manager $um, $id)
    {
    	parent::__construct($um);
        $this->id = $id;
    }

	/**
	 * Return the fieldset for the update form
	 * @return \Fieldset [description]
	 */
	public function getFieldset()
	{
        $input = \Lang::get('mustached.user.form.edit');
		$fieldset = \Fieldset::forge()->add_model('User\Model_User', '', 'set_edit_fields' );
        
		$fieldset->add('company', '', array('type' => 'text', 'id' => 'companies', 'placeholder' => __('mustached.user.company')));

        $u = \DB::select('firstname', 'email', 'lastname', 'biography', 'twitter', array('companies.name', 'company'))->from('users')->where('users.id', '=', $this->id)->join('companies', 'RIGHT')->on('companies.id', '=', 'users.company_id')->execute()->current();

        $user = array(
        	'firstname' => $u['firstname'],
        	'email'     => $u['email'],
        	'lastname'  => $u['lastname'],
            'biography' => $u['biography'],
        	'twitter'   => $u['twitter'],
        	'company'   => $u['company'],
        );

        $fieldset->populate($user, true);

        $fieldset->add('submit', '', array('type' => 'submit', 'value' => $input, 'class' => 'btn btn-large btn-primary'));

        return $fieldset;
	}

	/**
	 * Submit the datas 
	 * @param  Array $fields Array of the fields to submit
	 * @return mixed 		 Return true on success or an error message on failure   	 
	 */
	public function submit($fields)
    {
        $um = new Manager;
        return $um->update_user($this->id, $fields);
    }




}