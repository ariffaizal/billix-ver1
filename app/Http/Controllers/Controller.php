<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function orderStatus(): array
    {
        return [
            0 => 'Draft',
            1 => 'Pending Payment',
            2 => 'Pending Start',
            3 => 'Session Active',
            4 => 'Session Closed',
            5 => 'Pending Payment', // Open Bill
            7 => 'Refund',
            8 => 'Canceled',
            9 => 'Finish',
        ];
    }

    public function badgeStatus($status_text): array
    {
        return [
            0 => '<span class="badge text-bg-secondary">'.$status_text.'</span>',
            1 => '<span class="badge text-bg-info">'.$status_text.'</span>',
            2 => '<span class="badge text-bg-warning">'.$status_text.'</span>',
            3 => '<span class="badge text-bg-light">'.$status_text.'</span>',
            4 => '<span class="badge text-bg-dark">'.$status_text.'</span>',
            5 => '<span class="badge text-bg-info">'.$status_text.'</span>',
            7 => '<span class="badge text-bg-danger">'.$status_text.'</span>',
            8 => '<span class="badge text-bg-danger">'.$status_text.'</span>',
            9 => '<span class="badge text-bg-success">'.$status_text.'</span>',
        ];
    }
}
