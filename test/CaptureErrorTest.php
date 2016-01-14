<?php

namespace Carpediem\Errors\Test;

use Carpediem\Errors\CaptureError;
use PHPUnit_Framework_TestCase;

/**
 * @group capture
 */
class CaptureErrorTest extends PHPUnit_Framework_TestCase
{
    public function testCaptureErrorInfos()
    {
        $lambda = new CaptureError('touch');
        $res = $lambda('/foo');
        $this->assertFalse($res);
        $this->assertSame(E_WARNING, $lambda->getLastErrorCode());
        $this->assertNotEmpty($lambda->getLastErrorMessage());
    }

    public function testCaptureNothing()
    {
        $lambda = new CaptureError('strtoupper');
        $res = $lambda('foo');
        $this->assertSame('FOO', $res);
        $this->assertSame(0, $lambda->getLastErrorCode());
        $this->assertSame('', $lambda->getLastErrorMessage());
    }

    public function testGetErrorReporting()
    {
        $lambda = new CaptureError('strtoupper');
        $this->assertSame(E_ALL, $lambda->getErrorReporting());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetErrorReportingThrowsInvalidArgumentExceptionWithInvalidErrorReporting()
    {
        new CaptureError('touch', 'toto');
    }

    public function testCapturesTriggeredError()
    {
        $lambda = new CaptureError('trigger_error');
        $lambda('foo');
        $this->assertSame('foo', $lambda->getLastErrorMessage());
    }

    public function testCapturesSilencedError()
    {
        $lambda = new CaptureError(function ($x) {
            @trigger_error($x);
        });
        $lambda('foo');
        $this->assertSame('foo', $lambda->getLastErrorMessage());
    }

    public function testObjectDoesntInteractWithExistingErrorHandlers()
    {
        $n = 0;
        set_error_handler(function () use (&$n) {
            $n++;
        });
        trigger_error('foo');
        $this->assertSame(1, $n);

        $lambda = new CaptureError('trigger_error');
        $lambda('foo');
        $this->assertSame(1, $n);

        trigger_error('foo');
        $this->assertSame(2, $n);
    }
}
