# Standalone Validator (YForm-Plugin)

__Plugin__ für das REDAXO-Addon [yform](https://github.com/yakamara/redaxo_yform): Ermöglicht das Validieren von PHP Arrays ohne HTML Formulare. Es können alle YForm Validierungen genutzt werden.

## Beispiel
```php
$input = [
  'first_name' => 'Max',
  'last_name' => 'Mustermann'
];

$rules = [
  ['empty' => ['first_name', 'Bitte Vornamen angeben.']],
  ['empty' => ['last_name', 'Bitte Nachnahmen angeben.']]
];

$validator = new standalone_validator();

$validator->setValueArray($input);
$validator->setValidationArray($rules);

if(!$validator->isValid()) {
  print_r($validator->getErrors());
}

```

## Installation

1. Paket im Plugins-Ordner von yform ablegen: `redaxo/src/addons/yform/plugins/standalone-validator`  
2. Plugin im REDAXO-Adminbereich aktivieren.
