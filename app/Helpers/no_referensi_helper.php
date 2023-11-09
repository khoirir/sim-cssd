<?php
if (!function_exists('generateNoReferensi')) {
    function generateNoReferensi($tanggal, $id)
    {
        $noReferensi = date("ymdHis", strtotime($tanggal)) . substr($id, 0, 8);

        return strtoupper($noReferensi);
    }
}
