<?php
/**
* This file is part of the Carpediem.Errors library
*
* @license http://opensource.org/licenses/MIT
* @link https://github.com/carpediem/errors/
* @version 0.2.0
* @package Carpediem.errors
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Carpediem\Errors;

/**
 * A Interface that represents a class that can capture an error.
 */
interface CaptureErrorInterface
{
    /**
     * Process the object payload
     *
     * If an error occurs during the process, PHP's error code and message code MUST be store
     * to be accessed by CaptureErrorInterface::getLastErrorCode and CaptureErrorInterface::getLastErrorMessage
     *
     * Between each calls, the data store MUST be reset
     *
     * @param mixed ...$args the arguments associated with the callable
     *
     * @return mixed
     */
    public function __invoke();

    /**
     * Returns the last error code
     *
     * Between calls to CaptureErrorInterface::__invoke the code MUST be reset to 0
     *
     * If no error is capture the method MUST return 0
     *
     * @return int
     */
    public function getLastErrorCode();

    /**
     * Returns the last error message
     *
     * Between calls to CaptureErrorInterface::__invoke the code MUST be reset to an empty string
     *
     * If no error is capture the method MUST return an empty string
     *
     * @return string
     */
    public function getLastErrorMessage();
}
