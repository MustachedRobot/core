<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends MX_Controller {
    
    protected $data = array();

    public function __construct() {
        parent::__construct();
        
        $this->lang->load('mustached');
        $this->lang->load('front');
        
        $this->load->library('Twig');
		$this->load->spark('assets/1.5.0');

        // Load base helpers
        $this->twig->add_function('assets_css');
        $this->twig->add_function('assets_js');
        $this->twig->add_function('site_url');
        $this->twig->add_function('lang');

        // Load form helpers
        $this->twig->add_function('form_open');
        $this->twig->add_function('form_close');
        $this->twig->add_function('form_label');
        $this->twig->add_function('form_input');
        $this->twig->add_function('form_dropdown');
        $this->twig->add_function('form_textarea');
        $this->twig->add_function('form_checkbox');
        $this->twig->add_function('form_submit');
        $this->twig->add_function('form_password');

        // Load specific helpers
        $this->twig->add_function('get_user_avatar');


        if ( ENVIRONMENT == 'development' ){
            $this->output->enable_profiler(true);
        }

        if($msg = $this->session->flashdata('msg'))
        {
            $this->data['msg'] = array(
                'type'    => $msg['type'],
                'content' => $msg['content']
            );
        }

        if($this->session->userdata('firstname'))
        {
            $this->data['current_user'] = array(
                'id'        => $this->session->userdata('user_id'),
                'firstname' => $this->session->userdata('firstname'),
                'is_admin'  => $this->session->userdata('is_admin'),
            );    
        }

        
        // add the modules to the model autoloader search path
        // Datamapper::add_model_path( array(  APPPATH.'modules/welcome' ) );
    }

    // Utility function called to output a template
	public function _render($template, $data = array())
	{		
		$data_merge = array_merge($data, $this->data);
		$view = $this->twig->display($template.'.html.twig', $data_merge);				
	}

    // Utility function called to return the page as a string
	public function getTemplate($template, $data = array())
	{		
		$data_merge = array_merge($data, $this->data);
		return $this->twig->render($template.'html.twig', $data_merge);
	}


}

//require(APPPATH.'core/Public_Controller.php');

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */