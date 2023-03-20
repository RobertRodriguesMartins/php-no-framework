<?php

namespace Interfaces;

interface ProductContract
{
    public function getOne(): array | string;
    public function create(): array | string;
    public function getAll(): array | string;
}
