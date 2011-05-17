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

/**
 * Wrapper for {@link \PHPSpec\Mocks\Mock::mock()}
 * 
 * @param string $class
 * @param array  $stubs
 * @return object
 */
function double($class = 'stdClass', $stubs = array())
{
    $double = \PHPSpec\Mocks\Mock::mock($class);
    if (!empty($stubs)) {
        foreach($stubs as $stub => $value) {
            $double->stub($stub)->andReturn($value);
        }
    }
    return $double;
}

/**
 * Wrapper for {@link \PHPSpec\Mocks\Mock::mock()}
 * 
 * @param string $class
 * @param array  $stubs
 * @return object
 */
function mock($class = 'stdClass', $stubs = array())
{
    return double($class, $stubs);
}

/**
 * Wrapper for {@link \PHPSpec\Mocks\Mock::mock()}
 * 
 * @param string $class
 * @param array  $stubs
 * @return object
 */
function stub($class = 'stdClass', $stubs = array())
{
    return double($class, $stubs);
}