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
 * A class to avoid using PHP's error control operator `@`
 *
 * <?php
 *
 * use Carpediem\Errors\CaptureError;
 *
 * $touch = new CaptureError('touch');
 * if (!$touch('/usr/bin/foo.bar')) {
 *     throw new RuntimeException($touch->getLastErrorMessage());
 * }
 */
class CaptureError
{
    /**
     * The callable to be processed
     *
     * @var callable
     */
    protected $callable;

    /**
     * Last error message
     *
     * @var string
     */
    protected $last_error_message = '';

    /**
     * Last error code
     *
     * @var int
     */
    protected $last_error_code = 0;

    /**
     * PHP error reporting level
     *
     * @var int
     */
    protected $error_level;

    /**
     * A new instance
     *
     * @param callable $callable    The code to capture error from
     * @param int      $error_level error reporting level
     */
    public function __construct(callable $callable, $error_level = E_ALL)
    {
        $this->callable = $callable;
        $this->setErrorReporting($error_level);
    }

    /**
     * Set the Error Reporting level
     *
     * @param int $error_level error reporting level
     *
     * @throws InvalidArgumentException If the reporting level is not a positive int
     */
    public function setErrorReporting($error_level)
    {
        if (!filter_var($error_level, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
            throw new InvalidArgumentException('Expected data must to be positive integer');
        }

        $this->error_level = $error_level;
    }

    /**
     * Get the current Error Reporting Level
     *
     * @return int
     */
    public function getErrorReporting()
    {
        return $this->error_level;
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
        $errorHandler = function ($code, $message) {
            $this->last_error_code = $code;
            $this->last_error_message = $message;
        };

        $this->last_error_code = 0;
        $this->last_error_message = '';
        set_error_handler($errorHandler, $this->error_level);
        $result = call_user_func_array($this->callable, func_get_args());
        restore_error_handler();

        return $result;
    }

    /**
     * Returns the last error code
     *
     * @return int
     */
    public function getLastErrorCode()
    {
        return $this->last_error_code;
    }

    /**
     * Returns the last error message
     *
     * @return string
     */
    public function getLastErrorMessage()
    {
        return $this->last_error_message;
    }
}
