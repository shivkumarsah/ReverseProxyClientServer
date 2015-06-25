<?php

namespace Zizaco\Confide;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Illuminate\Support\Facades\App as App;
use Illuminate\Support\Facades\Lang as Lang;

class UserValidatorTest extends PHPUnit_Framework_TestCase
{

    /**
     * Calls Mockery::close
     */
    public function tearDown()
    {
        m::close();
    }
    
    

    public function testShouldValidate()
    {
        /*
          |------------------------------------------------------------
          | Set
          |------------------------------------------------------------
         */
        $repo = m::mock('Zizaco\Confide\EloquentRepository');
        
        App::shouldReceive('make')
                ->with('confide.repository')
                ->andReturn($repo);
        $validator = m::mock(
                        'Zizaco\Confide\UserValidator' .
                        '[validatePassword,validateIsUnique,validateAttributes]'
        );
        $validator->shouldAllowMockingProtectedMethods();
        $user = m::mock('Zizaco\Confide\ConfideUserInterface');
        /*
          |------------------------------------------------------------
          | Expectation
          |------------------------------------------------------------
         */
        $validator->shouldReceive('validatePassword')
                ->once()->andReturn(true);
        $validator->shouldReceive('validateIsUnique')
                ->once()->andReturn(true);
        $validator->shouldReceive('validateAttributes')
                ->once()->andReturn(true);
        /*
          |------------------------------------------------------------
          | Assertion
          |------------------------------------------------------------
         */
        $this->assertTrue($validator->validate($user));
        
    }
    
   

    public function testShouldValidateIsUnique()
    {
        /*
          |------------------------------------------------------------
          | Set
          |------------------------------------------------------------
         */
        $repo = m::mock('Zizaco\Confide\EloquentRepository');
        $validator = m::mock('Zizaco\Confide\UserValidator[attachErrorMsg]');
        $validator->repo = $repo;
        $userA = m::mock('Zizaco\Confide\ConfideUserInterface');
        $userA->id = 1;
        $userA->email = 'zizaco@gmail.com';
        $userA->username = 'zizaco';
        $userB = m::mock('Zizaco\Confide\ConfideUserInterface');
        $userB->id = '2';
        $userB->email = 'foo@bar.com';
        $userB->username = 'foo';
        $userC = m::mock('Zizaco\Confide\ConfideUserInterface');
        $userC->id = '3';
        $userC->email = 'something@somewhere.com';
        $userC->username = 'something';
        $userD = m::mock('Zizaco\Confide\ConfideUserInterface');
        $userD->id = ''; // No id
        $userD->email = 'something@somewhere.com'; // Duplicated email
        $userD->username = 'something';
        /*
          |------------------------------------------------------------
          | Expectation
          |------------------------------------------------------------
         */
        $userA->shouldReceive('getKey')
                ->andReturn($userA->id);
        $userB->shouldReceive('getKey')
                ->andReturn($userB->id);
        $userC->shouldReceive('getKey')
                ->andReturn($userC->id);
        $userD->shouldReceive('getKey')
                ->andReturn($userD->id);
        $repo->shouldReceive('getUserByIdentity')
                ->andReturnUsing(function ($user) use ($userB, $userC) {
                            if (isset($user['email']) && $user['email'] == $userB->email) {
                                return $userB;
                            }
                            if (isset($user['email']) && $user['email'] == $userC->email) {
                                return $userC;
                            }
                        });
        $validator->shouldReceive('attachErrorMsg')
                ->atLeast(1)
                ->with(m::any(), 'confide::confide.alerts.duplicated_credentials', 'email');
        /*
          |------------------------------------------------------------
          | Assertion
          |------------------------------------------------------------
         */
        $this->assertTrue($validator->validateIsUnique($userA));
        $this->assertTrue($validator->validateIsUnique($userB));
        $this->assertTrue($validator->validateIsUnique($userC));
        $this->assertFalse($validator->validateIsUnique($userD));
    }
    
   

    public function testShouldAttachErrorMsgOnEmpty()
    {
        /*
          |------------------------------------------------------------
          | Set
          |------------------------------------------------------------
         */
        $errorBag = m::mock('Illuminate\Support\MessageBag');
        App::shouldReceive('make')
                ->with('Illuminate\Support\MessageBag')
                ->andReturn($errorBag);
        $validator = new UserValidator;
        $user = m::mock('Zizaco\Confide\ConfideUserInterface');
        $user->errors = null;
        /*
          |------------------------------------------------------------
          | Expectation
          |------------------------------------------------------------
         */
        Lang::shouldReceive('get')
                ->once()->with('foobar')
                ->andReturn('translated_foobar');
        $errorBag->shouldReceive('add')
                ->with('confide', 'translated_foobar')
                ->andReturn(true);
        /*
          |------------------------------------------------------------
          | Assertion
          |------------------------------------------------------------
         */
        $validator->attachErrorMsg($user, 'foobar');
        $this->assertInstanceOf('Illuminate\Support\MessageBag', $user->errors);
    }

    public function testShouldAttachErrorMsgOnExisting()
    {
        /*
          |------------------------------------------------------------
          | Set
          |------------------------------------------------------------
         */
        $errorBag = m::mock('Illuminate\Support\MessageBag');
        App::shouldReceive('make')
                ->with('Illuminate\Support\MessageBag')
                ->never();
        $validator = new UserValidator;
        $user = m::mock('Zizaco\Confide\ConfideUserInterface');
        $user->errors = $errorBag;
        /*
          |------------------------------------------------------------
          | Expectation
          |------------------------------------------------------------
         */
        Lang::shouldReceive('get')
                ->once()->with('foobar')
                ->andReturn('translated_foobar');
        $errorBag->shouldReceive('add')
                ->with('confide', 'translated_foobar')
                ->andReturn(true);
        /*
          |------------------------------------------------------------
          | Assertion
          |------------------------------------------------------------
         */
        $validator->attachErrorMsg($user, 'foobar');
        $this->assertInstanceOf('Illuminate\Support\MessageBag', $user->errors);
    }

}