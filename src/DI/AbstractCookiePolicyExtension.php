<?php

declare(strict_types=1);

namespace NAttreid\CookiePolicy\DI;

use NAttreid\CookiePolicy\CookiePolicy;
use NAttreid\CookiePolicy\Hooks\CookiePolicyConfig;
use NAttreid\CookiePolicy\ICookiePolicyFactory;
use Nette;
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

		$cookiePolicy = $this->prepareConfig($config);

		$builder->addFactoryDefinition($this->prefix('factory'))
			->setImplement(ICookiePolicyFactory::class)
			->getResultDefinition()
			->setFactory(CookiePolicy::class)
			->setArguments([$cookiePolicy]);
	}

	protected function prepareConfig(array $config)
	{
		$builder = $this->getContainerBuilder();
		return $builder->addDefinition($this->prefix('config'))
			->setFactory(CookiePolicyConfig::class)
			->addSetup('$enable', [$config['enable']])
			->addSetup('$link', [$config['link']])
			->addSetup('$analytics', [$config['analytics']])
			->addSetup('$functional', [$config['functional']])
			->addSetup('$commercial', [$config['commercial']]);
	}
}