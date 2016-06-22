<?php

namespace NAttreid\CookiePolicy;

use Nette\Http\Session;

/**
 * Ochrana prav pri pouzivani cookies
 *
 * @author Attreid <attreid@gmail.com>
 */
class CookiePolicy extends \Nette\Application\UI\Control {

    /** @var Session */
    private $session;

    /** @var string */
    private $link;

    /** @var boolean */
    private $view = false;

    public function __construct(Session $session) {
        $this->session = $session;
    }

    /**
     * Nastavi zobrazeni
     * @param boolean $view
     */
    public function setView($view = TRUE) {
        $this->view = $view;
    }

    /**
     * Potvrzeni
     */
    public function handleAgree() {
        if ($this->presenter->isAjax()) {
            $session = $this->session->getSection('cookiePolicy');
            $session->view = FALSE;
            $this->redrawControl('cookiePolicy');
        } else {
            $this->presenter->redirect('Homepage:');
        }
    }

    /**
     * Nastavi link pro info
     * @param string $link
     */
    public function setLink($link) {
        $this->link = $link;
    }

    public function render() {
        $session = $this->session->getSection('cookiePolicy');
        $this->template->view = isset($session->view) ? $session->view : $this->view;

        if (isset($this->link)) {
            $this->template->link = $this->link;
        }

        $this->template->setFile(__DIR__ . '/cookiePolicy.latte');
        $this->template->render();
    }

}

interface ICookiePolicyFactory {

    /** @return CookiePolicy */
    public function create();
}
