<?php

namespace Interfaces;

interface UserContract
{
    public function getOne(string $value, string $case = 'id'): array | string;
    public function login(array $userData): array | string;
    public function create(): array | string;
    public function get(): array | string;
}
