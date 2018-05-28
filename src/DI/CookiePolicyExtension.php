<?php

declare(strict_types=1);

namespace NAttreid\CookiePolicy\DI;

use NAttreid\Cms\Configurator\Configurator;
use NAttreid\Cms\DI\ExtensionTranslatorTrait;
use NAttreid\CookiePolicy\Hooks\CookiePolicyConfig;
use NAttreid\CookiePolicy\Hooks\CookiePolicyHook;
use NAttreid\WebManager\Services\Hooks\HookService;
use Nette\DI\Statement;

if (trait_exists('NAttreid\Cms\DI\ExtensionTranslatorTrait')) {
	class CookiePolicyExtension extends AbstractCookiePolicyExtension
	{
		use ExtensionTranslatorTrait;

		protected function prepareConfig(array $config)
		{
			$builder = $this->getContainerBuilder();
			$hook = $builder->getByType(HookService::class);
			if ($hook) {
				$builder->addDefinition($this->prefix('hook'))
					->setType(CookiePolicyHook::class);

				$this->setTranslation(__DIR__ . '/../Lang/', [
					'webManager'
				]);

				return new Statement('?->cookiePolicy \?: new ' . CookiePolicyConfig::class, ['@' . Configurator::class]);
			} else {
				return parent::getConfig($config);
			}
		}
	}
} else {
	class CookiePolicyExtension extends AbstractCookiePolicyExtension
	{
	}
}