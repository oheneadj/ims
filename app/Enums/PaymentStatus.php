<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PAID = 'paid';
    case PARTIAL = 'partial';
    case CREDIT = 'credit';

    public function label(): string
    {
        return match($this) {
            self::PAID => 'Paid',
            self::PARTIAL => 'Partial',
            self::CREDIT => 'Credit',
        };
    }
}
