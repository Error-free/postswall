<?php

namespace App\Components;

use Illuminate\Support\Facades\DB;
use App\Components\SequenceGenerator;

class SequenceWriter
{
	protected $allowedTemplateKeys = ['category', 'brand', 'product', 'property', 'property_value'];
	protected $sequences = [];

	/**    
	 * Выводит последовательности в стандртный поток вывода через echo
	 * 
	 * @param  string $template  [description]
	 * @param  string $delimeter [description]
	 * @return [type]            [description]
	 */
	public function print($template = '', $delimeter = '')
	{
		if($template) {
			$template = $this->sanitizeTemplate($template);
		}

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
			$generator = new SequenceGenerator;
			$templates = $generator->generate($this->allowedTemplateKeys);
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
}