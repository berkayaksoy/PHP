<?php

namespace Leo\lib;

/**
 * Class Exception
 * @package Leo\lib
 */
class Exception extends \Exception
{
	/**
	 * @var int
	 */
	private $batchCount = 0;

	/**
	 * @var int
	 */
	private $recordCount = 0;

	/**
	 * Get number of batches queued in error
	 * @return int
	 */
	public function getBatchCount()
	{
		return $this->batchCount;
	}

	/**
	 * Get number of records queued in error
	 * @return int
	 */
	public function getRecordCount()
	{
		return $this->recordCount;
	}

	/**
	 * Set number of batches queued in error
	 * @param $value
	 */
	public function setBatchCount($value)
	{
		$this->batchCount = $value;
	}

	/**
	 * Set number of records queued in error
	 * @param $value
	 */
	public function setRecordCount($value)
	{
		$this->recordCount = $value;
	}
}
