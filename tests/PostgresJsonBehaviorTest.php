<?php

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class PostgresJsonBehaviorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!class_exists('Foo')) {
            $schema = <<<EOF
<database name="json_behavior" defaultIdMethod="native">
    <table name="foo">
        <column name="id" required="true" primaryKey="true" autoIncrement="true" type="INTEGER" />
        <column name="name" type="VARCHAR" required="true" />
        <column name="json1" type="VARCHAR" required="true"/>
        <column name="json2" type="VARCHAR" required="true"/>

        <behavior name="postgres_json">
            <parameter name="column_names" value="json1,json2" />
        </behavior>
    </table>
</database>
EOF;
            $builder = new PropelQuickBuilder();
            $config  = $builder->getConfig();
            $config->setBuildProperty('behavior.postgres_json.class', '../src/PostgresJsonBehavior');
            $builder->setConfig($config);
            $builder->setSchema($schema);
            $builder->build();
        }
    }


    public function testObjectMethods()
    {
        $this->assertTrue(method_exists('Foo', 'getJson1'));
        $this->assertTrue(method_exists('Foo', 'getJson2'));
        $this->assertTrue(method_exists('Foo', 'setJson1'));
        $this->assertTrue(method_exists('Foo', 'setJson2'));
        $this->assertTrue(method_exists('Foo', 'setJson1Path'));
        $this->assertTrue(method_exists('Foo', 'setJson2Path'));
        $this->assertTrue(method_exists('Foo', 'initJsonFields'));
    }

    public function testQueryMethods()
    {
        $this->assertTrue(method_exists('FooQuery', 'filterByJson1Path'));
        $this->assertTrue(method_exists('FooQuery', 'filterByJson2Path'));
    }

    public function testAccessMethods()
    {
        $foo = new Foo();
        $foo->setJson1(array('key' => 'value'));

        $this->assertEquals(array('key' => 'value'), $foo->getJson1());
        $this->assertEquals('value', $foo->getJson1('key'));

        $foo->setJson2(array('key' => array('lower' => 'test')));
        $this->assertEquals('test', $foo->getJson2('key.lower'));
    }


    public function testSetPathMethods()
    {
        $foo = new Foo();
        $foo->setJson1(array('key' => 'value'));

        $this->assertEquals(array('key' => 'value'), $foo->getJson1());
        $foo->setJson1Path('test.lower', 'new');
        $this->assertEquals(array('key' => 'value', 'test' => array('lower' => 'new')), $foo->getJson1());
        $foo->setJson1Path('test.lower', 'new2');
        $this->assertEquals(array('key' => 'value', 'test' => array('lower' => 'new2')), $foo->getJson1());

    }

    /**
     * @expectedException PropelException
     */
    public function testSetPathMethodsException()
    {
        $foo = new Foo();
        $foo->setJson1(array('key' => 'value'));

        $this->assertEquals(array('key' => 'value'), $foo->getJson1());
        $foo->setJson1Path('key.lower', 'new');
    }
}
