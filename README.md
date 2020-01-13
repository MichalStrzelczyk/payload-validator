# Payload Validator Library

This library is an extension for the `opis/json-schema` package. The idea is to create one json standard file for 
sanitizing and validation http request payloads. Moreover this package has two very useful features:
- The ability to define error message for each of the validator
- The ability to define sanitizers for each of variable

Important links:
- JSON Schema https://json-schema.org/
- Documentation of `opis/json-schema` package https://docs.opis.io/json-schema/1.x/

## Installation

```
    composer install 
```

## Usage

### 1. Variable sanitizing - query string parameters

As you know all query parameters are assigned as a string type. We are not able to validate those parameters using  
basic validators from `opis/json-schema` library. To do this we need to add `sanitizers` section in the json schema.
All those sanitizers will be run before validation in the same order as we defined in json array. 

```
    {
      "type": "object",      
      "properties": {
        "age": {
          "type": "integer",
          "default": 0,
          "minimum": 18,
          "maximum": 99,
          "sanitizers": [
            "ToInteger"
          ],
          }
        }
      }      
    } 
```
If you need to define your custom sanitizer, you can do this adding a class path in the `sanitizers` section. This class 
must implement \PayloadValidator\Sanitizer\SanitizerInterface.
```
    {
      "type": "object",      
      "properties": {
        "age": {
          "type": "integer",          
          "sanitizers": [
            "ToInteger",
            "\This\Is\Path\To\My\SanitizerClass"
          ],
          }
        }
      }      
    } 
```

### 2. Error messages customization