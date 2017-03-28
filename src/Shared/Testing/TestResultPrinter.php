<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Testing;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestFailure;
use PHPUnit\Framework\Warning;
use PHPUnit\Runner\PhptTestCase;
use PHPUnit\TextUI\ResultPrinter as PhpUnitResultPrinter;
use SebastianBergmann\Comparator\ComparisonFailure;

class TestResultPrinter extends PhpUnitResultPrinter
{
    const INDENT = '      ';

    public function endTest(Test $test, $time)
    {
        if (!$this->lastTestFailed) {
            $this->write(self::INDENT);
            $this->writeWithColor('fg-green', '✓ ', false);
            $this->printDescription($test, $time);
        }

        if ($test instanceof TestCase) {
            $this->numAssertions += $test->getNumAssertions();
        } elseif ($test instanceof PhptTestCase) {
            $this->numAssertions++;
        }

        $this->lastTestFailed = false;

        if ($test instanceof TestCase) {
            if (!$test->hasExpectationOnOutput()) {
                $this->write($test->getActualOutput());
            }
        }
    }

    private function printDescription(Test $test, $time)
    {
        $raw =\PHPUnit\Util\Test::describe($test);
        $parts =  explode('::', $raw);

        $this->writeWithColor(
            'fg-cyan, bold',
            $parts[0] . ': ',
            false
        );

        if (count($parts) === 1) {
            $this->write("\n");
            return;
        }

        $normalized = str_replace('_', ' ', $parts[1]);

        $this->writeWithColor(
            'fg-white',
            $normalized,
            false
        );

        $this->writeWithColor(
            'fg-white',
            $this->getTimeString($time)
        );
    }

    private function getTimeString($time)
    {
        $ms = round($time * 1000);

        return ' ' . (string) $ms . 'ms';
    }

    public function addError(Test $test, \Exception $e, $time)
    {
        $this->write(self::INDENT);
        $this->writeWithColor('fg-red', '✘ ', false);
        $this->printDescription($test, $time);

        $this->lastTestFailed = true;
    }

    public function addFailure(Test $test, AssertionFailedError $e, $time)
    {
        $this->write(self::INDENT);
        $this->writeWithColor('fg-red', '✘ ', false);
        $this->printDescription($test, $time);
        $this->lastTestFailed = true;
    }

    public function addWarning(Test $test, Warning $e, $time)
    {
        $this->lastTestFailed = true;
    }

    public function addIncompleteTest(Test $test, \Exception $e, $time)
    {
        $this->lastTestFailed = true;
    }

    public function addRiskyTest(Test $test, \Exception $e, $time)
    {
        $this->lastTestFailed = true;
    }

    public function addSkippedTest(Test $test, \Exception $e, $time)
    {
        $this->lastTestFailed = true;
    }

    protected function printDefectTrace(TestFailure $defect)
    {
        $exception = $defect->thrownException();

        $this->printException($exception);

        if (!$exception instanceof ExpectationFailedException) {

            $this->printGotoTip($exception);

            return;
        }

        $failure = $exception->getComparisonFailure();

        if (null === $failure) {
            return;
        }

        $this->printComparison($failure);
    }

    private function printException(\Exception $exception): void
    {
        $this->write("\n");

        $lines = explode("\n", (string) $exception);

        foreach ($lines as $line) {
            $this->write(self::INDENT);
            $this->writeWithColor('fg-red, bg-red', ' ', false);
            $this->write('  ');
            $this->writeWithColor('fg-white', $line);
        }

        while ($previous = $exception->getPrevious()) {
            $this->write("\nCaused by\n" . $previous);
            // @todo: format previous
        }
    }

    protected function printGotoTip(\Exception $exception): void
    {
        $file = new \SplFileInfo($exception->getFile());
        $this->write(self::INDENT);
        $this->writeWithColor('fg-red, bg-red', ' ', false);
        $this->writeWithColor('fg-white, bold', '  Goto: ' . $file->getBasename() . ':' . $exception->getLine());
    }

    private function printComparison(ComparisonFailure $failure): void
    {
        $expected = $failure->getExpectedAsString();
        if (empty($expected)) {
            $expected = $failure->getExpected();
        }

        $actual = $failure->getActualAsString();
        if (empty($actual)) {
            $actual = $failure->getActual();
        }

        $this->write("\n");

        $this->writeWithColor('fg-cyan, bold', self::INDENT . "Expected:");

        $lines = explode("\n", $expected);
        foreach ($lines as $line) {
            $this->write(self::INDENT);
            $this->writeWithColor('fg-red, bg-red', ' ', false);
            $this->write('  ');
            $this->writeWithColor('fg-white, bold', $line);
        }

        $this->write("\n");

        $this->writeWithColor('fg-cyan, bold', self::INDENT . "Actual");

        $lines = explode("\n", $actual);
        foreach ($lines as $line) {
            $this->write(self::INDENT);
            $this->writeWithColor('fg-green, bg-green', ' ', false);
            $this->write('  ');
            $this->writeWithColor('fg-white, bold', $line);
        }
    }
}
