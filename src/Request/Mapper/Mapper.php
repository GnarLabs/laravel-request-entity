<?php

namespace GnarLabs\Tests\Requests\Mapper;

class Mapper
{
    protected $includes = '';

    protected $map = [];

    protected $data = [];

    protected function transform($data)
    {
        $mapped = [];
        foreach($this->data as $key => $value) {
            if(isset($this->map[$key])) {
                $mapped[$this->map[$key]] = $value;
            }
        }
        return $this->map;
    }
}