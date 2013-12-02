postgres-json-behavior
======================
[![Build Status](https://travis-ci.org/rasa-web/postgres-json-behavior.png?branch=master)](https://travis-ci.org/rasa-web/postgres-json-behavior)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/rasa-web/postgres-json-behavior/badges/quality-score.png?s=c68b08dde09423de76f8b365b3829e87551fbe33)](https://scrutinizer-ci.com/g/rasa-web/postgres-json-behavior/)
[![Code Coverage](https://scrutinizer-ci.com/g/rasa-web/postgres-json-behavior/badges/coverage.png?s=38e67ccbc8098ce7d1cd0777b546ddb67f5a30af)](https://scrutinizer-ci.com/g/rasa-web/postgres-json-behavior/)


PostgresSql JSON behavior for propel, This behavior only support postgres 9.3 and (maybe) upper version.

Add behavior into your build.properties : 

```
propel.behavior.postgres_json.class = path.to.vendor.rasa-web.propel-postgres-json-behavior.src.PostgresJsonBehavior
```

Create your schema:

```xml
<database name="json_behavior" defaultIdMethod="native">
    <table name="foo">
        <column name="id" required="true" primaryKey="true" autoIncrement="true" type="INTEGER" />
        <column name="name" type="VARCHAR" required="true" />
        <column name="json1" type="VARCHAR" required="true"/>
        <column name="json2" type="VARCHAR" required="true"/>

        <behavior name="postgres_json">
            <parameter name="column_names" value="json1,json2" />
	    <!-- throw exception on get{json}Path functions if the path is not available, 
	    default is false and the result is calculated base on the $default parameter of function -->
	    <parameter name="exception_on_not_found" value="false" />
        </behavior>
    </table>
</database>
```

Build your model, the type automatically changed to JSON in result sql. 

```php 
//Base class
$object->getJson1(); // Get the json1 field in array format (not string)
// If the exception_on_not_found is true then there is an exception if key is not available
$object->getJson1Path("key1.subkey.subkey.lastkey", $default);// get the 'value' {"key":{"subkey":{"subkey":{"lastkey": "value"}}}}
$object->setJson1Path("path.to.key", $value)
// Query class
$objectQuery->filterByJson1Path("a.b.c", "value"); // search for {"a":{"b":{"c":"value"}}}
```