<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tables extends Model
{
    protected $table = 'tables';

    protected $primaryKey = 'id_table';

    protected $fillable = [
        'table_name',
        'is_active',
        'http_relay',
    ];

    public function getTableAvailable($id_order)
    {
        return DB::table('tables')
            ->where('is_active', 1)
            ->whereNotIn('id_table', $this->getTableInOrder($id_order))
            ->whereNotIn('id_table', $this->getTableInTableActive())
            ->get();
    }

    private function getTableInOrder($id_order)
    {
        return DB::table('order_items')
            ->select('id_table')
            ->where('is_table', 1)
            ->where('id_order', $id_order);
    }

    private function getTableInTableActive()
    {
        return DB::table('table_active')
            ->select('id_table');
    }

    public function getAllTableWithStatus()
    {
        return DB::table('tables', 't')
            ->select(
                't.id_table',
                't.table_name',
                't.is_active as table_can_use',
                'a.id_table_active',
                'a.id_order',
                'a.is_active as table_session_status',
                'a.is_openbill',
                'a.time_start',
                'a.time_limit',
            )
            ->selectRaw('ADDTIME(a.time_start,a.time_limit) as time_end')
            ->selectRaw('NOW() as time_now')
            ->leftJoin('table_active as a', 'a.id_table', '=', 't.id_table')
            ->orderBy('t.id_table')
            ->get();
    }
}
