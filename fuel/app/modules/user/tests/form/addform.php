<?php 

namespace User\Form;

/**
 * Manager class tests
 *
 * @group App
 * @group User
 * @group Form
 * @group DB_Cnx
 */
class Test_Form_Manager extends \TestCase
{

	private $addForm;
	private $user;

	public function setUp()
	{
		\Module::load('user');

		$um = new \User\Manager;

        $this->addForm = new addForm($um);

	}

	/*----------------------------------------------
     *
     * TEST FORM CREATION
     *
     *---------------------------------------------*/

	public function test_create_form_returns_fieldset()
	{
		$return = $this->addForm->create_form();
		$this->assertInstanceOf('Fieldset', $return);
	}




}