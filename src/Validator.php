<?php

declare (strict_types=1);

namespace PayloadValidator;

use \Opis\JsonSchema\ISchema;
use \Opis\JsonSchema\ISchemaLoader;
use \Opis\JsonSchema\ValidationResult;

class Validator extends \Opis\JsonSchema\Validator
{
    /** @var array */
    protected $errorContainer = [];

    /**
     * @return array
     */
    public function getErrorContainer(): array
    {
        return $this->errorContainer;
    }

    /**
     * @param $data
     * @param ISchema $schema
     * @param int $max_errors
     * @param ISchemaLoader|null $loader
     *
     * @return ValidationResult
     */
    public function schemaValidation(
        $data,
        ISchema $schema,
        int $max_errors = 1,
        ISchemaLoader $loader = null
    ): ValidationResult {
        $data = (object)$this->sanitize((array)$data, $schema);
        $bug = parent::schemaValidation($data, $schema, $max_errors, $loader);

        $this->errorContainer = [];
        if ($bug->isValid() === false) {
            $errors = $bug->getErrors();
            foreach ($errors as $error) {
                $errorType = $error->keyword();

                if ($errorType === 'required') {
                    $name = $error->keywordArgs()['missing'];
                } else {
                    $name = $error->dataPointer()[0];
                }

                $fieldError = (array)$schema->resolve()->properties->{$name}->errorMessages->{$error->keyword()};
                $this->errorContainer += $fieldError;
            }
        }

        return $bug;
    }

    /**
     * @param array $requestParameters
     * @param ISchema $schema
     *
     * @return array
     */
    public function sanitize(array $requestParameters, ISchema $schema): array
    {
        foreach ($requestParameters as $key => $keyData) {
            $sanitizers = $schema->resolve()->properties->{$key}->sanitizers;
            if (!\is_array($sanitizers) || \count($sanitizers) === 0) {
                continue;
            }

            foreach ($sanitizers as $sanitizerName) {
                $sanitizerClassName = (\class_exists(
                    $sanitizerName
                )) ? $sanitizerName : '\PayloadValidator\Sanitizer\\' . \ucfirst($sanitizerName);

                if (!\class_exists($sanitizerClassName)) {
                    throw new \RuntimeException('Class: ' . $sanitizerClassName . ' doesn\'t exist');
                }

                $requestParameters[$key] = \call_user_func_array([$sanitizerClassName, "sanitize"], [$keyData]);
            }
        }

        return $requestParameters;
    }
}