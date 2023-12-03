<?php

class rex_yform_value_direct_input extends rex_yform_value_abstract
{
    public function setValue($value): void
    {
        $this->value = $this->getElement(2);
    }

    public function enterObject(): void
    {
        $this->params['value_pool']['email'][$this->getName()] = $this->getValue();
        $this->params['value_pool']['sql'][$this->getName()] = $this->getValue();
    }

    public function getDescription(): string
    {
        return '
                direct_input -> Beispiel: direct_input|name|value
        ';
    }
}
