<?

class Formbuilder
{

    private $object;
    private $attributes;
    private $name;
    private $validate = array();
    private $validation_name = null;

    /**
     * Find if there are any validations in form_validations table and executes them
     * @return boolean | void
     */
    protected function getValidations ()
    {
        if ($this->validation_name != null) {
            $condition = array(
                'where' => array(
                    'name' => $this->validation_name
            ));
        } else {
            $url = cleanUrl();
            $condition = array(
                'whereOr' => array(
                    'address1' => $url,
                    'address2' => $url
            ));
        }
        $validations = admin_FormValidations::find($condition);

        if (!$validations) {
            return false;
        }
        $validations = admin_Validations::findAll(array('where' => array(
                        'relation_id' => $validations->id
        )));
        if (!$validations) {
            return false;
        }

        $rules = array();
        foreach ($validations as $validation) {
            $this->validate[$validation->field][$validation->rule] = $validation->value;
        }
    }

    /**
     * Create form object
     * @param model $object -Object from models
     * @param array $options - attributes for <form tag
     * @param string $validation_name - it's used for getValidations to find validations by validation name
     */
    public function __construct ($object, $options = null, $validation_name = null)
    {
        $this->object = $object;
        $this->name = get_class($object);
        $filtered_name = $this->name;

        $this->validation_name = $validation_name == null ? '' : $validation_name;

        if (!isset($options['method'])) {
            $options['method'] = 'post';
        }

        if (!isset($options['action'])) {
            $options['action'] = str_replace(ADMIN_DIR . '_', '', $this->name);
            $filtered_name = str_replace(ADMIN_DIR . '_', '', $this->name);
        }

        if (!$this->object->isNew()) {
            if ($options['action'] == $filtered_name) {
                $options['action'] .= '/update/' . $this->object->id;
            } else {
                $options['action'] .= "/" . $this->object->id;
            }
        } else {
            if ($options['action'] == $filtered_name) {
                $options['action'] .= '/create';
            }
        }

        if (Dispatcher::$in_admin === true) {
            $options['action'] = ADMIN_DIR . '/' . $options['action'];
        }

        $options['action'] = "/" . PUBLIC_DIR . '/' . $options['action'];
        $options['id'] = isset($options['id']) ? $options['id'] : $this->name;

        $this->attributes = $options;
        $this->getValidations();
    }

    public function __toString ()
    {
        $tag = tag('form', $this->attributes);
        $errors = FormValidator::$errors;
        if (empty($errors))
            return $tag;
        if ($this->validation_name != null) {
            if (FormValidator::getName() != $this->validation_name)
                return $tag;
        }

        $html1 = '';
        $html2 = '';
        $html1 = '<div id="errors_title" class="' . $this->attributes['id'] . '">';
        $html1 .= '<h4>There were some errors in form</h4>';
        $html2 .= '<ul style="padding: 0;" class="' . $this->attributes['id'] . '">';
        $li = '';
        foreach ($errors as $key => $value) {
            if (!is_int($key))
                continue;
            $li .= '<li class="error_explanation">' . $value . '</li>';
            unset(FormValidator::$errors[$key]);
        }

        $html2 .= $li;
        $html2 .= '</ul>';
        $html1 .= '</div>';

        $html = $li != null ? $html1 . $html2 . $tag : $html1 . $tag;

        return $html;
    }

    /**
     * Set some settings to attributes
     * @param string $name
     * @param array $attributes
     * @return array $attributes - return changed attributes
     */
    public function parseAttributes ($name, $attributes)
    {
        $attributes['name'] = $name;

        if (isset($attributes['name'])) {
            if (isset($this->object->$attributes['name'])) {
                if ((isset($attributes['type']) && $attributes['type'] == 'radio')) {
                    if($this->object->$attributes['name'] == $attributes['value']){
                        $attributes['checked'] = 'checked';
                    }
                } else {
                    $attributes['value'] = $this->object->$attributes['name'];
                }
            }
            $attributes['name'] = $this->name . "[" . $attributes['name'] . "]";
        }

        if (!isset($attributes['id'])) {
            $attributes['id'] = str_replace(array('[', ']'), array('_', ''), $attributes['name']);
        }

        if (isset($attributes['type']) && $attributes['type'] == 'checkbox') {
            if (isset($attributes['value']) && $attributes['value'] == 'on') {
                $attributes['checked'] = 'checked';
            } else {
                unset($attributes['checked']);
            }
        }



        return $attributes;
    }

    /**
     * Create an input field
     * @param string $name
     * @param array $attributes
     * @return html input tag
     */
    public function input ($name, $attributes = null)
    {
        if (!isset($attributes['type'])) {
            $attributes['type'] = 'text';
        }
        $attributes = $this->parseAttributes($name, $attributes);

        $div = '';
        $div = $this->validate($name);

        $tag = tag('input', $attributes) . $div;

        return $tag;
    }

    /**
     * If there is error for this field, return div element containing error msg
     * @param string $name - field name
     * @return html div tag with error msg
     */
    protected function validate ($name)
    {
        if (!isset(FormValidator::$errors[$name])) {//!isset($this->validate[$name]) ||
            return '';
        }

        $options = array();
        $options['class'] = 'error_explanation';
        $options['style'] = "";
        $content = ' ';
        if (isset(FormValidator::$errors[$name])) {
            $content = FormValidator::$errors[$name];
            $options['style'] = "";
        }

        return tag('div', $options, $content);
    }

    /**
     * Create Input type checkbox
     * @param string $name - input name
     * @param array $attributes
     * @return html input element
     */
    public function checkbox ($name, $attributes = null)
    {
        $attributes = $this->parseAttributes($name, $attributes);
        if (isset($attributes['value'])) {
            if ($attributes['value'] == 'on') {
                $attributes['checked'] = 'checked';
            }
            unset($attributes['value']);
        }
        $attributes['type'] = 'checkbox';

        return tag("input", $attributes);
    }

    /**
     * Create Label
     * @param string $name - name of input field
     * @param string $text - Text to show
     * @param array $attributes - attributes for the label
     * @return html label element
     */
    public function label ($name, $text, array $attributes = array())
    {
        $attributes = $this->parseAttributes($name, $attributes);
        $attributes['for'] = $attributes['id'];
        $content = $text;

        unset($attributes['name'], $attributes['id'], $attributes['value']);

        return tag('label', $attributes, $content);
    }

    /**
     * Ceate textarea
     * @param string $name - name of textarea
     * @param array $attributes
     * @return html textarea element
     */
    public function textarea ($name, array $attributes = array())
    {
        $attributes = $this->parseAttributes($name, $attributes);
        if (isset($attributes['value']) && !empty($attributes['value'])) {
            $content = $attributes['value'];
            unset($attributes['value']);
        } else {
            $content = ' ';
        }

        $div = '';
        $div = $this->validate($name);

        return tag('textarea', $attributes, $content) . $div;
    }

    /**
     * Create select element; Select's options are in 'value' attribute
     * @param string $name
     * @param array $attributes
     * @usage
     *
     *  <?=$form->select('city', array(
     *      'value' => array(
     *          array(
     *              'value' => 0, 'content' => 'Select city'
     *              , 'value' => 1, 'content' => 'Sofia'
     *              , 'value' => 2, 'content' => 'Berlin'
     * ))));?>
     *
     * @return html select element
     */
    public function select ($name, array $attributes = array())
    {
        $test = $attributes['value'];

        $attributes = $this->parseAttributes($name, $attributes);
        $content = '';

        if ($test != $attributes['value']) {
            $test['selected'] = $attributes['value'];
            $attributes['value'] = $test;
        }

        if (isset($attributes['value'])) {
            $selected = is_array($attributes['value']) ? array_cut($attributes['value'], 'selected') : $attributes['value'];
            foreach ($attributes['value'] as $key => $value) {
                if (is_array($value)) {
                    $options = array();
                    if ($selected == $value['value']) {
                        $options['selected'] = 'selected';
                    }

                    $options['value'] = array_cut($value, 'value');
                    $stoinost = isset($value['content']) ? $value['content'] : $value['value'];
                    unset($value['content']);

                    $content .= tag('option', $options, $stoinost);
                } else {
                    $options = array();
                    if ($selected == $value)
                        $options['selected'] = 'selected';

                    $stoinost = $value;
                    $options['value'] = $value;
                    unset($options['content']);
                    $content .= tag('option', $options, $stoinost);
                }
                unset($attributes[$key]);
            }
            unset($attributes['value']);
        }

        $div = '';
        $div = $this->validate($name);

        return tag('select', $attributes, $content) . $div;
    }

    /*
     * show captcha's image
     */

    public function captcha ()
    {
        $attributes = array();
        $attributes['src'] = '/Captcha.php';

        return tag('img', $attributes);
    }

    /**
     * Close the form
     * @return html closing form tag
     */
    public function close ()
    {
        return '</form>';
    }

}