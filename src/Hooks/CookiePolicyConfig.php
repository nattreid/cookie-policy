<?php

declare(strict_types=1);

namespace NAttreid\CookiePolicy\Hooks;

use Nette\SmartObject;

/**
 * Class CookiePolicyConfig
 *
 * @property bool $enable
 * @property bool $analytics
 * @property bool $functional
 * @property bool $commercial
 * @property string|null $link
 *
 * @author Attreid <attreid@gmail.com>
 */
class CookiePolicyConfig
{
	use SmartObject;

	private $enable = true;
	private $analytics = false;
	private $functional = false;
	private $commercial = false;
	private $link;

	protected function isEnable(): bool
	{
		return $this->enable;
	}

	protected function setEnable(bool $enable): void
	{
		$this->enable = $enable;
	}

	protected function isAnalytics(): bool
	{
		return $this->analytics;
	}

	protected function setAnalytics(bool $analytics): void
	{
		$this->analytics = $analytics;
	}

	protected function isFunctional(): bool
	{
		return $this->functional;
	}

	protected function setFunctional(bool $functional): void
	{
		$this->functional = $functional;
	}

	protected function isCommercial(): bool
	{
		return $this->commercial;
	}

	protected function setCommercial(bool $commercial): void
	{
		$this->commercial = $commercial;
	}

	protected function getLink(): ?string
	{
		return $this->link;
	}

	protected function setLink(?string $link): void
	{
		$this->link = $link;
	}
}