<?php

namespace App\Services;

use Carbon\Carbon;

class dateFormatService
{
    public function formatDate(Carbon $createdAt)
    {
        $daysDifference = $createdAt->diffInDays(Carbon::now());

        return $createdAt->isToday()
            ? 'às ' . $createdAt->format('H:i')
            : ($daysDifference < 10
                ? $daysDifference . 'd'
                : $createdAt->translatedFormat('d M'));
    }
}
