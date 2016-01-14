<?php

namespace Carpediem\Errors\Test;

use Carpediem\Errors\CaptureError;
use Carpediem\Errors\ErrorToException;
use PHPUnit_Framework_TestCase;

/**
 * @group exception
 */
class ErrorToExceptionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException RuntimeException
     */
    public function testErrorTransformedIntoARuntimeException()
    {
        $touch = new ErrorToException(new CaptureError('touch', E_WARNING));
        $touch('/foo');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testErrorTransformedIntoAnInvalidArgumentException()
    {
        $touch = new ErrorToException(new CaptureError('touch'), 'InvalidArgumentException');
        $touch('/foo');
    }

    public function testCaptureNothing()
    {
        $strtoupper = new ErrorToException(new CaptureError('strtoupper'));
        $res = $strtoupper('foo');
        $this->assertSame('FOO', $res);
    }

    public function testGetExceptionClass()
    {
        $strtoupper = new ErrorToException(new CaptureError('strtoupper'));
        $this->assertSame('RuntimeException', $strtoupper->getExceptionClass());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetErrorReportingThrowsInvalidArgumentExceptionWithInvalidErrorReporting()
    {
        new ErrorToException(new CaptureError('strtoupper'), 'StdClass');
    }
}
