<?php

namespace GnarPhp\Request;

use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Http\Request;


class RequestEntity extends Request
{

    /**
     * @var string
     */
    protected $method = 'all';
    
    /**
     * @example ['field' => 'required|string']
     * @var array
     */
    protected $fields = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var bool
     */
    protected $useValidator = true;

    /**
     * @var Validator
     */
    protected $validator;

    

    /**
     * @var array
     */
    protected $validatorMessages = [];

    /**
     * @var array
     */
    protected $validatorCustomAttributes = [];

    /**
     * @var string
     */
    protected $lang = 'en';


    public function __construct(array $query, array $request, array $attributes, array $cookies, array $files, array $server, $content)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * @param array|null $validatorMessages
     * @param array|null $validatorCustomAttributes
     * @return Validator
     */
    public function validate(array $validatorMessages = null, array $validatorCustomAttributes = null)
    {
        if(!is_null($validatorMessages)) {
            $this->validatorMessages = $validatorMessages;
        }

        if(!is_null($validatorCustomAttributes)) {
            $this->validatorCustomAttributes = $validatorCustomAttributes;
        }

        $this->validator = $this->setupValidator()->make(
            $this->requestData,
            $this->fields,
            $this->validatorMessages,
            $this->validatorCustomAttributes
        );

        return $this->validator;
    }

    /**
     * If the Validator is not found make a new one
     *
     * @return ValidatorFactory
     */
    protected function setupValidator()
    {
        // the goal is to be portable...right?
        if(class_exists(\Validator::class)) {
            return \Validator;
        }
        return new \Illuminate\Validation\Factory(new \Symfony\Component\Translation\Translator($this->lang));
    }

    

}