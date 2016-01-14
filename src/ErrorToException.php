<?php
/**
* This file is part of the Carpediem.Errors library
*
* @license http://opensource.org/licenses/MIT
* @link https://github.com/carpediem/errors/
* @version 0.1.0
* @package Carpediem.errors
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Carpediem\Errors;

use InvalidArgumentException;

/**
 * A class to convert Error into Exception
 *
 * <?php
 *
 * use Carpediem\Errors\CaptureError;
 * use Carpediem\Errors\ErrorToException;
 *
 * $touch = new ErrorToException(new CaptureError('touch', E_WARNING), 'RuntimeException');
 * try {
 *    $touch('/usr/bin/foo.bar');
 * } catch (RuntimeException $e) {
 *    echo $e->getMessage();
 * }
 */
class ErrorToException
{
    /**
     * @var CaptureError
     */
    protected $callable;

    /**
     * Exception class to instantiate
     *
     * @var string
     */
    protected $exception_class_name;

    /**
     * A new instance
     *
     * @param CaptureError $callable
     * @param string       $exception_class_name The exception to be thrown
     */
    public function __construct(CaptureError $callable, $exception_class_name = 'RuntimeException')
    {
        $this->callable = $callable;
        $this->setExceptionClass($exception_class_name);
    }

    /**
     * Set the Exception class
     *
     * @param string $className error reporting level
     *
     * @throws InvalidArgumentException If the class is not a throwable object
     */
    public function setExceptionClass($className)
    {
        if (!$this->isThrowable($className) && !$this->isException($className)) {
            throw new InvalidArgumentException(sprintf(
                'The class name provided `%s` is neither a Throwable nor an Exception object',
                $className
            ));
        }

        $this->exception_class_name = $className;
    }

    /**
     * Is the ClassName a Throwable Object (PHP7+)
     *
     * @param string $className
     *
     * @return bool
     */
    protected function isThrowable($className)
    {
        return interface_exists('Throwable') && is_subclass_of($className, 'Throwable');
    }

    /**
     * Is the ClassName an Exception Object (PHP5+)
     *
     * @param string $className
     *
     * @return bool
     */
    protected function isException($className)
    {
        return $className === 'Exception' || is_subclass_of($className, 'Exception');
    }

    /**
     * Get the current Error Reporting Level
     *
     * @return string
     */
    public function getExceptionClass()
    {
        return $this->exception_class_name;
    }

    /**
     * Process the callable
     *
     * @param mixed ...$args the arguments associated with the callable
     *
     * @return mixed
     */
    public function __invoke()
    {
        $result = call_user_func_array($this->callable, func_get_args());
        $errorLevel = $this->callable->getErrorReporting();
        $errorCode = $this->callable->getLastErrorCode();
        $errorMessage = $this->callable->getLastErrorMessage();
        if (!empty($errorMessage)) {
            throw new $this->exception_class_name($errorMessage, $errorCode);
        }

        return $result;
    }
}
