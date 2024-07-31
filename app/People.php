<?php

namespace App;

final readonly class People
{
    /**
     * @param People[] $people
     */
    private function __construct(private array $people) {}

    public static function new(string $string): ?People
    {
        return new self(array_filter(array_map(
            fn(object $person) => Person::new($person),
            json_decode($string),
        )));
    }

    public function string(): string
    {
        $string = join(', ', array_map(
            fn(Person $person) => $person->string(),
            $this->people,
        ));
        return strlen($string) > 30 ? substr($string, 0, 27) . '...' : $string;
    }

    public function longString(): string
    {
        return join(', ', array_map(
            fn(Person $person) => $person->longString(),
            $this->people,
        ));
    }
}
