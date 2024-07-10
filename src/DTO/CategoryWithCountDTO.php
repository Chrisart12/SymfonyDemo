<?php 

namespace App\DTO;

use DateTimeImmutable;


class CategoryWithCountDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly DateTimeImmutable $createdAt,
        public readonly int $count
    )
    {
      
    }
}

