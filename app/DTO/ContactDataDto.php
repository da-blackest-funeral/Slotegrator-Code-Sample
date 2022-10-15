<?php

namespace App\DTO;

class ContactDataDto
{
    public function __construct(
        public string $post,
        public string $email,
        public string $first_name,
        public string $last_name,
        public ?string $phone,
    ) {}
}
