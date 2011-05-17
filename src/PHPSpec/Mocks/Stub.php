<?php
/**
 * PHPSpec Mocks
 *
 * LICENSE
 *
 * This file is subject to the GNU Lesser General Public License Version 3
 * that is bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/lgpl-3.0.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@phpspec.org so we can send you a copy immediately.
 *
 * @category   PHPSpec_Mocks
 * @package    PHPSpec_Mocks
 * @author     Marcello Duarte
 * @copyright  Copyright (c) 2011 Marcello Duarte, Pádraic Brady,
 *                                Travis Swicegood
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 */
namespace PHPSpec\Mocks;

/**
 * @category   PHPSpec
 * @package    PHPSpec
 * @author     Marcello Duarte
 * @copyright  Copyright (c) 2011 Marcello Duarte, Pádraic Brady,
 *                                Travis Swicegood
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 */
class Stub
{
    /**
     * Counter value representing any number of times
     */
    const ANY = '*';
    
    /**
     * Expected number of times stub must be called
     *
     * @var integer
     */
    protected $_expected = Stub::ANY;
    
    /**
     * Number of times stub has been called
     *
     * @var string
     */
    protected $_counter;
    
    /**
     * Method (or property) being stubbed
     *
     * @var string
     */
    protected $_method;
    
    /**
     * Arguments to be passed to the method being stubbed
     *
     * @var string
     */
    protected $_args = array();
    
    /**
     * Exception to be thrown
     *
     * @var string
     */
    protected $_exceptionToThrow;
    
    /**
     * Result to be returned
     *
     * @var mixed
     */
    protected $_resultToReturn;
    
    /**
     * Creates a stub
     *
     * @param string $method 
     */
    public function __construct($method)
    {
        $this->_method = $method;
    }
    
    /**
     * Sets the value to be returned
     *
     * @param string $valueToReturn 
     * @return self
     */
    public function andReturn($valueToReturn)
    {
        $this->_resultToReturn = $valueToReturn;
        return $this;
    }
    
    /**
     * Sets the arguments it must receive
     *
     * @return sekf
     */
    public function shouldReceive()
    {
        $this->_args = func_get_args();
        return $this;
    }
    
    /**
     * Sets the number of times stub will be called
     *
     * @param integer $counter 
     * @return self
     */
    public function exactly($counter)
    {
        $this->_expected = $this->_counter = $counter;
        return $this;
    }
    
    /**
     * Sets the number of times stub will be called to 0
     *
     * @return self
     * @author Marcello Duarte
     */
    public function never()
    {
        $this->_expected = 0;
        return $this;
    }
    
    /**
     * Gets the exception to be thrown
     *
     * @return string
     */
    public function __stub_getExceptionToThrow()
    {
        return $this->_exceptionToThrow;
    }
    
    /**
     * Returns the result intended to be returned
     *
     * @return mixed
     */
    public function __stub_getResultToReturn()
    {
        if ($this->_expected !== self::ANY && $this->_counter === 0) {
            throw new \PHPSpec\Mocks\ExpectedCountError(PHP_EOL .
                "           expected: $this->_expected times" . PHP_EOL .
                "           received: " . ($this->_expected + 1) . " times"
            );
        }
        $this->_counter--;
        return $this->_resultToReturn;
    }
    
    /**
     * Returns the method/property being stubbed
     *
     * @return string
     */
    public function __stub_getMethod()
    {
        return $this->_method;
    }
    
    /**
     * Returns the arguments of the method being stubbed
     *
     * @return array
     */
    public function __stub_getArgs()
    {
        return $this->_args;
    }
}