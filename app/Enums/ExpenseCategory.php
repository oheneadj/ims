<?php

namespace App\Enums;

enum ExpenseCategory: string
{
    case EQUIPMENT = 'equipment';
    case SUPPLIES = 'supplies';
    case RESTOCKING = 'restocking';
    case TRANSPORTATION = 'transportation';
    case RENT = 'rent';
    case UTILITIES = 'utilities';
    case MARKETING = 'marketing';
    case SALARIES = 'salaries';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this) {
            self::EQUIPMENT => 'Equipment',
            self::SUPPLIES => 'Supplies',
            self::RESTOCKING => 'Inventory Restocking',
            self::TRANSPORTATION => 'Transportation',
            self::RENT => 'Rent',
            self::UTILITIES => 'Utilities',
            self::MARKETING => 'Marketing',
            self::SALARIES => 'Salaries',
            self::OTHER => 'Other',
        };
    }
}
