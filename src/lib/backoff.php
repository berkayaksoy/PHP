<?php

namespace Leo\lib;

class backoff
{
	private $initialDelay;
	private $maxDelay;
	private $currentDelay;
	private $currentDelayIncrement = 0;

	public function __construct($initialDelay = 1, $maxDelay = 7200)
	{
		$this->initialDelay = $initialDelay;
		$this->currentDelay = $this->initialDelay;
		$this->maxDelay = $maxDelay;
	}

	public function backoff()
	{
		// increment the current delay
		$this->currentDelayIncrement++;
		// get the current delay seconds
		$this->currentDelay = $this->fibanocci($this->currentDelayIncrement);

		if ($this->currentDelay > $this->maxDelay) {
			$this->currentDelay = $this->maxDelay;
		}

		Utils::log(['operation' => 'backoff', 'seconds' => $this->currentDelay]);//"Backing off for {$this->currentDelay} seconds before retrying.");
		sleep($this->currentDelay);
	}

	private function fibanocci($n, $a = 1, $b = 1)
	{
		if ($n) {
			return $this->fibanocci($n - 1, $b, $a + $b);
		} else {
			return $a;
		}
	}

	public function reset()
	{
		$this->currentDelay = $this->initialDelay;
		$this->currentDelayIncrement = 0;
	}

	public function getCurrentDelay()
	{
		return $this->currentDelay;
	}
}
