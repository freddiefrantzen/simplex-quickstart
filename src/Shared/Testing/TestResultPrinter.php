<?php declare(strict_types = 1);

namespace Simplex\Quickstart\Shared\Testing;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\SelfDescribing;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestFailure;
use PHPUnit\Framework\Warning;
use PHPUnit\Runner\PhptTestCase;
use PHPUnit\TextUI\ResultPrinter as PhpUnitResultPrinter;
use PHPUnit\Util\Filter;
use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * @SuppressWarnings(PHPMD)
 */
class TestResultPrinter extends PhpUnitResultPrinter
{
    private const INDENT = '      ';

    private const LINE_FEED = "\n";

    private const RED = 'fg-red';
    private const WHITE = 'fg-white';
    private const WHITE_BOLD = 'fg-white, bold';
    private const GREEN = 'fg-green';
    private const CYAN_BOLD = 'fg-cyan, bold';

    private const GREEN_BLOCK = 'fg-green, bg-green';
    private const RED_BLOCK = 'fg-red, bg-red';

    private const CROSS_SYMBOL = '✘';
    private const TICK_SYMBOL = '✓';

    /** @inheritdoc */
    public function addError(Test $test, \Exception $e, $time)
    {
        $this->printFailureInfoLine($test, $time);

        $this->lastTestFailed = true;
    }

    private function printFailureInfoLine(Test $test, $time): void
    {
        $this->write(self::INDENT);
        $this->writeWithColor(self::RED, self::CROSS_SYMBOL . ' ', false);
        $this->printTestDescription($test, $time);
    }

    private function printTestDescription(Test $test, $time): void
    {
        $this->writeWithColor(self::CYAN_BOLD, $this->getTestClass($test), false);

        if (!$this->isTestDescribable($test)) {
            $this->write(self::LINE_FEED);
            return;
        }

        $this->writeWithColor(self::CYAN_BOLD, ': ', false);
        $this->writeWithColor(self::WHITE, $this->getTestDescription($test), false);
        $this->writeWithColor(self::WHITE_BOLD, $this->getTimeAsString($time));
    }

    private function getTestClass(Test $test): string
    {
        return get_class($test);
    }

    private function isTestDescribable(Test $test): bool
    {
        return $test instanceof TestCase || $test instanceof  SelfDescribing;
    }

    private function getTestDescription(Test $test): string
    {
        if ($test instanceof TestCase) {
            return str_replace('_', ' ', $test->getName());
        }

        if ($test instanceof SelfDescribing) {
            return $test->toString();
        }

        throw new \LogicException(get_class($test) . ' is not describable');
    }

    private function getTimeAsString(float $time): string
    {
        $ms = round($time * 1000);

        return ' ' . (string) $ms . 'ms';
    }

    /** @inheritdoc */
    public function addFailure(Test $test, AssertionFailedError $e, $time)
    {
        $this->printFailureInfoLine($test, $time);

        $this->lastTestFailed = true;
    }

    /** @inheritdoc */
    public function addWarning(Test $test, Warning $e, $time)
    {
        $this->lastTestFailed = true;
    }

    /** @inheritdoc */
    public function addIncompleteTest(Test $test, \Exception $e, $time)
    {
        $this->lastTestFailed = true;
    }

    /** @inheritdoc */
    public function addRiskyTest(Test $test, \Exception $e, $time)
    {
        $this->lastTestFailed = true;
    }

    /** @inheritdoc */
    public function addSkippedTest(Test $test, \Exception $e, $time)
    {
        $this->lastTestFailed = true;
    }

    /** @inheritdoc */
    public function endTest(Test $test, $time)
    {
        if (!$this->lastTestFailed) {
            $this->printSuccessLineInfo($test, $time);
        }

        $this->incrementAssertionCount($test);

        $this->lastTestFailed = false;

        if (!$test instanceof TestCase) {
            return;
        }

        if ($test->hasExpectationOnOutput()) {
            return;
        }

        $this->write($test->getActualOutput());
    }

    private function printSuccessLineInfo(Test $test, float $time): void
    {
        $this->write(self::INDENT);
        $this->writeWithColor(self::GREEN, self::TICK_SYMBOL . ' ', false);
        $this->printTestDescription($test, $time);
    }

    private function incrementAssertionCount(Test $test): void
    {
        if ($test instanceof TestCase) {
            $this->numAssertions += $test->getNumAssertions();
        } elseif ($test instanceof PhptTestCase) {
            ++$this->numAssertions;
        }
    }

    protected function printDefectTrace(TestFailure $defect)
    {
        $exception = $defect->thrownException();

        if (!$exception instanceof ExpectationFailedException) {
            $this->printExceptions($exception);
            $this->printGotoTip($exception);
            return;
        }

        $comparisonFailure = $exception->getComparisonFailure();

        if (null === $comparisonFailure || !$this->hasComparisonDetail($comparisonFailure)) {
            $this->printExceptions($exception);
            return;
        }

        $this->write(self::LINE_FEED);

        $this->printComparison($comparisonFailure);

        $this->write(self::INDENT);

        $this->writeWithColor(self::WHITE, Filter::getFilteredStacktrace($exception));
    }

    private function printExceptions(\Throwable $exception): void
    {
        $this->printException($exception);

        while ($previous = $exception->getPrevious()) {
            $this->write(self::LINE_FEED . 'Caused by:' . self::LINE_FEED);
            $this->printException($previous);
        }
    }

    private function printException(\Throwable $exception): void
    {
        $this->write(self::LINE_FEED);

        $lines = explode(self::LINE_FEED, (string) $exception);

        foreach ($lines as $line) {
            $this->write(self::INDENT);
            $this->writeWithColor(self::RED_BLOCK, ' ', false);
            $this->write('  ');
            $this->writeWithColor(self::WHITE, $line);
        }
    }

    protected function printGotoTip(\Throwable $exception): void
    {
        $file = new \SplFileInfo($exception->getFile());

        $this->write(self::INDENT);
        $this->writeWithColor(self::RED_BLOCK, ' ', false);
        $this->writeWithColor(self::WHITE_BOLD, '  Goto: ' . $file->getBasename() . ':' . $exception->getLine());
    }

    private function printComparison(ComparisonFailure $failure): void
    {
        $this->writeWithColor(self::WHITE, self::INDENT . $failure->getMessage());

        $this->write(self::LINE_FEED);

        $this->writeWithColor(self::CYAN_BOLD, self::INDENT . "Expected:");

        $this->printComparisonDetail($failure->getExpectedAsString(), self::GREEN_BLOCK);

        $this->writeWithColor(self::CYAN_BOLD, self::INDENT . "Actual");

        $this->printComparisonDetail($failure->getActualAsString(), self::RED_BLOCK);
    }

    private function printComparisonDetail(string $detail, string $blockColor): void
    {
        $this->write(self::LINE_FEED);

        $lines = explode(self::LINE_FEED, $detail);

        foreach ($lines as $line) {
            $this->write(self::INDENT);
            $this->writeWithColor($blockColor, ' ', false);
            $this->write('  ');
            $this->writeWithColor(self::WHITE_BOLD, $line, false);
            $this->write(self::LINE_FEED);
        }

        $this->write(self::LINE_FEED);
    }

    private function hasComparisonDetail(ComparisonFailure $failure): bool
    {
        return !empty($failure->getExpectedAsString());
    }
}
