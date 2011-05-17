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
class Mock
{
    /**
     * Creates a mock of the class given
     *
     * @param string $class
     * @return object
     */
    public static function mock($class)
    {
        $class = self::getClassBody($class);
        eval ($class['body']);
        return unserialize(
            sprintf(
                'O:%d:"%s":0:{}',
                strlen($class['name']), $class['name']
            )
        );
    }

    /**
     * Creates the mocked class body to be evaluated extending the original
     * one and adding a interceptor and stubbing enabling methods
     *
     * @param string $class
     * @return array
     */
    public static function getClassBody($class)
    {
        $properties = $methods = '';
        $className = "__Mock_{$class}_" . md5((string)microtime());
        $body = <<<CLASS_BODY

if (!class_exists('$class', false)) {
    class $class {}
}
        
class $className extends $class
{
    public \$__mock_stubs = array();
    $properties
    $methods
    
    public function stub(\$methodToStub)
    {
        if (isset(\$this->__mock_stubs[\$methodToStub])) {
            unset(\$this->__mock_stubs[\$methodToStub]);
        }
        \$stub = new \\PHPSpec\\Mocks\\Stub(\$methodToStub);
        \$this->__mock_stubs[\$methodToStub] = \$stub;
        return \$stub;
    }
    
    public function stubChain()
    {
        \$stubs = func_get_args();
        for (\$i = 0; \$i < func_num_args(); \$i) {
            \$\$stubs[\$i] = double();
        }
        for (\$i = 0; \$i < func_num_args(); \$i) {
            if (\$i === 0) {
                \$this->stub(\$stubs[\$i])->andReturn(\$\$stubs[\$i+1]);
            } elseif (\$i !== func_num_args() - 1) {
                \$\$stubs[\$i]->stub(\$\$stubs[\$i+1])->andReturn(
                    \$\$stubs[\$i+1]
                );
            } else {
                \$\$stubs[\$i]->stub(\$\$stubs[\$i+1])->andReturn(
                    \$this->__mock_stubs[0]
                );
            }
        }
        return \$this->__mock_stubs[0];
    }
    
    public function __get(\$property)
    {
        if (\$stub = \$this->__mock_getStubFor(\$property)) {
            return \$stub->__stub_getResultToReturn();
        }
        trigger_error("Undefined property: $class::\$property", E_USER_NOTICE);
    }
    
    public function __call(\$method, \$args)
    {
        \$stub = \$this->__mock_getStubFor(\$method);
        if (\$stub instanceof \\PHPSpec\\Mocks\\Stub
            && ((array)(\$stub->__stub_getArgs()) === \$args)) {
            if (\$e = \$stub->__stub_getExceptionToThrow()) {
                throw new \$e;
            }
            try {
                \$result = \$stub->__stub_getResultToReturn();
                return \$result;
            } catch (\\PHPSpec\\Mocks\\Exception \$e) {
                return null;
            }
        }
    }
    
    protected function __mock_getStubFor(\$method) {
        foreach (\$this->__mock_stubs as \$stubName => \$stub) {
            if (\$stub->__stub_getMethod() === \$method) {
                return \$stub;
            }
        }
        return false;
    }
}
CLASS_BODY;
        return array('name' => $className, 'body' => $body);
    }
}