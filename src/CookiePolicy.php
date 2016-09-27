<?php

namespace NAttreid\CookiePolicy;

use Nette\Http\Request;
use Nette\Http\Session;
use Nette\Localization\ITranslator;

/**
 * Ochrana prav pri pouzivani cookies
 *
 * @author Attreid <attreid@gmail.com>
 */
class CookiePolicy extends \Nette\Application\UI\Control
{

	/** @var Session */
	private $session;

	/** @var string */
	private $link;

	/** @var boolean */
	private $view = FALSE;

	/** @var ITranslator */
	private $translator;

	/** @var Request */
	private $request;

	public function __construct(Session $session, Request $request)
	{
		parent::__construct();
		$this->session = $session;
		$this->request = $request;
		$this->translator = new Lang\Translator;
	}

	/**
	 * Nastavi translator
	 * @param ITranslator $translator
	 */
	public function setTranslator(ITranslator $translator)
	{
		$this->translator = $translator;
	}

	/**
	 * Vrati Translator
	 * @return Lang\Translator
	 */
	public function getTranslator()
	{
		return $this->translator;
	}

	/**
	 * Nastavi zobrazeni
	 * @param boolean $view
	 */
	public function setView($view = TRUE)
	{
		$this->view = $view;
	}

	/**
	 * Potvrzeni
	 */
	public function handleAgree()
	{
		if ($this->request->isAjax()) {
			$session = $this->session->getSection('cookiePolicy');
			$session->view = FALSE;
			$this->redrawControl('cookiePolicy');
		} else {
			$this->redirect('this');
		}
	}

	/**
	 * Nastavi link pro info
	 * @param string $link
	 */
	public function setLink($link)
	{
		$this->link = $link;
	}

	public function render()
	{
		$this->template->addFilter('translate', [$this->translator, 'translate']);

		$session = $this->session->getSection('cookiePolicy');
		$this->template->view = isset($session->view) ? $session->view : $this->view;

		if (isset($this->link)) {
			$this->template->link = $this->link;
		}

		$this->template->setFile(__DIR__ . '/cookiePolicy.latte');
		$this->template->render();
	}

}

interface ICookiePolicyFactory
{

	/** @return CookiePolicy */
	public function create();
}
