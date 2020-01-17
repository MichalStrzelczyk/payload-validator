# Payload Validator Library

This library is an extension for the `opis/json-schema` package. The idea is to create one json standard for 
sanitizing and validation http request payloads. Moreover this package has two very useful features:
- The ability to define error message for each of the validator
- The ability to define sanitizers for each of variable

Important links:
- JSON Schema https://json-schema.org/
- Documentation of `opis/json-schema` package https://docs.opis.io/json-schema/1.x/

## Installation

```
composer install miinto/payload_validator
```

## Usage

### 1. Variable sanitizing - query string parameters case

As you know all query parameters are assigned as a string type. We are not able to validate these parameters using  
basic validators from the `opis/json-schema` library. To do this you need to add `sanitizers` section in the json 
schema. All these sanitizers will be run before validation in the same order as we defined in json array. 

```json
{
  "type": "object",      
  "properties": {
    "age": {
      "type": "integer",
      "default": 0,
      "minimum": 18,
      "maximum": 99,
      "sanitizers": [
        "toInteger"
      ]
      }
    }
  }          
```

**CAUTION** Sanitizer names are case sensitive 

If you need to define your custom sanitizer, you can do this by adding a class path in the `sanitizers` section. This 
class must implement `\PayloadValidator\Sanitizer\SanitizerInterface`.
```json
{
  "type": "object",      
  "properties": {
    "age": {
      "type": "integer",          
      "sanitizers": [
        "toInteger",
        "\\This\\Is\\Path\\To\\My\\SanitizerClass"
      ]
      }
    }
}         
```

### 2. Error messages customization

One variable can assign many validators and for each of them you can define a custom error message. To do this you 
should add `errorMessages` key in your schema like this:

```json
{
  "type": "object",  
  "properties": {
    "age": {
      "type": "integer",
      "default": 0,
      "minimum": 18,
      "maximum": 99,
      "sanitizers": [
        "toInteger"
      ],
      "errorMessages": {
        "type": {
          "code": 2000,
          "message": "Parameter `age` should be numerical"
        },
        "minimum": {
          "code": 2001,
          "message": "Minimum `age` is 18"
        },
        "maximum": {
          "code": 2002,
          "message": "Maximum `age` is 99"
        }
      }
    }
  },
  "required": [
    "age"
  ]
}
```

**CAUTION** Matching an error to a custom error message using a validator criteria key. The same keys should be defined
into `errorMessages` section.

After validation you have an access to the error container in the Validator object. This array has all error 
messages.

Usage:

```php                
$testData = ['age' => '15'];
$schema = (new \PayloadValidator\Schema\Factory())->createFromArray($schemaAsArray);
$validator = (new \PayloadValidator\Validator\Factory())->create();
$validator->schemaValidation((object) $testData, $this->schema);
$errors = $validator->getErrorContainer();

// var_dump($errors);
// will return:
//
// [
//    [
//      'code' => '2001',
//      'message' => 'Minimum `age` is 18'
//    ]
// ]
```