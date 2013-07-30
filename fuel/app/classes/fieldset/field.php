<?php 

class Fieldset_Field extends \Fuel\Core\Fieldset_Field
{

    /**
     * Return the template for display
     * Updated from FuelPHP Core to allow a specific templating according to the field type
     */
    protected function template($build_field)
    {
        $form = $this->fieldset()->form();

        $required_mark = $this->get_attribute('required', null) ? $form->get_config('required_mark', null) : null;
        $label = $this->label ? $form->label($this->label, null, array('id' => 'label_'.$this->name, 'for' => $this->get_attribute('id', null), 'class' => $form->get_config('label_class', null))) : '';
        $error_template = $form->get_config('error_template', '');
        $error_msg = ($form->get_config('inline_errors') && $this->error()) ? str_replace('{error_msg}', $this->error(), $error_template) : '';
        $error_class = $this->error() ? $form->get_config('error_class') : '';

        if (is_array($build_field))
        {
            $label = $this->label ? str_replace('{label}', $this->label, $form->get_config('group_label', '<span>{label}</span>')) : '';


            $template = $this->template ?: $form->get_config('multi_field_template', "\t\t<tr>\n\t\t\t<td class=\"{error_class}\">{group_label}{required}</td>\n\t\t\t<td class=\"{error_class}\">{fields}\n\t\t\t\t{field} {label}<br />\n{fields}\t\t\t{error_msg}\n\t\t\t</td>\n\t\t</tr>\n");
            if ($template && preg_match('#\{fields\}(.*)\{fields\}#Dus', $template, $match) > 0)
            {
                $build_fields = '';
                foreach ($build_field as $lbl => $bf)
                {
                    $bf_temp = str_replace('{label}', $lbl, $match[1]);
                    $bf_temp = str_replace('{required}', $required_mark, $bf_temp);
                    $bf_temp = str_replace('{field}', $bf, $bf_temp);
                    $build_fields .= $bf_temp;
                }

                $template = str_replace($match[0], '{fields}', $template);
                $template = str_replace(array('{group_label}', '{required}', '{fields}', '{error_msg}', '{error_class}', '{description}'), array($label, $required_mark, $build_fields, $error_msg, $error_class, $this->description), $template);

                return $template;
            }

            // still here? wasn't a multi field template available, try the normal one with imploded $build_field
            $build_field = implode(' ', $build_field);
        }

        // determine the field_id, which allows us to identify the field for CSS purposes
        $field_id = 'col_'.$this->name;
        if ($parent = $this->fieldset()->parent())
        {
            $parent->get_tabular_form() and $field_id = $parent->get_tabular_form().'_col_'.$this->basename;
        }
       
        switch ($this->get_attribute('type', null))
        {
            case "checkbox":
                $template = $this->template ?: $form->get_config('field_checkbox_template', "\t\t<tr>\n\t\t\t<td class=\"{error_class}\">{label}{required}</td>\n\t\t\t<td class=\"{error_class}\">{field} {description} {error_msg}</td>\n\t\t</tr>\n");
            break;
            default:
                $template = $this->template ?: $form->get_config('field_template', "\t\t<tr>\n\t\t\t<td class=\"{error_class}\">{label}{required}</td>\n\t\t\t<td class=\"{error_class}\">{field} {description} {error_msg}</td>\n\t\t</tr>\n");
            break;
        } 
              

        $template = str_replace(array('{label}', '{required}', '{field}', '{error_msg}', '{error_class}', '{description}', '{field_id}'),
            array($label, $required_mark, $build_field, $error_msg, $error_class, $this->description, $field_id),
            $template);

        return $template;
    }    
}
