<?php

namespace App\Enum;

enum ProductStatus: string
{
    case NON_COMMENCE = 'Non commencé';
    case EN_COURS = 'En cours';
    case TERMINE = 'Terminé';
    case SUSPENDU = 'Suspendu';
}


class Status
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const PENDING = 'pending';

    // Altri metodi se necessario
}
