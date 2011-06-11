<?php

require_once 'PHPSpec/Mocks/Functions.php';

class DescribeMock extends \PHPSpec\Context
{
    function itCreatesAMockedObject()
    {
        $this->spec(double('Foo'))->should->beAnInstanceOf('Foo');
        $this->spec(mock('Foo'))->should->beAnInstanceOf('Foo');
        $this->spec(stub('Foo'))->should->beAnInstanceOf('Foo');
    }
    
    function itShouldMakePossibleToStubAMethod()
    {
        $foo = stub('Foo');
        $foo->stub('bar')
            ->andReturn('foo bar');
        
        $this->spec($foo->bar())->should->be('foo bar');
    }
    
    function itShouldBePossibleToDoPartialStubbing()
    {
        $foo = stub('Foo');
        $foo->stub('bar')
            ->shouldReceive(42)
            ->andReturn('chuck');
        $this->spec($foo->bar(42))->should->be('chuck');
        $this->spec($foo->bar(24))->shouldNot->be('chuck'); // <-- no need for types
        
        $foo = stub('Foo');
        $foo->stub('bar')
            ->shouldReceive(42, 'chuck')
            ->andReturn('norris');
        
        $this->spec($foo->bar(42, 'chuck'))->should->be('norris');
        $this->spec($foo->bar(42))->shouldNot->equal('norris');
    }
    
    function itShouldBePossibleToStubAProperty()
    {
        $foo = double('Foo');
        $foo->stub('bar')->andReturn('bar value');
        
        $this->spec($foo->bar)->should->be('bar value');
    }
    
    function itShouldBePossibleToUseAShortcutToSetStubAndProperties()
    {
        $foo = double('Foo', array('bar' => 123, 'zoo' => 321));
        $this->spec($foo->bar)->should->be(123);
        $this->spec($foo->zoo)->should->be(321);
    }
    
    function itShouldBePossibleToCreateAnEmptyStub()
    {
        $double = double();
        $double->stub('foo')->andReturn('bar');
        $this->spec($double->foo)->should->be('bar');
    }
    
    function itShouldBePossibleToCreateAStubChain()
    {
        $this->pending('Not implemented yet');
        $request = double();
        $request->stubChain('frontController', 'dispatcher', 'route', 'request');

        /* this is the same as 
           $frontController = double();
           $dispatcher      = double();
           $route           = double();
           $request         = double();
           $request->stub('frontController')->andReturn($frontController);
           $frontController->stub('dispatcher')->andReturn($dispatcher);
           $dispatcher->stub('route')->andReturn($route);
           $route->stub('request')->andReturn($request);
        */
        $this->spec($request->frontController)
             ->should->equal($request);
    }
    
    function itShouldBePossibleToDefineCounters()
    {
        $foo = double('Foo');

        $foo->stub('bar')->exactly(3)->andReturn('bar');
        $foo->bar();
        $foo->bar();
        $foo->bar();
    }
    
    function itComplainsWhenStubIsCallLessTimesThanExpected()
    {
        $foo = double('Foo');
        try {
            $foo->stub('bar')->exactly(3)->andReturn('bar');
            $foo->bar();
            $foo->bar();
            unset($foo);
        } catch (\Exception $e) {
            $this->spec($e)->should->beAnInstanceOf('\PHPSpec\Mocks\ExpectedCountError');
        }
    }
    
    function itComplainsWhenStubIsCallMoreTimesThanExpected()
    {
        $foo = double('Foo');
        try {
            $foo->stub('bar')->exactly(2)->andReturn('bar');
            $foo->bar();
            $foo->bar();
            $foo->bar();
        } catch (\Exception $e) {
            $this->spec($e)->should->beAnInstanceOf('\PHPSpec\Mocks\ExpectedCountError');
        }
    }
    
    function itKnowsNeverMeansNever()
    {
        $foo = double('Foo');
        try {
            $foo->stub('bar')->never()->andReturn('bar');
            $foo->bar();
        } catch (\Exception $e) {
            $this->spec($e)->should->beAnInstanceOf('\PHPSpec\Mocks\ExpectedCountError');
        }
    }
    
    function itDoesntComplainItMethodNeverGetsCalled()
    {
        $foo = double('Foo');
        $foo->stub('bar')->never()->andReturn('bar');
        unset($foo);
    }
}