<?php

if (!function_exists('money')) {
    /**
     * Format a given amount to the given currency
     *
     * @param $amount
     * @param \App\Models\Currency $currency
     * @return string
     */
    function money($amount, \App\Models\Currency $currency)
    {
        return $currency->symbol_left . number_format(
            $amount,
            $currency->decimal_place,
            $currency->decimal_point,
            $currency->thousand_point
        ) . $currency->symbol_right;
    }
}

if (!function_exists('app_token')) {
    function app_token()
    {
        return 'AppToken';
    }
}

if (!function_exists('dateToDb')) {
    function dateToDb($tanggal)
    {
        if ($tanggal == '00-00-0000' or $tanggal == NULL) $tanggal = date('d-m-Y');

        $tgl = explode('-', $tanggal);
        if (strlen($tgl[0]) == 2) {
            $tahun = $tgl[0];
            $bulan = $tgl[1];
            $tggal = $tgl[2];
        } else {
            $tahun = $tgl[2];
            $bulan = $tgl[1];
            $tggal = $tgl[0];
        }

        return $tggal . '-' . $bulan . '-' . $tahun;
    }
}
