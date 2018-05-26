<?php

declare(strict_types=1);

namespace NAttreid\CookiePolicy;

use Nette\SmartObject;

/**
 * Class Config
 *
 * @property bool $enable
 * @property bool $analytics
 * @property bool $functional
 * @property bool $commercial
 *
 * @author Attreid <attreid@gmail.com>
 */
class Config
{
	use SmartObject;

	private $enable = false;
	private $analytics = false;
	private $functional = false;
	private $commercial = false;


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
}