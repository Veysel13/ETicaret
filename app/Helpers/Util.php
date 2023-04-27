<?php

use \App\Constants\AuthorityType;

function imageUrl($image,$type='image')
{
    if ($image !== '' && $image !== null) {
        return \Storage::disk('uploads')->url($image);
    }

    if ($type=='image'){
        return 'http://via.placeholder.com/400x400?text=UrunResmi';
    }

    return null;
}

function priceFormat($number)
{
    return \App\Model\Config\Currency::format($number);
}

function priceFormat2($number)
{
    $price = \App\Model\Config\Currency::format($number, 2, ',', '.', '', ' ₺');
    return str_ends_with($price, ',00 ₺') ? str_replace(',00 ₺', ' ₺', $price) : $price;
}

function priceFormat3($number)
{
    return \App\Model\Config\Currency::format($number, 0, ',', '.', '', ' ₺');
}


function dateFormat($date, $format = 'm-d-Y')
{

    $dateFormat = \Carbon\Carbon::createFromFormat($format, $date)->format('Y-m-d');

    return $dateFormat;
}

function changeDateFormat($date, $format = 'm-d-Y')
{

    $dateFormat = \Carbon\Carbon::parse($date)->format($format);

    return $dateFormat;
}

function stringLimit($description, $limit = 25)
{
    return \Illuminate\Support\Str::limit($description, $limit);
}

function getIp()
{
    if (isset($_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else {
        $ip = $_SERVER["REMOTE_ADDR"];
    }
    return $ip;
}
