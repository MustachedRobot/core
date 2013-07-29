<?php

namespace Mustached;

/**
 * The Plugins class handles the way the application manage plugins
 */
class Plugins 
{

    private $plugins = array(); // list of plugins installed on the app (in the modules folder)
    private $regular_modules = array('checkin', 'calendar', 'user', 'test', 'install'); // list of the core module of the app (aka "not the plugins")

    /**
     * Instanciate the plugins array.
     */
    public function __construct()
    {
        // Todo: store the plugin_path in cache
        $plugin_path = APPPATH.'modules'.DS;

        $modules = array_keys(\File::read_dir($plugin_path, 1));
        $plugins = array();

        foreach ($modules as $module) {
            $module = substr($module, 0, -1);
        
            if (!in_array($module, $this->regular_modules)) {
                $this->plugins[] = $module;
            }
        }
    }

    public function getPlugins() 
    {
        return $this->plugins;
    }

    /**
     * Generic action for plugins : for each plugin, check if there is a $method() in the $class
     * If so, execute the method.
     * 
     * @param String $class  Class name
     * @param String $method Method name
     * @param Array  $params Array of params
     * 
     * @return Array          Array of the return values of each plugin
     */
    public function pluginAction($class, $method, $params = null) {
        $return = array();
        foreach ($this->plugins as $plugin) {
            \Module::load($plugin);
            $object_name = "\\".ucfirst($plugin)."\\".ucfirst($class);

            $object = new $object_name;
            
            if (method_exists($object, $method)) {
                try  {
                    $return[$plugin] = $object->$method($params);                       
                } catch(Exception $e) {
                    $return[$plugin] = array('error' => $e->getMessage());
                    // Log the error and the plugin associated with it
                }
            }
        }
        return $return;
    }

    /**
     * Get the CSS of the plugins
     * 
     * @return Mixed Array of css path if some are defined, null otherwise
     */
    public function getCss() {

        $css = array();
        foreach ($this->plugins as $plugin) {
            \Module::load($plugin);
            $object_name = "\\".ucfirst($plugin)."\Theme";

            if (class_exists($object_name)) {
                $object = new $object_name;    
                $method = 'getCss';

                if (method_exists($object, $method)) {
                    $css[$plugin] = $object->getCss();
                }
            }
        }

        if (!empty($css)) {
            return $css;    
        } else {
            return null;
        }
        
    }

    /**
     * Get the images of the plugins
     * 
     * @return Mixed Array of css path if some are defined, null otherwise
     */
    public function getImages() {

        $images = array();
        foreach ($this->plugins as $plugin) {
            \Module::load($plugin);
            $object_name = "\\".ucfirst($plugin)."\Theme";

            if (class_exists($object_name)) {
                $object = new $object_name;    
                $method = 'getImages';

                if (method_exists($object, $method)) {
                    $images[$plugin] = $object->getImages();
                }
            }
        }

        if (!empty($images)) {
            return $images;    
        } else {
            return null;
        }
        
    }    
    

    /**
     * For each plugin, add a form element to a form. 
     *
     * This method checks the Form class of each plugins and checks if there is a method called "addElementOn".FormName
     * If the method exists, it is called and the form element is added on the given form.
     * 
     * @param String    $form_name Name of the form
     * @param \Fieldset $fieldset  Fieldset on which to add the new form element
     * 
     * @return \Fieldset        Fieldset
     */
    public function addToForm($form_name, $fieldset)
    {       
        foreach ($this->plugins as $plugin) {
            \Module::load($plugin);
            $object_name = "\\".ucfirst($plugin)."\Form";

            // Check if there is a addElementOnFormName
            if (class_exists($object_name)) {
                $object = new $object_name;    
                $method = 'addElementOn'.$form_name;

                if (method_exists($object, $method)) {
                    try  {
                        $p = $object->$method();
                        $method_add = 'add_'.$p['before_after'];
                        $fieldset->$method_add($p['name'], $p['label'], $p['attributes'], $p['rules'], $p['fieldname']);                    
                    } catch(Exception $e) {
                        // Log the error and the plugin associated with it
                    }
                }                 
            }
        }
        return $fieldset;
    }

    /**
     * Build the settings form of a plugin
     * 
     * @param String $plugin Plugin name
     * 
     * @return \Fieldset      Fieldset
     */
    public function buildSettingsForm($plugin)
    {

        $config = $this->getConfig($plugin);
    
        $fieldset = \Fieldset::forge($plugin);

        foreach ($config as $key => $value) {
            $fieldset->add($key, __($plugin.'.'.$value['label']), array('type' => $value['type'], 'value' => $value['value']));
        }

        $fieldset->add(
            'submit',
            '',
            array('type' => 'submit', 'value' => __('mustached.settings.plugins.update'), 
            'class' => 'btn btn-large btn-primary')
        );  

        return $fieldset;

    }


    /**
     * Save the settings of a plugin in the app config file.
     * 
     * @param String    $plugin   Plugin name
     * @param \Fieldset $fieldset Fieldset
     * 
     * @return Bool True if the config was saved, false if an error occured
     */
    public function saveSettingsFromForm($plugin, $fieldset)
    {

        \Config::load('mustached', 'mustached');

        $config = \Config::get('mustached.'.$plugin);

        foreach ($config as $key => $value) {
            \Config::set('mustached.'.$plugin.'.'.$key.'.value', $fieldset->input($key));                  
        }

        return \Config::save('mustached', 'mustached');
        
    }


    /**
     * Return installed plugins
     * 
     * @return Array Array of the plugins name
     */
    public function get_plugins()
    {
        return $this->plugins;
    }

    /**
     * Set the installed plugins
     * 
     * @param array $plugins Array of the installed plugins
     * 
     * @return void
     */
    public function set_plugins($plugins = array())
    {
        $this->plugins = $plugins;
    }

    /**
     * Checks wether a plugin exists or not
     * 
     * @param String $plugin Plugin name
     * 
     * @return Bool 
     */
    public function plugin_exists($plugin)
    {
        return (in_array($plugin, $this->plugins)) ? true : false;
    }

    /**
     * Return the full path of a plugin
     * 
     * @param String $plugin Plugin name
     * 
     * @return String         
     */
    public function get_path($plugin)
    {
        return APPPATH.'modules/'.$plugin;
    }

    /**
     * Return the config of the plugin
     * 
     * @param String $plugin Name of the plugin
     * 
     * @return Array Associative array of settings
     */
    public function getConfig($plugin)
    {        
        return \Config::get('mustached.'.$plugin);
    }

    /**
     * Write the plugin config to the config file of the application (without overwriting existing settings)
     * 
     * @param String $plugin Name of the plugin
     * 
     * @return Boolean True on success, False on failure
     */
    public function writeLocalConfig($plugin) 
    {

        // Load the mustached.php config file in the group 'mustached'
        \Config::load('mustached', 'mustached');

        $localPath = 'mustached.'.$plugin;
        $pluginConfig = \Config::get($plugin);

        // Get the plugin config already saved loccaly
        $localConfig = \Config::get($localPath);

        if (empty($localConfig)) {
            $localConfig = array();
        }

        // Add the plugin config not already in the local config (in case of a plugin update with new settings for instance)
        foreach ($pluginConfig as $key => $value) {        
            if (!isset($localConfig[$key])) {
                $localConfig[$key] = $value;
            }
        }

        // Set and save the merged config values to the local config file
        \Config::set($localPath, $localConfig);
        return \Config::save('mustached', 'mustached');

    }


}