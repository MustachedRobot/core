<?php

namespace User;
use Mustached\Message;

class Controller_Account extends \Controller_Front
{

  public function before()
  {
     parent::before();
  }


  /**
   * Page allowing a user to create his account
   * If the user comes from the checkin page and his account doesn't exists: the email field is populated
   * If the account creation succeed, the user is redirected to the checkin page, with the email field populated
   */
  public function action_add()
  {

    $um = new Manager;
    $fm = new Form\AddForm($um, urldecode(\Input::get('email')));

    $fieldset = $fm->create_form();

    $this->data['form'] = $fieldset->form()->build();

    if(\Input::method() == 'POST')
    {    
        $result = $fm->submit($fieldset);
        
        if(is_int($result))
        {
           Message::flash_success('mustached.user.save_success');
           $user = Model_User::find($result);
           \Response::redirect('/?email='.urlencode($user['email']));
        }
        else
        {
           $this->data['msg'] = Message::error($result);
        }
    }
    return $this->_render('add');
  }



  public function action_edit($id = null)
  {
    if(!$id) {
      $id = $this->current_user['user_id'];
    }

    $um = new Manager;
    $fm = new Form\UpdateForm($um, $id);
    $fieldset = $fm->create_form();

    $this->data['form'] = $fieldset->form()->build();

    if (\Input::method() == 'POST')
    {
        $result = $fm->submit($fieldset);
        $this->data['msg'] = ($result === true) ? Message::success('mustached.user.update_success') : Message::error($result);
    }

    return $this->_render('edit');

  }

  public function action_edit_password()
  {

    $um = new Manager;
    $fm = new Form\PasswordForm($um, $this->current_user['email']);

    $fieldset = $fm->create_form();
    $this->data['form'] = $fieldset->form()->build();

    if (\Input::method() == 'POST')
    {
      $result = $fm->submit($fieldset);
      $this->data['msg'] = ($result === true) ? Message::success('mustached.user.edit_password.success') : Message::error('mustached.user.edit_password.error');
    }

    return $this->_render('edit_password');
  }


}

