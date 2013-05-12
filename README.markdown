PHPSpec Mocks
=============

**PHPSpec Mocks** is a lightweight mocking framework designed to be used in the
PHP BDD Framework **PHPSpec**.

Examples
--------

### 1. Stubbing a method

Write your spec, replacing the dependency with a stub. Use the 'stub' method to
add a stubbed method and 'andReturn' to specify the value you want to be
returned: 
```php
<?php
class HelloWorldSpec extends \PHPSpec\Context
{
    function itGreetsAccordingToTheSetGreeterStrategy()
    {
        $greeter = mock('Greeter');
        $greeter->stub('greet')->andReturn('Hello, World!');
        
        $helloWorld = new HelloWorld($greeter);
        $this->spec($helloWorld->hello())->should->equal('Hello, World!');
    }
}
```

You can now write your class:
```php
<?php
class HelloWorld
{
    private $greeter;
    public function __construct(Greeter $message)
    {
        $this->greeter = $message;
    }
    
    public function hello()
    {
        return $this->greeter->greet();
    }
}
```
### 2. Partial stubbing

You can specify that you want a method to be stubbed only when certain arguments are
passed:
```php
<?php
class HelloWorldSpec extends \PHPSpec\Context
{
    function itGreetsAccordingToTheSetGreeterStrategy()
    {
        $greeter = mock('Greeter');
        $greeter->stub('greet')
                ->shouldReceive('Chuck')
                ->andReturn('Hello, Chuck!');
    
        $helloWorld = new HelloWorld($greeter);
        $this->spec($helloWorld->hello('Chuck'))
              ->should->equal('Hello, Chuck!');
}
```

### 3. Stubbing a property

It is possible to stub a property in the same manner:
```php
<?php
class HelloWorldSpec extends \PHPSpec\Context
{
    function itKnowsWhoItIsGreeting() // the class who knew too much?
    {
        $greeter = mock('Greeter');
        $greeter->stub('who')->andReturn('Chuck');
        
        $helloWorld = new HelloWorld($greeter);
        $this->spec($helloWorld->greetingWho())->should->equal('Chuck');
    }
}
```

```php    
<?php
class HelloWorld
{
    ...
    public function greetingWho()
    {
        return $this->greeter->who;
    }
}
```

### 4. Shortcut for creating a double

It is possible to create a double and stubbing methods/properties all at once.
So the method in the previous example could be replaced by
```php
<?php
...
function itKnowsWhoItIsGreeting() // the class who knew too much?
{
    $greeter = mock('Greeter', array('who' => 'Chuck');

    $helloWorld = new HelloWorld($greeter);
    $this->spec($helloWorld->greetingWho())->should->equal('Chuck');
}
```

### 5. Empty doubles

If your interface is type agnostic you can even create a mock without
giving it a name
```php
<?php
...
function itGreetsAccordingToTheSetGreeterStrategy()
{
    $greeter = mock(); // <-- no Greeter here
    $greeter->stub('greet')->andReturn('Hello, World!');

    $helloWorld = new HelloWorld($greeter);
    $this->spec($helloWorld->hello())->should->equal('Hello, World!');
}

...
// HelloWorld constructor:
public function __construct($greeter) // <-- no type hinting
```

### 6. Counters

It is also possible to specify mocks that expect a method/property to be
accessed a specified number of times:
```php
<?php
...
// property will be accessed exactly 1 time
$greeter->stub('who')->andReturn('Chuck')->exactly(1);

// property will never be accessed
$greeter->stub('who')->never();

// property will be accessed any number of times
$greeter->stub('who')->andReturn('Chuck');
```
