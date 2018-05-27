<?php

declare(strict_types=1);

namespace NAttreid\CookiePolicy;

use NAttreid\CookiePolicy\Lang\Translator;
use Nette\Application\AbortException;
use Nette\Application\UI\Control;
use Nette\Http\Request;
use Nette\Http\Session;
use Nette\Localization\ITranslator;
use Nette\Utils\Json;
use Tracy\Debugger;

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

	/** @var string */
	private $link;

	/** @var Config */
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

	public function __construct(Session $session, Request $request)
	{
		parent::__construct();
		$this->session = $session->getSection('nattreid/cookiePolicy');
		$this->request = $request;
		$this->translator = new Translator;

		$this->analytics =& $this->session->analytics;
		$this->functional =& $this->session->functional;
		$this->commercial =& $this->session->commercial;
		$this->visible =& $this->session->visible;

		$this->config = new Config;
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
		if (!$this->config) {
			return true;
		}
		return $this->analytics ?? true;
	}

	protected function getFunctional(): bool
	{
		if (!$this->config) {
			return true;
		}
		return $this->functional ?? true;
	}

	protected function getCommercial(): bool
	{
		if (!$this->config) {
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

	public function setEnable(bool $enable = true): void
	{
		$this->config->enable = $enable;
	}

	public function setEnableAnalytics(bool $enable = true): void
	{
		$this->config->analytics = $enable;
	}

	public function setEnableCommercial(bool $enable = true): void
	{
		$this->config->commercial = $enable;
	}

	public function setEnableFunctional(bool $enable = true): void
	{
		$this->config->functional = $enable;
	}

	public function setLink(string $link): void
	{
		$this->link = $link;
	}

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

	public function handleSettings(): void
	{
		if ($this->request->isAjax()) {
			$this->template->viewModal = true;
			$this->redrawControl('modal');
		} else {
			$this->redirect('this');
		}
	}

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
		$this->template->link = $this->link;

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
