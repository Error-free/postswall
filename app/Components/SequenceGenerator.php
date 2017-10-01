<?php

namespace App\Components;

class SequenceGenerator
{
	protected $sequences = [];

	protected function processSubArray($arr, &$seq, $depth = 1)
	{
		if(count($arr) == 1) {
			$seq[$depth] = current($arr);
			$this->sequences[] = array_values($seq);;
		} else {
			foreach($arr as $key => $val) {
				$seq[$depth] = $val;

				$tmpArr = array_values($arr);
				unset($tmpArr[$key]);
				$tmpArr = array_values($tmpArr);

				$this->processSubArray($tmpArr, $seq, $depth + 1);
			}
		}
	}

	public function generate($values)
	{
		$this->sequences = [];
		$seq = [];
		$this->processSubArray($values, $seq);
		return $this->sequences;
	}
}