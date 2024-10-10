<?php

namespace App\Entity;

enum ProductUnit: string
{
    case KILOGRAM = 'kg';
    case GRAM = 'g';
    case LITER = 'l';
    case MILLILITER = 'ml';
}
