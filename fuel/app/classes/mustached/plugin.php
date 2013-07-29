<?php

namespace Mustached;

/**
 * The Plugin class handles methods and options usable by a plugin in its own context
 */
class Plugin
{

    /**
     * Return the config of a plugin. 
     * If the parameter has been set locally by the user, it returns the local config. Otherwise it returns the plugin config.
     * 
     * @param String $plugin Name of the plugin
     * @param String $config Name of the config parameter
     * 
     * @return Mixed The config parameter value
     */
    public static function getConfig($plugin, $config)
    {
        $conf = \Config::get('mustached.'.$plugin.'.'.$config);
        if (!isset($conf)) {
            \Config::load($plugin.'::'.$plugin, $plugin);
            return \Config::get($plugin.'.'.$config);
        } else {
            return $conf;
        }
        
    }

}