<?php

namespace App\DTO\Admin;

class AuditLogItemDTO
{
    public function __construct(
        public int $id,
        public string $actorName,
        public string $actorRole,
        public string $action,      // "ORDER_UPDATED", "PRODUCT_CREATED" vs
        public string $entityType,  // "order", "product", "user" vs
        public ?string $entityId,   // "123" gibi (string bırakmak esnek)
        public string $createdAt,
        public ?string $meta = null // JSON veya kısa açıklama
    ) {}
}