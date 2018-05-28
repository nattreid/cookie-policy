<?php

declare(strict_types=1);

namespace NAttreid\CookiePolicy\DI;

use NAttreid\Cms\Configurator\Configurator;
use NAttreid\CookiePolicy\Hooks\CookiePolicyConfig;
use NAttreid\CookiePolicy\Hooks\CookiePolicyHook;
use NAttreid\WebManager\Services\Hooks\HookService;
use Nette\DI\Statement;

if (trait_exists('NAttreid\Cms\DI\ExtensionTranslatorTrait')) {
	class CookiePolicyExtension extends AbstractCookiePolicyExtension
	{
		protected function prepareHook(array $config)
		{
			$builder = $this->getContainerBuilder();
			$hook = $builder->getByType(HookService::class);
			if ($hook) {
				$builder->addDefinition($this->prefix('hook'))
					->setType(CookiePolicyHook::class);

				return new Statement('?->cookiePolicy \?: new ' . CookiePolicyConfig::class, ['@' . Configurator::class]);
			} else {
				return parent::prepareHook($config);
			}
		}
	}
} else {
	class CookiePolicyExtension extends AbstractCookiePolicyExtension
	{
	}
}