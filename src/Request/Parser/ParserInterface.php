<?php

namespace GnarPhp\Request\Parser;


interface ParserInterface
{
    public function all() : array;

    public function input() : array;

    public function json() : array;

    public function query() : array;

    public function header() : array;

    public function file() : array;

    public function cookie() : array;
}