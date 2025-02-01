<?php

namespace App\Models\Enums;

enum ShopStatus: string
{
    case OPEN = 'open';

    case CLOSED = 'closed';

    /**
     * @return string
     */
    public function getName(): string {
        return match ($this) {
            self::OPEN => 'Open',
            self::Closed => 'Closed'
        };
    }
}
