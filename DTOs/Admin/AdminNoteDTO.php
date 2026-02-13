<?php

namespace App\DTO\Admin;

class AdminNoteDTO
{
    public function __construct(
        public int $id,
        public string $note,
        public string $createdAt,
        public ?string $updatedAt = null
    ) {}
}