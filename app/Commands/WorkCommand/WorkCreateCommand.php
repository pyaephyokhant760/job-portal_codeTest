<?php

namespace App\Commands\WorkCommand;

class WorkCreateCommand
{
    public function __construct(
        public int $employer_id,
        public string $title,
        public string $description,
        public int $category_id,
        public string $location,
        public string $expiry_date
    ) {}
}
