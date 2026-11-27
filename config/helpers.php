<?php
/**
 * Helper functions untuk aplikasi Manajemen Surat UNMA
 */

if (!function_exists('indoDate')) {
    /**
     * Mengubah format tanggal Y-m-d menjadi format teks Indonesia (contoh: 27 November 2026)
     *
     * @param string $date format Y-m-d
     * @return string
     */
    function indoDate($date) {
        if (empty($date) || $date == '0000-00-00') {
            return '-';
        }
        $bulan = array(
            1 => 'Januari', 'Februari', 'Maret', 'April',
            'Mei', 'Juni', 'Juli', 'Agustus',
            'September', 'Oktober', 'November', 'Desember'
        );
        $pecah = explode('-', $date);
        if (count($pecah) !== 3) {
            return $date;
        }
        return (int)$pecah[2] . ' ' . $bulan[(int)$pecah[1]] . ' ' . $pecah[0];
    }
}
