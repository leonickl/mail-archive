<?php

namespace App;

final readonly class Person
{
    private function __construct(
        private string  $address,
        private ?string $name,
    ) {}

    public static function new(string $string): ?Person
    {
        // Regular expression to match the name and email format
        $pattern_with_name = '/^(.+?)\s*<(.+?@.+?\..+?)>$/';
        $pattern_without_name = '/^(.+?@.+?\..+?)$/';

        if (preg_match($pattern_with_name, $string, $matches)) {
            return new self(address: $matches[2], name: trim($matches[1]));
        } elseif (preg_match($pattern_without_name, $string, $matches)) {
            return new self(address: $matches[1], name: null);
        }

        return null;
    }

    public function string(): string
    {
        return $this->name ?? $this->address;
    }
}
