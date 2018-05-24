<?php
use PHPUnit\Framework\TestCase;
use \SkyOJ\Helper\ParamTypeChecker;
class paramTypeCheckerTest extends TestCase
{
    public function testCheckInt()
    {
        $this->assertTrue( ParamTypeChecker::check('int',123) );
        $this->assertTrue( ParamTypeChecker::check('int',0) );
        $this->assertTrue( ParamTypeChecker::check('int','0') );
        $this->assertTrue( ParamTypeChecker::check('int','123') );
        $this->assertTrue( ParamTypeChecker::check('int','2147483647') );

        $this->assertFalse( ParamTypeChecker::check('int',12.3) );
        $this->assertFalse( ParamTypeChecker::check('int','00') );
        $this->assertFalse( ParamTypeChecker::check('int','0.0') );
        $this->assertFalse( ParamTypeChecker::check('int','087') );
        $this->assertFalse( ParamTypeChecker::check('int','abcd') );
        $this->assertFalse( ParamTypeChecker::check('int',[1,2,3]) );
    }

    public function testCheckJson()
    {
        $this->assertTrue( ParamTypeChecker::check('json','{}') );
        $this->assertTrue( ParamTypeChecker::check('json','[]') );
        $this->assertTrue( ParamTypeChecker::check('json','{"test":"test" , "testnum":123}') );
        $this->assertTrue( ParamTypeChecker::check('json','{"test":"test" , "testnum":123, "testarr":[1,2,3]}') );
        $this->assertTrue( ParamTypeChecker::check('json','[1,2,3,4,5]') );
        $this->assertTrue( ParamTypeChecker::check('json','["a","b"]') );
        $this->assertTrue( ParamTypeChecker::check('json','["a\"","b"]') );
        $this->assertTrue( ParamTypeChecker::check('json','[[1,2,3,4,5],[4,5,6]]') );
        $this->assertTrue( ParamTypeChecker::check('json','[[1,2,3,4,5],[4,5,6],{"test":"test" , "testnum":123, "testarr":[1,2,3]}]') );
        $this->assertTrue( ParamTypeChecker::check('json','123') );
        $this->assertTrue( ParamTypeChecker::check('json',123) );

        $this->assertFalse( ParamTypeChecker::check('json','{]') );
        $this->assertFalse( ParamTypeChecker::check('json','[1,2,3,4,{}') );
        $this->assertFalse( ParamTypeChecker::check('json','[1,2,3,4,]') );
        $this->assertFalse( ParamTypeChecker::check('json','{"test":"test" , "testnum":123 ,}') );
    }

    public function testCheckString()
    {
        $this->assertTrue( ParamTypeChecker::check('string','string') );
        $this->assertTrue( ParamTypeChecker::check('string',"abcdef") );
        $this->assertTrue( ParamTypeChecker::check('string','\'"aaa') );
        $this->assertTrue( ParamTypeChecker::check('string','123') );
        $this->assertTrue( ParamTypeChecker::check('string','{}') );
        $this->assertTrue( ParamTypeChecker::check('string',"12345") );

        $this->assertFalse( ParamTypeChecker::check('string',12.3) );
        $this->assertFalse( ParamTypeChecker::check('string',123) );
        $this->assertFalse( ParamTypeChecker::check('string',['1','2']) );
    }

    public function testCheckArray()
    {
        $array = [1,2,3];
        $type = ['int'];
        $this->assertTrue( ParamTypeChecker::check($type,$array) );
        $array = ['1','2','3','abc'];
        $type = ['string'];
        $this->assertTrue( ParamTypeChecker::check($type,$array) );
        $array = ['[1]','[2]','[3]','{"text":"xxx","num":123}'];
        $type = ['json'];
        $this->assertTrue( ParamTypeChecker::check($type,$array) );
        $array = [[1,2],[3,4]];
        $type = [['int']];
        $this->assertTrue( ParamTypeChecker::check($type,$array) );

        $array = ['1','2','3',123];
        $type = ['string'];
        $this->assertFalse( ParamTypeChecker::check($type,$array) );
        $array = ['1','2','3',[1,2,3]];
        $type = ['string'];
        $this->assertFalse( ParamTypeChecker::check($type,$array) );
        $array = ['1a',123];
        $type = ['int'];
        $this->assertFalse( ParamTypeChecker::check($type,$array) );
        $array = [[1,2,3],[1,2,3],[1,2,3]];
        $type = ['int'];
        $this->assertFalse( ParamTypeChecker::check($type,$array) );
        $array = ['{"text":"xxx","num":123}','123]'];
        $type = ['json'];
        $this->assertFalse( ParamTypeChecker::check($type,$array) );
        $array = ['1','2','3'];
        $type = [['string']];
        $this->assertFalse( ParamTypeChecker::check($type,$array) );
    }

    public function testCheckObject()
    {
        $obj = new stdclass();
        $type = new stdclass();
        $obj->a = 'a';
        $obj->num = 1;
        $obj->arr = [1,2];
        $obj->arr2d = [[1,2],[3,4]];
        $obj->o = new stdclass();
        $obj->o->a = 'a';
        $type->a = 'string';
        $type->num = 'int';
        $type->arr = ['int'];
        $type->arr2d = [['int']];
        $type->o = new stdclass();
        $type->o->a = 'string';
        $this->assertTrue( ParamTypeChecker::check($type,$obj) );
        $obj = new stdclass();
        $type = new stdclass();
        $obj->a = 'a';
        $obj->b = 'b';
        $obj->num = 1;
        $obj->arr = [1,2];
        $obj->arr2d = [[1,2],[3,4]];
        $obj->o = new stdclass();
        $obj->o->a = 'a';
        $type->a = 'string';
        $type->num = 'int';
        $type->arr = ['int'];
        $type->arr2d = [['int']];
        $type->o = new stdclass();
        $type->o->a = 'string';
        $this->assertTrue( ParamTypeChecker::check($type,$obj) );

        $obj = new stdclass();
        $type = new stdclass();
        $obj->a = 'a';
        $obj->num = 1;
        $obj->arr = [1,2];
        $obj->arr2d = [[1,2],[3,4]];
        $obj->o = new stdclass();
        $obj->o->a = 'a';
        $type->a = 'string';
        $type->num = 'int';
        $type->arr = ['int'];
        $type->arr2d = [['int']];
        $type->o = new stdclass();
        $type->o->a = 'string';
        $type->b = 'string';
        $this->assertFalse( ParamTypeChecker::check($type,$obj) );
    }
}
