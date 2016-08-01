# Cookie Policy pro Nette Framework
Nastavení v **config.neon**
```neon
services:
    - NAttreid\CookiePolicy\ICookiePolicyFactory
```

## Použití
```php
/** @var \NAttreid\CookiePolicy\ICookiePolicyFactory @inject */
public $cookiePolicyFactory;

protected function createComponentCookiePolicy() {
    $control = $this->cookiePolicyFactory->create();

    // pro zmenu jazyka
    $this->getTranslator()->setLang('cs');

    $control->setLink('/linkToText');

    $control->setView();

    return $control;
}
```