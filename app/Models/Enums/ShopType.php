<?php

namespace App\Models\Enums;

enum ShopType: string
{
    case TAKEAWAY = 'takeaway';

    case SHOP = 'shop';

    case RESTAURANT = 'restaurant';

    /**
     * @return string
     */
    public function getName(): string {
        return match ($this) {
            self::TAKEAWAY => 'Takeaway',
            self::SHOP => 'Shop',
            self::RESTAURANT => 'Restaurant',
        };
    }

}
