<?php

namespace App\Commands\CategoryCommand;

class CategoryCommand
{
    public function __construct(
        public string $name,
    ) {}
}