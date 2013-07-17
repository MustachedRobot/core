<?php
namespace Mustached;

class TwigExtensions extends \Twig_Extension {

    /**
     * Gets the name of the extension.
     *
     * @return  string
     */
    public function getName()
    {
        return 'mustached';
    }

    /**
     * Sets up all of the functions this extension makes available.
     *
     * @return  array
     */
    public function getFunctions()
    {
        return array(
            'avatar'        => new \Twig_Function_Function('\\Mustached\\Helper::avatar'),
            'current_url'   => new \Twig_Function_Function('Uri::string'),
        );
    }    


}