<?php

namespace App\Traits;

use Carbon\Carbon;

trait DatesFormatTrait
{
    /**
     * Convert ISO 8601 duration (e.g., PT3H50M) to a human-readable format.
     *
     * @param string $duration The ISO 8601 duration string.
     * @return string The human-readable format of the duration.
     */
    private function convertISO8601ToHumanReadable(string $duration): string
    {
        $hours = 0;
        $minutes = 0;

        // Match the hours and minutes in the ISO 8601 duration
        preg_match('/PT(\d+H)?(\d+M)?/', $duration, $matches);

        if (isset($matches[1])) {
            $hours = (int) rtrim($matches[1], 'H');
        }

        if (isset($matches[2])) {
            $minutes = (int) rtrim($matches[2], 'M');
        }

        // Return the formatted string
        return ($hours > 0 ? $hours . ' hours ' : '') . ($minutes > 0 ? $minutes . ' minutes' : '');
    }

    /**
     * Format dateTime to a more human-readable format.
     *
     * @param string $dateTime The dateTime string to be formatted.
     * @return string The formatted dateTime string.
     */
    private function formatDateTime(string $dateTime): string
    {
        return Carbon::parse($dateTime)->format('F j, Y, g:i A');
    }
}
