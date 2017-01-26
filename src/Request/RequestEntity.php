<?php

namespace GnarPhp\Request;

use GnarLabs\Tests\Requests\Mapper\Mapper;
use GnarPhp\Request\Parser\Parser;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Http\Request;

class RequestEntity implements RequestEntityInterface
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $accessor = 'all';
    
    /**
     * @example ['field' => 'required|string']
     * @var array
     */
    protected $fields = [];

    /**
     * @var array
     */
    protected $map = null;

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

    /**
     * @var
     */
    protected $parser = Parser::class;

    public function __construct(Request $request)
    {
       $this->request = $request;
        if($this->useValidator) {
            $this->validate();
        }
        if(!is_null($this->map) && class_exists($this->map)) {
            $this->mapFields();
        }
    }

    protected function mapFields()
    {
        $mapper = new $this->map();
        $this->data = $mapper->transform();
    }

    protected function parse()
    {
        $fields = array_keys($this->fields);
        $accessor = $this->accessor;
        $parser = new $this->parser($this->request, $fields);
        $this->data = $parser->$accessor();
    }


    public function validate()
    {
        $this->validator = $this->setupValidator()->make(
            $this->data,
            $this->fields,
            $this->validatorMessages,
            $this->validatorCustomAttributes
        );
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

    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }

    /**
     * @return string
     */
    public function toJson() : string
    {
        return json_encode($this->data);
    }

    public function toArray() : array
    {
        return (array) $this->data;
    }

}