<?php

if ( ! function_exists('indonesian_format'))
{
    function indonesian_format($date)
    {
        // fungsi atau method untuk mengubah tanggal ke format indonesia
        // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
        $indonesian_month = array(
            "Januari", "Februari", "Maret",
            "April", "Mei", "Juni",
            "Juli", "Agustus", "September",
            "Oktober", "November", "Desember"
        );
        $year        = substr($date, 0, 4); // memisahkan format tahun 
        $month       = substr($date, 5, 2); // memisahkan format bulan
        $currentdate = substr($date, 8, 2); // memisahkan format tanggal
        $result = $currentdate . " " . $indonesian_month[(int) $month - 1] . " " . $year;
        return $result;
    }
}

?> 