<?php

namespace mageekguy\atoum\tests\units\report\fields\runner\failures;

use
	\mageekguy\atoum,
	\mageekguy\atoum\mock,
	\mageekguy\atoum\cli\prompt,
	\mageekguy\atoum\cli\colorizer,
	\mageekguy\atoum\report,
	\mageekguy\atoum\report\fields\runner
;

require_once(__DIR__ . '/../../../../../runner.php');

class cli extends \mageekguy\atoum\tests\units\report\fields\runner\failures
{
	public function testClass()
	{
		$this->assert
			->class($this->getTestedClassName())->isSubclassOf('\mageekguy\atoum\report\fields\runner')
		;
	}

	public function testClassConstants()
	{
		$this->assert
			->string(runner\failures\cli::defaultTitlePrompt)->isEqualTo('> ')
			->string(runner\failures\cli::defaultMethodPrompt)->isEqualTo('=> ')
		;
	}

	public function test__construct()
	{
		$field = new runner\failures\cli();

		$this->assert
			->variable($field->getRunner())->isNull()
			->object($field->getLocale())->isInstanceOf('\mageekguy\atoum\locale')
			->object($field->getTitlePrompt())->isEqualTo(new prompt(runner\failures\cli::defaultTitlePrompt))
			->object($field->getTitleColorizer())->isEqualTo(new colorizer('0;31'))
			->object($field->getMethodPrompt())->isEqualTo(new prompt(runner\failures\cli::defaultMethodPrompt, new colorizer('0;31')))
			->object($field->getMethodColorizer())->isEqualTo(new colorizer('0;31'))
		;

		$field = new runner\failures\cli(null, null, null);

		$this->assert
			->variable($field->getRunner())->isNull()
			->object($field->getLocale())->isInstanceOf('\mageekguy\atoum\locale')
			->object($field->getTitlePrompt())->isEqualTo(new prompt(runner\failures\cli::defaultTitlePrompt))
			->object($field->getTitleColorizer())->isEqualTo(new colorizer('0;31'))
			->object($field->getMethodPrompt())->isEqualTo(new prompt(runner\failures\cli::defaultMethodPrompt, new colorizer('0;31')))
			->object($field->getMethodColorizer())->isEqualTo(new colorizer('0;31'))
		;

		$field = new runner\failures\cli($titlePrompt = new prompt(uniqid()), $titleColorizer = new colorizer(), $methodPrompt = new prompt(uniqid()), $methodColorizer = new colorizer(), $locale = new atoum\locale());

		$this->assert
			->variable($field->getRunner())->isNull()
			->object($field->getLocale())->isIdenticalTo($locale)
			->object($field->getTitlePrompt())->isIdenticalTo($titlePrompt)
			->object($field->getTitleColorizer())->isIdenticalTo($titleColorizer)
			->object($field->getMethodPrompt())->isIdenticalTo($methodPrompt)
			->object($field->getMethodColorizer())->isIdenticalTo($methodColorizer)
		;
	}

	public function testSetTitlePrompt()
	{
		$field = new runner\failures\cli();

		$this->assert
			->object($field->setTitlePrompt($prompt = new prompt(uniqid())))->isIdenticalTo($field)
			->object($field->getTitlePrompt())->isIdenticalTo($prompt)
		;
	}

	public function testSetTitleColorizer()
	{
		$field = new runner\failures\cli();

		$this->assert
			->object($field->setTitleColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
			->object($field->getTitleColorizer())->isIdenticalTo($colorizer)
		;
	}

	public function testSetMethodPrompt()
	{
		$field = new runner\failures\cli();

		$this->assert
			->object($field->setMethodPrompt($prompt = new prompt(uniqid())))->isIdenticalTo($field)
			->object($field->getMethodPrompt())->isIdenticalTo($prompt)
		;
	}

	public function testSetMethodColorizer()
	{
		$field = new runner\failures\cli();

		$this->assert
			->object($field->setMethodColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
			->object($field->getMethodColorizer())->isIdenticalTo($colorizer)
		;
	}

	public function testSetWithRunner()
	{
		$field = new runner\failures\cli();

		$mockGenerator = new mock\generator();
		$mockGenerator->generate('\mageekguy\atoum\runner');

		$runner = new mock\mageekguy\atoum\runner();

		$this->assert
			->object($field->setWithRunner($runner))->isIdenticalTo($field)
			->object($field->getRunner())->isIdenticalTo($runner)
			->object($field->setWithRunner($runner, atoum\runner::runStart))->isIdenticalTo($field)
			->object($field->getRunner())->isIdenticalTo($runner)
			->object($field->setWithRunner($runner, atoum\runner::runStop))->isIdenticalTo($field)
			->object($field->getRunner())->isIdenticalTo($runner)
		;
	}

	public function test__toString()
	{
		$field = new runner\failures\cli();

		$this->mock
			->generate('\mageekguy\atoum\score')
			->generate('\mageekguy\atoum\runner')
		;

		$score = new mock\mageekguy\atoum\score();
		$score->getMockController()->getErrors = array();

		$runner = new mock\mageekguy\atoum\runner();
		$runner->getMockController()->getScore = $score;

		$this->assert
			->castToString($field)->isEmpty()
			->castToString($field->setWithRunner($runner))->isEmpty()
			->castToString($field->setWithRunner($runner, atoum\runner::runStart))->isEmpty()
			->castToString($field->setWithRunner($runner, atoum\runner::runStop))->isEmpty()
		;

		$score->getMockController()->getFailAssertions = $fails = array(
			array(
				'class' => $class = uniqid(),
				'method' => $method = uniqid(),
				'file' => $file = uniqid(),
				'line' => $line = uniqid(),
				'asserter' => $asserter = uniqid(),
				'fail' => $fail = uniqid()
			),
			array(
				'class' => $otherClass = uniqid(),
				'method' => $otherMethod = uniqid(),
				'file' => $otherFile = uniqid(),
				'line' => $otherLine = uniqid(),
				'asserter' => $otherAsserter = uniqid(),
				'fail' => $otherFail = uniqid()
			)
		);

		$field = new runner\failures\cli();

		$this->assert
			->castToString($field)->isEmpty()
			->castToString($field->setWithRunner($runner))->isEqualTo($field->getTitlePrompt() . $field->getTitleColorizer()->colorize(sprintf($field->getLocale()->__('There is %d failure:', 'There are %d failures:', sizeof($fails)), sizeof($fails))) . PHP_EOL .
				$field->getMethodPrompt() . $field->getMethodColorizer()->colorize($class . '::' . $method . '():') . PHP_EOL .
				sprintf($field->getLocale()->_('In file %s on line %d, %s failed : %s'), $file, $line, $asserter, $fail) . PHP_EOL .
				$field->getMethodPrompt() . $field->getMethodColorizer()->colorize($otherClass . '::' . $otherMethod . '():') . PHP_EOL .
				sprintf($field->getLocale()->_('In file %s on line %d, %s failed : %s'), $otherFile, $otherLine, $otherAsserter, $otherFail) . PHP_EOL
			)
		;

		$field = new runner\failures\cli($titlePrompt = new prompt(uniqid()), $titleColorizer = new colorizer(uniqid(), uniqid()), $methodPrompt = new prompt(uniqid()), $methodColorizer = new colorizer(uniqid(), uniqid()), $locale = new atoum\locale());

		$this->assert
			->castToString($field)->isEmpty()
			->castToString($field->setWithRunner($runner))->isEqualTo($field->getTitlePrompt() . $titleColorizer->colorize(sprintf($locale->__('There is %d failure:', 'There are %d failures:', sizeof($fails)), sizeof($fails))) . PHP_EOL .
				$field->getMethodPrompt() . $methodColorizer->colorize($class . '::' . $method . '():') . PHP_EOL .
				sprintf($locale->_('In file %s on line %d, %s failed : %s'), $file, $line, $asserter, $fail) . PHP_EOL .
				$field->getMethodPrompt() . $methodColorizer->colorize($otherClass . '::' . $otherMethod . '():') . PHP_EOL .
				sprintf($locale->_('In file %s on line %d, %s failed : %s'), $otherFile, $otherLine, $otherAsserter, $otherFail) . PHP_EOL
			)
		;
	}
}

?>
