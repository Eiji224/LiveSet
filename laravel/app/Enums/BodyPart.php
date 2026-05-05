<?php

namespace App\Enums;

enum BodyPart: string {
    case ARMS = 'Руки';
    case BACK = 'Спина';
    case CARDIO = 'Кардио';
    case CHEST = 'Грудные мышцы';
    case CORE = 'Мышцы кора';
    case FULL_BODY = 'Всё тело';
    case LEGS = 'Ноги';
    case SHOULDERS = 'Плечи';
    case OTHER = 'Другое';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
