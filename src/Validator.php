<?php

declare (strict_types=1);

namespace Miinto\PayloadValidator;

use \Opis\JsonSchema\ISchema;
use \Opis\JsonSchema\ISchemaLoader;
use \Opis\JsonSchema\IValidatorHelper;
use \Opis\JsonSchema\IFormatContainer;
use \Opis\JsonSchema\IFilterContainer;
use \Opis\JsonSchema\IMediaTypeContainer;
use \Opis\JsonSchema\ValidationResult;
use \Opis\JsonSchema\ValidationError;
use \Miinto\PayloadValidator\Dto\Factory as DtoFactory;

class Validator extends \Opis\JsonSchema\Validator
{
    CONST ERROR_TYPE_REQUIRED = 'required';
    CONST ERROR_CODE_REQUIRED = 1000;
    CONST ERROR_MESSAGE_REQUIRED = 'The parameter `%` is required.';

    /** @var array */
    protected $errorContainer = [];

    /**
     * Validator constructor.
     * @param IValidatorHelper|null $helper
     * @param ISchemaLoader|null $loader
     * @param IFormatContainer|null $formats
     * @param IFilterContainer|null $filters
     * @param IMediaTypeContainer|null $media
     */
    public function __construct(
        IValidatorHelper $helper = null,
        ISchemaLoader $loader = null,
        IFormatContainer $formats = null,
        IFilterContainer $filters = null,
        IMediaTypeContainer $media = null,
        DtoFactory $dtoFactory = null
    ) {
        parent::__construct($helper, $loader, $formats, $filters, $media);
        $this->dtoFactory = $dtoFactory ?? new DtoFactory();
    }


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
        $this->sanitize($data, $schema);
        $validationResult = parent::schemaValidation($data, $schema, $max_errors, $loader);

        $this->errorContainer = [];
        if ($validationResult->isValid() === false) {
            $errors = $validationResult->getErrors();
            foreach ($errors as $error) {
                $errorType = $error->keyword();

                if ($errorType === self::ERROR_TYPE_REQUIRED) {
                    $name = $error->keywordArgs()['missing'];
                } else {
                    $name = $error->dataPointer()[0];
                }

                $this->errorContainer[] = $this->getErrorData($name, $schema, $error);
            }
        }

        return $validationResult;
    }

    /**
     * @param string $name
     * @param ISchema $schema
     * @param ValidationError $error
     *
     * @return array
     */
    private function getErrorData(string $name, ISchema $schema, ValidationError $error): array
    {
        if (isset($schema->resolve()->properties->{$name})) {
            $result = $schema->resolve()->properties->{$name}->errorMessages->{$error->keyword()};
            $errorCode = $result->code;
            $errorMessage = $result->message;
        } else {
            $errorCode = self::ERROR_CODE_REQUIRED;
            $errorMessage = \sprintf(self::ERROR_MESSAGE_REQUIRED, $name);
        }

        $errorEntry = $this->dtoFactory->createErrorEntry(
            $errorCode,
            $errorMessage,
            $name
        );

        return $errorEntry->toArray();
    }

    /**
     * @param object $requestParameters
     * @param ISchema $schema
     *
     * @return array
     */
    public function sanitize(object $requestParameters, ISchema $schema): object
    {
        $properties = \get_object_vars($requestParameters);

        foreach ($properties as $key => $keyData) {
            $sanitizers = $schema->resolve()->properties->{$key}->sanitizers ?? [];
            if (!\is_array($sanitizers) || \count($sanitizers) === 0) {
                continue;
            }

            foreach ($sanitizers as $sanitizerName) {
                $sanitizerClassName = (\class_exists(
                    $sanitizerName
                )) ? $sanitizerName : '\Miinto\PayloadValidator\Sanitizer\\' . \ucfirst($sanitizerName);

                if (!\class_exists($sanitizerClassName)) {
                    throw new \RuntimeException('Class: ' . $sanitizerClassName . ' doesn\'t exist');
                }

                $requestParameters->{$key} = \call_user_func_array([$sanitizerClassName, "sanitize"], [$keyData]);
            }
        }

        return $requestParameters;
    }
}