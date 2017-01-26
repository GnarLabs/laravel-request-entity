<?php

namespace GnarPhp\Request\Parser;

use Illuminate\Http\Request;

class Parser implements ParserInterface
{
    protected $request;

    protected $fields = [];

    protected $data = [];

    public function __construct(Request $request, array $fields)
    {
        $this->request = $request;
        $this->initFields($fields);
    }

    protected function initFields(array $fields)
    {
        foreach($fields as $field) {
            $this->fields[$field] = null;
        }
    }

    public function all() : array
    {
        $data = $this->request->all(array_keys($this->fields));
        return array_merge($this->fields, $data);
    }

    public function input() : array
    {
        $data = $this->request->only(array_keys($this->fields));
        return array_merge($this->fields, $data);
    }

    public function json() : array
    {
        return $this->multiple('json');
    }

    public function query() : array
    {
        return $this->multiple('query');
    }

    public function header() : array
    {
        return $this->multiple('header');
    }

    public function file() : array
    {
        $files = $this->request->allFiles();
        $fields = implode(",", array_keys($this->fields));
        $data = array_column($files, $fields);
        return array_merge($this->fields, $data);
    }

    public function cookie() : array
    {
        return $this->multiple('cookie');
    }

    protected function multiple(string $method) : array
    {
        $fields = implode(",", array_keys($this->fields));
        $json = $this->request->$method()->all();
        $data = array_column($json, $fields);
        return array_merge($this->fields, $data);
    }
}