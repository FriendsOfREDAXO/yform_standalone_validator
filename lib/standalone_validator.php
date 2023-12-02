<?php

class standalone_validator extends rex_yform
{
    private $fieldNames = [];

    public function setValueArray($arr): void
    {
        $i = 0;
        foreach ($arr as $key => $val) {
            $this->setValueField('direct_input', [$key, $val]);
            $this->fieldNames[$i] = $key;
            ++$i;
        }
    }

    public function setValidationArray($arr): void
    {
        foreach ($arr as $item) {
            foreach ($item as $key => $val) {
                $this->setValidateField($key, $val);
            }
        }
    }

    public function validate(): void
    {
        $this->objparams['values'] = [];
        $this->objparams['validates'] = [];
        $this->objparams['actions'] = [];
        $this->objparams['fields'] = [];
        $this->objparams['fields']['values'] = &$this->objparams['values'];
        $this->objparams['fields']['validates'] = &$this->objparams['validates'];
        $this->objparams['fields']['actions'] = &$this->objparams['actions'];
        $this->objparams['send'] = 1;

        $rows = count($this->objparams['form_elements']);

        for ($i = 0; $i < $rows; ++$i) {
            $element = $this->objparams['form_elements'][$i];

            if ('validate' == $element[0]) {
                $class = 'rex_yform_validate_' . trim($element[1]);
            } else {
                $class = 'rex_yform_value_' . trim($element[0]);
            }

            if (class_exists($class)) {
                if ('validate' == $element[0]) {
                    $class = 'rex_yform_validate_' . trim($element[1]);
                    $this->objparams['validates'][$i] = new $class();
                    $this->objparams['validates'][$i]->loadParams($this->objparams, $element);
                    $this->objparams['validates'][$i]->setId($i);
                    $this->objparams['validates'][$i]->init();
                    $this->objparams['validates'][$i]->setObjects($this->objparams['values']);
                } else {
                    $class = 'rex_yform_value_' . trim($element[0]);
                    $this->objparams['values'][$i] = new $class();
                    $this->objparams['values'][$i]->loadParams($this->objparams, $element);
                    $this->objparams['values'][$i]->setId($i);
                    $this->objparams['values'][$i]->init();
                    $this->objparams['values'][$i]->setObjects($this->objparams['values']);
                    $rows = count($this->objparams['form_elements']);
                }
            } else {
                echo 'Class does not exist "' . $class . '" ';
            }
        }

        foreach ($this->objparams['values'] as $i => $valueObject) {
            $valueObject->setValue($this->getFieldValue($i, []));
        }

        foreach ($this->objparams['validates'] as $Object) {
            $Object->enterObject();
        }

        $this->objparams['validated'] = 1;
    }

    public function getErrors(): array
    {
        if (!isset($this->objparams['validated']) || 1 != $this->objparams['validated']) {
            $this->validate();
        }

        $warning_messages = $this->objparams['warning_messages'];
        $out = [];
        foreach ($warning_messages as $key => $val) {
            $out[$this->fieldNames[$key]] = $val;
        }

        return $out;
    }

    public function isValid(): bool
    {
        if (!isset($this->objparams['validated']) || 1 != $this->objparams['validated']) {
            $this->validate();
        }

        if (count($this->objparams['warning'])) {
            return false;
        }

        return true;
    }
}
