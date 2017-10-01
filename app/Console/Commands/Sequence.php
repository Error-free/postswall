<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Components\SequenceWriter;

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
	protected $description = 'Генерирует последовательность и выводит на экран';

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
		$writer = new SequenceWriter;
		$writer->print($this->option('template'), $this->option('delimeter'));
	}
}