<?php

namespace App;

final readonly class People
{
    /**
     * @param  People[]  $people
     */
    private function __construct(private array $people) {}

    public static function new(string $string): ?People
    {
        return new self(array_filter(array_map(
            fn (object $person) => Person::new($person),
            json_decode($string),
        )));
    }

    public function string(): string
    {
        return implode(', ', array_map(
            fn (Person $person) => $person->string(),
            $this->people,
        ));
    }

    public function longString(): string
    {
        return implode(', ', array_map(
            fn (Person $person) => $person->longString(),
            $this->people,
        ));
    }
}
