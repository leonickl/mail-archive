<?php

namespace App;

final readonly class Person
{
    private function __construct(
        private string $address,
        private string $name,
    ) {}

    public static function new(object $data): Person
    {
        return new self(address: trim($data->address), name: trim($data->display));
    }

    public function string(): string
    {
        return $this->name === '' ? $this->address : $this->name;
    }

    public function longString(): string
    {
        return $this->name.' <'.$this->address.'>';
    }
}
