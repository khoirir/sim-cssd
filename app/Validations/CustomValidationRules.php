<?php

namespace App\Validations;

use Config\Database;

class CustomValidationRules
{
    public function not_future_date($value, ?string &$error = null): bool
    {
        if ($value > date("Y-m-d")) {
            $error = 'Tanggal tidak boleh lebih dari tanggal sekarang';
            return false;
        }
        return true;
    }

    public function not_future_time($value, ?string &$error = null): bool
    {
        if ($value > date("Y-m-d H:i")) {
            $error = 'Jam tidak boleh lebih dari jam sekarang';
            return false;
        }
        return true;
    }

    public function is_unique_soft(?string $str, string $field, array $data): bool
    {
        [$field] = array_pad(
            explode(',', $field),
            3,
            null
        );

        sscanf($field, '%[^.].%[^.]', $table, $field);

        $row = Database::connect($data['DBGroup'] ?? null)
            ->table($table)
            ->select('1')
            ->where($field, $str)
            ->where("deleted_at", null)
            ->limit(1);

        return $row->get()->getRow() === null;
    }

    public function is_date_less_than(?string $str, string $field, array $data): bool
    {
        $tanggalPembanding = strlen($str) <= 10 ? explode(" ", $data[$field])[0] : $data[$field];
        if ($str < $tanggalPembanding) {
            return false;
        }

        return true;
    }

    public function is_time_less_than(?string $str, string $field, array $data): bool
    {
        $field = explode(',', $field);
        $jamPembanding = date('Y-m-d H:i', strtotime($data[$field[1]]));
        $jamInput = date('Y-m-d H:i', strtotime($data[$field[0]] . " " . $str));
        if ($jamInput < $jamPembanding) {
            return false;
        }
        return true;
    }

    public function is_time_greater_than(?string $str, string $field, array $data): bool
    {
        $jamPembanding = date('Y-m-d H:i');
        $jamInput = date('Y-m-d H:i', strtotime($data[$field] . " " . $str));
        if ($jamInput > $jamPembanding) {
            return false;
        }
        return true;
    }
}
