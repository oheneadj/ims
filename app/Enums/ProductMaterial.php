<?php

namespace App\Enums;

enum ProductMaterial: string
{
    case GOLD = 'gold';
    case GOLD_PLATED = 'gold_plated';
    case SILVER = 'silver';
    case STERLING_SILVER = 'sterling_silver';
    case STAINLESS_STEEL = 'stainless_steel';
    case BEADS = 'beads';
    case CRYSTAL = 'crystal';
    case COSTUME = 'costume';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this) {
            self::GOLD => 'Gold',
            self::GOLD_PLATED => 'Gold Plated',
            self::SILVER => 'Silver',
            self::STERLING_SILVER => 'Sterling Silver',
            self::STAINLESS_STEEL => 'Stainless Steel',
            self::BEADS => 'Beads',
            self::CRYSTAL => 'Crystal',
            self::COSTUME => 'Costume/Fashion',
            self::OTHER => 'Other',
        };
    }
}
