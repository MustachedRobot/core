<?php


namespace Fuel\Tasks;

/**
 * This class handles the installation of plugins.
 */
class InstallPlugins
{

    public function run()
    {
        $this->createCss();
        $this->createImages();
    }

    /**
     * Create the css for all the plugins
     * 
     * @return void
     */
    public function createCss()
    {
        $p = new \Mustached\Plugins;
        $csss = $p->getCss();

        $message = \Cli::color('Checkin for CSS to install ...', 'green');
        \Cli::write($message);

        foreach ($csss as $plugin => $css) {
            $localDir = DOCROOT.'public/assets/css/plugins/'.$plugin.DIRECTORY_SEPARATOR;
            if (!file_exists($localDir)) {
                \File::create_dir(DOCROOT.'public/assets/css/plugins/', $plugin);
            }
            if (!file_exists($localDir.$css['version'].'.css')) {
                \File::copy(APPPATH.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$plugin.DIRECTORY_SEPARATOR.$css['path'], $localDir.$css['version'].'.css');
                    $message = \Cli::color('Plugin '.$plugin.': CSS v'.$css['version'].' created', 'green');
                    \Cli::write($message);
            }    
        }

        $message = \Cli::color('... CSS installation finished!', 'green');
        \Cli::write($message);
    }

    /**
     * Create the images for all the plugins
     * 
     * @return void
     */
    public function createImages()
    {
        $p = new \Mustached\Plugins;
        $plugins = $p->getPlugins();        

        $message = \Cli::color('Checkin for images to install ...', 'green');
        \Cli::write($message);

        foreach ($plugins as $plugin) {
            $pluginDir = APPPATH.'modules/'.$plugin.'/assets/img';
            $localDir  = DOCROOT.'public/assets/img/plugins/'.$plugin;
            if (file_exists($pluginDir)) {
                if (file_exists($localDir)) {
                    \File::delete_dir($localDir, true);    
                }                
                \File::copy_dir($pluginDir, $localDir);
                $message = \Cli::color('Plugin '.$plugin.': images created', 'green');
                \Cli::write($message);
            }            
        }

        $message = \Cli::color('... Images installation finished!', 'green');
        \Cli::write($message);
    }    
}