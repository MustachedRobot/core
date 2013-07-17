# Notes for upgrading on a newer Fuel PHP Version for Mustached Robot

When upgrading the FuelPHP Core or Packages, you should be aware there are a few manual adaptations to make afterwards. Run a search for "Mustached Migration Warning" in the whole directory to find what need to be done.

List of updates to be done (detail below):
* update the default Twig functions
* update the FuelPHP Core Fiel class

## Updating the Fuel Core
### Form checkbox custom template

In /fuel/core/classes/fieldset/field.php, replace this line:
```
$template = $this->template ?: $form->get_config('field_template', "\t\t<tr>\n\t\t\t<td class=\"{error_class}\">{label}{required}</td>\n\t\t\t<td class=\"{error_class}\">{field} {description} {error_msg}</td>\n\t\t</tr>\n");
```

by the following lines:

```
// Mustached Migration Warning
// Adding a templating extension by field type. Custom extension by @dzey.
switch ($this->get_attribute('type', null))
{
	case "checkbox":
		$template = $form->get_config('field_checkbox_template', "\t\t<tr>\n\t\t\t<td class=\"{error_class}\">{label}{required}</td>\n\t\t\t<td class=\"{error_class}\">{field} {description} {error_msg}</td>\n\t\t</tr>\n");
	break;
	default:
		$template = $this->template ?: $form->get_config('field_template', "\t\t<tr>\n\t\t\t<td class=\"{error_class}\">{label}{required}</td>\n\t\t\t<td class=\"{error_class}\">{field} {description} {error_msg}</td>\n\t\t</tr>\n");
	break;
}
```

## Updating the Packages

### Twig
#### Twig functions

In /fuel/packages/parser/classes/twig/fuel/extension.php, add the two function (in getFunctions())
* ```'avatar'        => new Twig_Function_Function('Mustached\Helper::avatar'),```
* ```'current_url'   => new Twig_Function_Function('Uri::string'),```

