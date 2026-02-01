<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case BANK_TRANSFER = 'bank_transfer';
    case TRANSFER = 'transfer';
    case CARD = 'card';
    case MOBILE_MONEY = 'mobile_money';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this) {
            self::CASH => 'Cash',
            self::BANK_TRANSFER => 'Bank Transfer',
            self::TRANSFER => 'Transfer',
            self::CARD => 'Card',
            self::MOBILE_MONEY => 'Mobile Money',
            self::OTHER => 'Other',
        };
    }
}
