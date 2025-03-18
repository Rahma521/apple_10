<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

function getIcon(string $type): ?string
{
    return match ($type) {
        'offline' => get_file('ofline_courses.svg'),
        'zoom' => get_file('zoom_courses.svg'),
        'online' => get_file('online_courses.svg'),
        default => null,
    };
}

function folder_path(string $path): void
{
    if (! file_exists($path)) {
        mkdir($path, 0777, true);
    }
}

function get_file($filename): string
{
    if (! $filename) {
        return url('images/logo.png');
    }

    return url('uploads/'.$filename);
}

function get_export($filename): string
{
    if (! $filename) {
        return url('images/logo.png');
    }

    return url('exports/'.$filename);
}

function format_field_names($name, $details): array
{
    return [
        'name' => $name,
        'details' => $details,
    ];
}

function generate_verification_code(): int
{
    return rand(100000, 999999);
}

function get_nearest($query, $lat, $lng)
{
    return $query->select(
        '*',
        DB::raw(
            '6371 * acos(cos(radians('.$lat.'))
        * cos(radians(lat)) * cos(radians(lang) - radians('.$lng.'))
        + sin(radians('.$lat.')) * sin(radians(lat))) AS distance'
        )
    );
    //       ->having("distance", "<", $radius)

}

function distance($lat1, $lon1, $lat2, $lon2): float
{
    $pi80 = M_PI / 180;
    $lat1 *= $pi80;
    $lon1 *= $pi80;
    $lat2 *= $pi80;
    $lon2 *= $pi80;
    $r = 6372.797; // mean radius of Earth in km
    $dlat = $lat2 - $lat1;
    $dlon = $lon2 - $lon1;
    $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $km = $r * $c;

    return $km;
}

function change_locale(string $locale): string
{
    return [
        'ar' => 'en',
        'en' => 'ar',
    ][$locale];
}

function get_total_after_discount(float $total, ?float $percentage): float
{
    return $total - $total * ($percentage / 100);
}

function get_total_after_tax(float $total): float
{
    return $total + $total * (15 / 100);
}

function getDiscountValue(float $total, ?float $percentage): int
{
    return ($total * $percentage) / 100;
}

function getTaxValue(): int
{
    return 15;
    //($total * $setting->tax) / 100;
}

function dateFormatByDate(?string $date): array
{
    $dateformat = Carbon::createFromFormat('Y-m-d', $date ?? Carbon::now()->format('Y-m-d'));
    $month = $dateformat->format('m');
    $year = $dateformat->format('Y');
    $day = $dateformat->format('d');

    return [
        'year' => $year,
        'month' => $month,
        'day' => $day,
    ];
}

function convert_date(?string $date): string
{
    return Carbon::parse($date)->format('Y-m-d');
}

function convert_date_and_time(?string $date): string
{
    return Carbon::parse($date)->format('Y-m-d h:i:s');
}

function convert_to_iso(?string $date): string
{
    return Carbon::parse($date)->toDateTimeString();

}

function convert_time(?string $time): string
{
    return date('H:i', strtotime(convert2english($time)));
}

function convert_time_twelve(?string $time): string
{
    return $time !== null ? date('h:i A', strtotime($time)) : '';
}

function convert2day(?string $date): string
{
    $data = $date ?? Carbon::now()->format('Y-m-d');

    return Str::lower(Carbon::createFromFormat('Y-m-d', $data)->format('l'));
}

    function convert2english(string $string): string
{
    $newNumbers = ['PM', 'AM', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $arabic = ['م', 'ص', '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

    return str_replace($arabic, $newNumbers, $string);
}

    function convertNumbersToLocale($string)
    {
        if (app()->getLocale() == 'ar') {
            $westernNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            $arabicNumbers  = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
            return str_replace($westernNumbers, $arabicNumbers, $string);
        }
        return $string;
    }

function convert2arabic(string $string): string
{
    $newNumbers = ['PM', 'AM'];
    $arabic = ['م', 'ص'];

    return str_replace($newNumbers, $arabic, $string);
}

function getCountHours(?string $time_from, ?string $time_to): string
{
    if ($time_from === null || $time_to === null) {
        return '0';
    }
    $fromDateTime = Carbon::createFromFormat('h:i A', $time_from);
    $toDateTime = Carbon::createFromFormat('h:i A', $time_to);

    return $toDateTime->diffInHours($fromDateTime);
}

function calculateAge($birthdate): int
{
    $birthDate = Carbon::parse($birthdate);
    $currentDate = Carbon::now();

    return $currentDate->diffInYears($birthDate);
}

function seconds2human(int $ss): string
{
    $s = $ss % 60;
    $m = floor(($ss % 3600) / 60);
    $h = floor(($ss % 86400) / 3600);
    $d = floor(($ss % 2592000) / 86400);
    $M = floor($ss / 2592000);

    return " $m : $h : $d    يوم   ";
}

function countDaysBetween($startDate, $endDate): int
{
    $start = Carbon::parse($startDate);
    $end = Carbon::parse($endDate);

    return $start->diffInDays($end) + 1;
}
