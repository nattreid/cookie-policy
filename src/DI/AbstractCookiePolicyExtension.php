<?php

declare(strict_types=1);

namespace NAttreid\CookiePolicy\DI;

use NAttreid\CookiePolicy\CookiePolicy;
use NAttreid\CookiePolicy\Hooks\CookiePolicyConfig;
use NAttreid\CookiePolicy\ICookiePolicyFactory;
use Nette\DI\CompilerExtension;

/**
 * Class AbstractGoogleApiExtension
 *
 * @author Attreid <attreid@gmail.com>
 */
abstract class AbstractCookiePolicyExtension extends CompilerExtension
{
	private $defaults = [
		'enable' => true,
		'link' => null,
		'analytics' => false,
		'functional' => false,
		'commercial' => false
	];

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults, $this->getConfig());

		$cookiePolicy = $this->prepareHook($config);

		$builder->addDefinition($this->prefix('factory'))
			->setImplement(ICookiePolicyFactory::class)
			->setFactory(CookiePolicy::class)
			->setArguments([$cookiePolicy]);
	}

	protected function prepareHook(array $config)
	{
		$googleApi = new CookiePolicyConfig;
		$googleApi->enable = $config['enable'];
		$googleApi->link = $config['link'];
		$googleApi->analytics = $config['analytics'];
		$googleApi->functional = $config['functional'];
		$googleApi->commercial = $config['commercial'];
		return $googleApi;
	}
}