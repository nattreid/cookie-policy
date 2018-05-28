<?php

declare(strict_types=1);

namespace NAttreid\CookiePolicy\Hooks;

use NAttreid\Form\Form;
use NAttreid\WebManager\Services\Hooks\HookFactory;
use Nette\ComponentModel\Component;
use Nette\Utils\ArrayHash;

/**
 * Class GoogleApiHook
 *
 * @author Attreid <attreid@gmail.com>
 */
class CookiePolicyHook extends HookFactory
{
	/** @var IConfigurator */
	protected $configurator;

	public function init(): void
	{
		if (!$this->configurator->cookiePolicy) {
			$this->configurator->cookiePolicy = new CookiePolicyConfig;
		}
	}

	/** @return Component */
	public function create(): Component
	{
		$form = $this->formFactory->create();
		$form->setAjaxRequest();

		$form->addCheckbox('enable', 'webManager.web.hooks.cookiePolicy.enable')
			->setDefaultValue($this->configurator->cookiePolicy->enable);

		$form->addText('link', 'webManager.web.hooks.cookiePolicy.link')
			->setDefaultValue($this->configurator->cookiePolicy->link);

		$form->addCheckbox('analytics', 'webManager.web.hooks.cookiePolicy.analytics')
			->setDefaultValue($this->configurator->cookiePolicy->analytics);

		$form->addCheckbox('functional', 'webManager.web.hooks.cookiePolicy.functional')
			->setDefaultValue($this->configurator->cookiePolicy->functional);

		$form->addCheckbox('commercial', 'webManager.web.hooks.cookiePolicy.commercial')
			->setDefaultValue($this->configurator->cookiePolicy->commercial);

		$form->addSubmit('save', 'form.save');

		$form->onSuccess[] = [$this, 'cookiePolicyFormSucceeded'];

		return $form;
	}

	public function cookiePolicyFormSucceeded(Form $form, ArrayHash $values): void
	{
		$config = $this->configurator->cookiePolicy;

		$config->enable = $values->enable;
		$config->link = $values->link ?: null;
		$config->analytics = $values->analytics;
		$config->functional = $values->functional;
		$config->commercial = $values->commercial;

		$this->configurator->cookiePolicy = $config;

		$this->flashNotifier->success('default.dataSaved');
	}
}