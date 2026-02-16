<?php

namespace App\Enum;

enum VideoGameStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case HIDDEN = 'hidden';
    case ARCHIVED = 'archived';
}
