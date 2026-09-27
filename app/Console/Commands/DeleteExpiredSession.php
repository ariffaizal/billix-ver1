<?php

namespace App\Console\Commands;

use App\Models\Tables;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpMqtt\Client\Facades\MQTT;

class DeleteExpiredSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:expired-session';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Expired Session Table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tableActive = DB::table('table_active')
            ->where('is_openbill', 0)
            ->where('is_active', 1)
            ->whereNotNull('time_start')
            ->whereNotNull('time_limit')
            ->whereRaw('NOW() > ADDTIME(time_start, time_limit)');

        $getTables = Tables::whereIn('id_table', $tableActive->pluck('id_table'))->get();
        foreach ($getTables as $t) {
            MQTT::publish('command', $t->http_relay.'?state=off');
        }

        DB::transaction(function () use ($tableActive) {

            $getIdOrder = $tableActive->groupBy('id_order')->select('id_order');
            DB::table('orders')->whereIn('id_order', $getIdOrder)->update(['order_status' => 9]);
            $tableActive->delete();
        });

        $getOrdersActive = DB::table('orders')
            ->where('order_status', 3)
            ->get();
        foreach ($getOrdersActive as $o) {
            $countActive = DB::table('table_active')
                ->where('id_order', $o->id_order)
                ->count('id_order');
            if ($countActive == 0) {
                DB::table('orders')
                    ->where('id_order', $o->id_order)
                    ->update(['order_status' => 9]);
            }
        }

        $this->info('Expired session deleted successfully.');
    }
}
