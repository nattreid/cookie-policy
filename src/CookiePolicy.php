<?php

declare(strict_types=1);

namespace NAttreid\CookiePolicy;

use NAttreid\CookiePolicy\Lang\Translator;
use Nette\Application\AbortException;
use Nette\Application\UI\Control;
use Nette\Http\Request;
use Nette\Http\Session;
use Nette\Localization\ITranslator;

/**
 * Ochrana prav pri pouzivani cookies
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

	public function __construct(Session $session, Request $request)
	{
		parent::__construct();
		$this->session = $session;
		$this->request = $request;
		$this->translator = new Translator;
	}

	/**
	 * Nastavi translator
	 * @param ITranslator $translator
	 */
	public function setTranslator(ITranslator $translator): void
	{
		$this->translator = $translator;
	}

	/**
	 * Vrati Translator
	 * @return Translator
	 */
	public function getTranslator(): Translator
	{
		return $this->translator;
	}

	/**
	 * Nastavi zobrazeni
	 * @param bool $view
	 */
	public function setView(bool $view = true): void
	{
		$this->view = $view;
	}

	/**
	 * Potvrzeni
	 * @throws AbortException
	 */
	public function handleAgree(): void
	{
		if ($this->request->isAjax()) {
			$session = $this->session->getSection('cookiePolicy');
			$session->view = false;
			$this->redrawControl('cookiePolicy');
		} else {
			$this->redirect('this');
		}
	}

	/**
	 * Nastavi link pro info
	 * @param string $link
	 */
	public function setLink(string $link): void
	{
		$this->link = $link;
	}

	public function render(): void
	{
		$this->template->addFilter('translate', [$this->translator, 'translate']);

		$session = $this->session->getSection('cookiePolicy');
		$this->template->view = $session->view ?? $this->view;

		if (isset($this->link)) {
			$this->template->link = $this->link;
		}

		$this->template->setFile(__DIR__ . '/cookiePolicy.latte');
		$this->template->render();
	}

}

interface ICookiePolicyFactory
{
	public function create(): CookiePolicy;
}
