# Cookie Policy pro Nette Framework
Nastavení v **config.neon**
```neon
extensions:
    cookiePolicy: NAtrreid\CookiePolicy\DI\CookiePolicyExtension
    
cookiePolicy:
    enable: true
    link: '/adresaStranky'
    analytics: true
    functional: true
    commercial: true
```

## Použití
```php
/** @var NAttreid\CookiePolicy\ICookiePolicyFactory @inject */
public $cookiePolicyFactory;

protected function createComponentCookiePolicy() {
    $control = $this->cookiePolicyFactory->create();

    // pro zmenu jazyka
    $control->translator->setLang('cs');

    return $control;
}
```
