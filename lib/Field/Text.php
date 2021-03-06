<?php

namespace Lib\Field;

use Lib\Field;
use Lib\Utils;

class Text extends Field
{
    protected $label,
            $content = '/.*/',
            $attribute_string = '',
            $class = '',
            $required = false,
    		$attributes;
    public $error = array(),
            $field_type = 'text';

    public function __construct($label, $attributes = array())
    {
        $this->label = $label;
        if (isset($attributes['required'])) {
            $this->required = $attributes['required'];
        } else {
            $attributes['required'] = true;
        }
        if (isset($attributes['content'])) {
            $this->content = $attributes['content'];
        }
        $this->attributes = $attributes;
    }

    public function attributeString()
    {
        if (!empty($this->error)) {
            $this->class = 'error';
        }
        $this->attribute_string = '';
        foreach ($this->attributes as $attribute => $val) {
            if ($attribute == 'class') {
                $this->class.= ' ' . $val;
            } else {
                $this->attribute_string .= $val ? ' ' . ($val === true ? $attribute : "$attribute=\"$val\"") : '';
            }
        }
    }

    public function returnField($form_name, $name, $value = '', $group = '')
    {
        $this->attributeString();

        $label = $this->label === false ? false : sprintf('<label for="%s_%s_%s" class="%s">%s</label>', $form_name, $group, $name, $this->class, $this->label);
        
        return array(
            'messages' => !empty($this->custom_error) && !empty($this->error) ? $this->custom_error : $this->error,
            'label' => $this->label === false ? false : sprintf('<label for="%s_%s_%s" class="%s">%s</label>', $form_name, $group, $name, $this->class, $this->label),
            'field' => sprintf('<input type="%1$s" name="%6$s[%7$s][%2$s]" id="%6$s_%7$s_%2$s" value="%3$s" %4$s class="%5$s form-control"/>', $this->field_type, $name, $value, $this->attribute_string, $this->class, $form_name, $group ),
            'html' => $this->html
        );
    }

    public function validate($val)
    {
        if ($this->required) {
            if (Utils::stripper($val) === false) {
                $this->error[] = 'is required';
            }
        }
        if (Utils::stripper($val) !== false) {
            if (!preg_match($this->content, $val)) {
                $this->error[] = 'is not valid';
            }
        }

        return !empty($this->error) ? false : true;
    }



}
