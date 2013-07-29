# Mustached Robot

[![Build Status](https://secure.travis-ci.org/cantineNantes/mustached-robot.png)](http://travis-ci.org/cantineNantes/mustached-robot)

## Description

Mustached Robot is an open source checkin plateform for coworking spaces.

## Development team

* Florent Gosselin - UX designer ([@fgosselin](http://twitter.com/fgosselin))
* Jérémie Pottier - Developer ([@dzey](http://twitter.com/dzey))

New developers or designers are welcome to join the project. Just contact us on twitter ([@fgosselin](http://twitter.com/fgosselin), [@dzey](http://twitter.com/dzey)) if you want to get involved!

## Roadmap

### v. 0.5 (current)

* Coworkers can create an account and checkin in the coworking space
* Coworking space managers can access the coworking space datas on a beautiful dashboards: coworkers currently here, coworkers profiles, coworking space statistics
* An API is available so any allowed developer can play with the coworking space datas

### v. 0.6

* Base of a plugin system that allows anyone to develop plugins and themes without needing to change the core files (example: a billing management system through Freshbook, a connection to a specific CRM, etc).

### v. 1.0

* Account management for coworkers (email confirmation, lost password, etc.)
* Skills management (a coworker can add or update his skills)
* A Dashboard view with the coworkers profiles and the next events in the coworking space (this view can be used on a large TV screen in the coworking space)
* Mobile ready

## Installation

See the [install documentation](https://github.com/MustachedRobot/core/wiki/Installation)

## Documentation 

You want to get involved? See the [Developers documentation](https://github.com/cantineNantes/mustached-robot/wiki/Developers-documentation)

## API documentation 

An [API mini-doc](https://github.com/MustachedRobot/core/wiki/API) is also available. Once Mustached Robot is installed and configured on a server, developers can use this API to access the coworking space datas.

## Project status and bug report

You can see the project status or report any bug on the [Github issues tracker](https://github.com/cantineNantes/mustached-robot/issues?milestone=1&state=open)

## Help and discussions

If you need help or if you want to talk about mustaches, you can do it on twitter or on this [Google Group](https://groups.google.com/forum/#!forum/mustached-robot).

## Plugins

-- Plugins are still under development --

A plugin is an independant module which extend the capabilities of Mustached Robot by interacting at several strategic places of the application.

With a plugin you can :

	* Add anything you want on a few specific views
 	* Add form elements and trigger actions according to these elements on the application
	* Customize the look & feel of your installation

And all that without breaking your Mustached Robot installation (i.e. you will be able to update the core features and the plugin independently).

### Develop a plugin

Plugins are FuelPHP modules. They are integrated in a project through composer. 

To developer a plugin your need to:

 * create a specific repository for your module with a composer.json file at the root, whose type must be "fuel-module" (see [composer installers documentation](http://getcomposer.org/doc/articles/custom-installers.md)).
 * add the plugin as a dependency for the project in the composer.json of the project

As an example, a plugin called twitter is [available on Github](https://github.com/MustachedRobot/twitter) and integrated in the default project.

Here are the current classes you can use:

 * Form - It allows you to add form elements on some form of the application.
 * Trigger - It allows you to do anything you want after a specific action has been done by the user (example: after a user has checked in, I want my plugin to tweet about it and send me a SMS).
 * Theme - It allows you to customize the CSS and add images into the project. Each coworking space can use its own theme.

#### Language

All the language strings must be stored in the "language" folder of the plugin, following FuelPHP convention. 
We use .yml by convention.

#### Configuration

You can configure your plugin by creating a "config" folder in your module and a .php file with the same name as your plugin. This config file will automatically generate a settings form for the administrators of the coworking space.

If the user changes the default configuration, it will be saved into his local installation configuration file (/fuel/app/config/mustached.php).

Example of a file location (for the twitter plugin) : ```/modules/twitter/config/twitter.php```.
Here is an example of a config file :

```
'consumerKey' => 
	array(
		'value' => '',
		'type' => 'text',
		'label' => 'settings.consumerKey',
	),
'consumerSecret' => 
	array(
		'value' => '',
		'type' => 'text',
		'label' => 'settings.consumerSecret',
	),
``` 

In this example, the keys ('consumerKey' and 'consumerSecret') are the settings name, the label is the label that will be displayed to the administrator of the config form. The 'type' is the type of the value, used to display the field type in the form (see [FuelPHP form type](http://docs.fuelphp.com/classes/form.html)) The 'value' will be updated when the administrator submits the form (you can add default values there).

If some users have already installed your plugin and, in a future version you decide to use some extra settings, the new settings will be made available to the user but they will keep their previous settings saved locally.

To access a config parameter of the plugin, use the function:

	\Mustached\Plugin::getConfig($pluginName, $configName)

	* pluginName: name of the plugin
	* configName: name of the config parameter (use "." as an array separator)

This function will return the parameter set by the user or, if none hase been set, the default parameter of the plugin. You should always use this function instead of the FuelPHP core function such as \Config::get();

### Customizables forms

A few forms are customizable.

#### The checkin form

If you want to configure the checkin form, create a "Form" class in your module and add the method ```addElementOnPublicCheckin()```

By default, the new element(s) will be added after the last field. You can override this settings by using the two parameters (the first is the name of the element on the current form, the second is the position -- 'before' or after' -- of your new field relatively to the first parameter.

### Customizable actions

Some actions can be extended.

#### Do something when user checks in

If you want to do something when a user checks in, create a "Trigger" class in your module, and add the method ```postCheckin()```

This method send one argument $options with a 'fieldset' key containing the fields submitted by the user on the checkin form (if you have previously configured the checkin form, the datas added on the form by your plugin will be also available, see the twitter plugin example for reference).

### Themes

A plugin can be used as a Theme for part or the totality of the application. 

#### CSS

To load a css file in the whole application, add a Theme class in your module and add a "getCss" function returning the path of the css file and the version number of this file:

	public function getCss()
    {                   
        return array(
            'path' => 'assets/main.css',
            'version' => 1,
        );
    }

At each update of the plugin from the user (through composer), this file will be merged into the main project.

#### Images

To use images in the project, insert them in an /assets/img directory. All the images in this directory will be copied in the project directory /public/assets/img/[plugin_name]/ when composer install or update will be run.

### Plugin update

A user can update the plugins by running the composer update command. This command will get the latest version of the plugin and will install the Css and the images.