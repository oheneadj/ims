<?php

namespace App\Enums;

enum ProductType: string
{
    case NECKLACE = 'necklace';
    case EARRINGS = 'earrings';
    case BRACELET = 'bracelet';
    case RING = 'ring';
    case ANKLET = 'anklet';
    case PENDANT = 'pendant';
    case CHAIN = 'chain';
    case SET = 'set';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this) {
            self::NECKLACE => 'Necklace',
            self::EARRINGS => 'Earrings',
            self::BRACELET => 'Bracelet',
            self::RING => 'Ring',
            self::ANKLET => 'Anklet',
            self::PENDANT => 'Pendant',
            self::CHAIN => 'Chain',
            self::SET => 'Set',
            self::OTHER => 'Other',
        };
    }
}
