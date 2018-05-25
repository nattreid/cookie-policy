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

	/** @var bool */
	private $view = false;

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
		return $this->analytics ?? false;
	}

	protected function getFunctional(): bool
	{
		return $this->functional ?? false;
	}

	protected function getCommercial(): bool
	{
		return $this->commercial ?? false;
	}

	protected function getVisible(): bool
	{
		return $this->visible ?? $this->view;
	}

	public function setView(bool $view = true): void
	{
		$this->view = $view;
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
			$this->analytics = $data->analytics;
			$this->functional = $data->functional;
			$this->commercial = $data->commercial;
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

		$this->template->analytics = $this->analytics ?? true;
		$this->template->functional = $this->functional ?? true;
		$this->template->commercial = $this->commercial ?? true;

		$this->template->setFile(__DIR__ . '/cookiePolicy.latte');
		$this->template->render();
	}

}

interface ICookiePolicyFactory
{
	public function create(): CookiePolicy;
}
