<?php

namespace App;

final readonly class People
{
    /**
     * @param People[] $people
     */
    private function __construct(private array $people) {}

    public static function new(string $string): People
    {
        return new self(array_filter(array_map(
            fn(string $s) => Person::new($s),
            explode(',', $string),
        )));
    }

    public function string(): string
    {
        return join(', ', array_map(
            fn(Person $person) => $person->string(),
            $this->people,
        ));
    }
}
