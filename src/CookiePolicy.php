<?php

declare(strict_types=1);

namespace NAttreid\CookiePolicy;

use NAttreid\CookiePolicy\Hooks\CookiePolicyConfig;
use NAttreid\CookiePolicy\Lang\Translator;
use Nette\Application\AbortException;
use Nette\Application\UI\Control;
use Nette\Http\Request;
use Nette\Http\Session;
use Nette\Localization\ITranslator;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

/**
 * Class CookiePolicy
 *
 * @property Translator $translator
 * @property-read bool $analytics
 * @property-read bool $functional
 * @property-read bool $commercial
 * @property-read bool $visible
 *
 * @author Attreid <attreid@gmail.com>
 */
class CookiePolicy extends Control
{

	/** @var Session */
	private $session;

	/** @var CookiePolicyConfig */
	private $config;

	/** @var ITranslator */
	private $translator;

	/** @var Request */
	private $request;

	/** @var bool */
	private $analytics;

	/** @var bool */
	private $functional;

	/** @var bool */
	private $commercial;

	/** @var bool */
	private $visible;

	public function __construct(CookiePolicyConfig $config, Session $session, Request $request)
	{
		parent::__construct();
		$this->session = $session->getSection('nattreid/cookiePolicy');
		$this->config = new $config;
		$this->request = $request;
		$this->translator = new Translator;

		$this->analytics =& $this->session->analytics;
		$this->functional =& $this->session->functional;
		$this->commercial =& $this->session->commercial;
		$this->visible =& $this->session->visible;
	}

	protected function setTranslator(ITranslator $translator): void
	{
		$this->translator = $translator;
	}

	protected function getTranslator(): Translator
	{
		return $this->translator;
	}

	protected function getAnalytics(): bool
	{
		if (!$this->config->enable) {
			return true;
		}
		return $this->analytics ?? true;
	}

	protected function getFunctional(): bool
	{
		if (!$this->config->enable) {
			return true;
		}
		return $this->functional ?? true;
	}

	protected function getCommercial(): bool
	{
		if (!$this->config->enable) {
			return true;
		}
		return $this->commercial ?? true;
	}

	protected function getVisible(): bool
	{
		if (!$this->config->enable) {
			return false;
		}
		return $this->visible ?? true;
	}

	/**
	 * @throws AbortException
	 */
	public function handleAllowAll(): void
	{
		if ($this->request->isAjax()) {
			$this->analytics = true;
			$this->functional = true;
			$this->commercial = true;
			$this->visible = false;
			$this->redrawControl('docker');
		} else {
			$this->redirect('this');
		}
	}

	/**
	 * @throws AbortException
	 */
	public function handleAllowWithoutCommercial(): void
	{
		if ($this->request->isAjax()) {
			$this->analytics = true;
			$this->functional = true;
			$this->commercial = false;
			$this->visible = false;
			$this->redrawControl('docker');
		} else {
			$this->redirect('this');
		}
	}

	/**
	 * @throws AbortException
	 */
	public function handleSettings(): void
	{
		if ($this->request->isAjax()) {
			$this->template->viewModal = true;
			$this->redrawControl('modal');
		} else {
			$this->redirect('this');
		}
	}

	/**
	 * @param string $json
	 * @throws AbortException
	 * @throws JsonException
	 */
	public function handleSave(string $json): void
	{
		if ($this->request->isAjax()) {
			$data = Json::decode($json);
			if (isset($data->analytics)) {
				$this->analytics = (bool) $data->analytics;
			}
			if (isset($data->functional)) {
				$this->functional = (bool) $data->functional;
			}
			if (isset($data->commercial)) {
				$this->commercial = (bool) $data->commercial;
			}
			$this->visible = false;
			$this->redrawControl('docker');
		} else {
			$this->redirect('this');
		}
	}

	public function render(): void
	{
		$this->template->addFilter('translate', [$this->translator, 'translate']);

		$this->template->componentId = $this->getUniqueId();
		$this->template->visible = $this->getVisible();

		$this->template->config = $this->config;

		$this->template->analytics = $this->getAnalytics();
		$this->template->functional = $this->getFunctional();
		$this->template->commercial = $this->getCommercial();

		$this->template->setFile(__DIR__ . '/cookiePolicy.latte');
		$this->template->render();
	}

}

interface ICookiePolicyFactory
{
	public function create(): CookiePolicy;
}
