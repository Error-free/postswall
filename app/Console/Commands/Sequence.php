<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Sequence extends Command
{
	protected $allowedTemplateKeys = ['category', 'brand', 'product', 'property', 'property_value'];
	protected $sequences = [];
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'sequence:generate 
	{--template= : Шаблон последовательности, если не задан, будут сгенерированы все возможные варианты. Пример "category brand product property property_value"}
	{--delimeter= : Разделитель. Пробел по умолчанию"}
	';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Генерирует последовательность';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$template = $this->option('template');
		if($template) {
			$template = $this->sanitizeTemplate($template);
		}

		$delimeter = $this->option('delimeter');
		$delimeter = empty($delimeter) ? ' ' : $delimeter;

		$data = DB::table('products AS p')
					->select('p.name AS product', 'b.name AS brand', 'c.name AS category', 'pv.name AS property_value', 'prop.name AS property')
					->join('brands AS b', 'b.id', '=', 'p.brand_id')
					->join('categorys AS c', 'c.id', '=', 'p.category_id')
					->join('products_property_values AS ppv', 'ppv.product_id', '=', 'p.id')
					->join('property_values AS pv', 'pv.id', '=', 'ppv.property_value_id')
					->join('propertys AS prop', 'prop.id', '=', 'pv.property_id')
					->get();

		if($template) {
			$templates = [explode(' ', $template)];
		} else {
			$templates = $this->generateSequence($this->allowedTemplateKeys);
		}

		foreach($data as $row) {
			foreach($templates as $template) {
				$str = $this->getString($row, $template, $delimeter);
				echo $str . "\n";
			}
		}
	}

	protected function sanitizeTemplate($template)
	{
		$templateAr = explode(' ', $template);
		$templateAr = array_intersect($templateAr, $this->allowedTemplateKeys);
		return implode(' ', $templateAr);
	}

	protected function getString($row, $templateAr, $delimeter)
	{
		$userKeys = array_flip($templateAr);

		foreach($this->allowedTemplateKeys as $k => $val) {
			$userKeys[$val] = '%'.($k + 1).'$s';
		}		

		$delimeterSign = '<delimeter>';
		$format = implode($delimeterSign, $userKeys);

		$str = sprintf($format, 
			$row->{$this->allowedTemplateKeys[0]}, 
			$row->{$this->allowedTemplateKeys[1]}, 
			$row->{$this->allowedTemplateKeys[2]}, 
			$row->{$this->allowedTemplateKeys[3]}, 
			$row->{$this->allowedTemplateKeys[4]});

		$str = str_replace($delimeterSign, $delimeter, $str);

		return $str;
	}

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

	protected function generateSequence($values)
	{
		$this->sequences = [];
		$seq = [];
		$this->processSubArray($values, $seq);
		return $this->sequences;
	}
}