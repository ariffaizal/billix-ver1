<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GenerateModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:generate-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Model dari Database';

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function getPK($table)
    {
        $pk = '';
        $indexes = Schema::getIndexes($table);
        for ($i = 0; $i < count($indexes); $i++) {
            if ($indexes[$i]['name'] == 'primary') {
                $pk = $indexes[$i]['columns'][0];
                break;
            }
        }

        return $pk;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tables = Schema::getTables();
        for ($i = 0; $i < count($tables); $i++) {
            $tableName = $tables[$i]['name'];
            $name = Str::studly($tableName);
            $pk = $this->getPK($tableName);
            $columns = Schema::getColumns($tableName);
            $columnsList = '';

            for ($j = 0; $j < count($columns); $j++) {
                //kondisi jika nama kolom sebagai primary key, dan nama kolom adalah created_at dan update maka tidak akan dimasukan kedalam $columnsList
                if ($columns[$j]['name'] != $pk && $columns[$j]['name'] != 'created_at' && $columns[$j]['name'] != 'updated_at') {
                    $columnsList .= "'".$columns[$j]['name']."',";
                }
            }

            $path = app_path('Models/'.$name.'.php');
            if ($this->files->exists($path)) {
                $this->error('Model sudah ada!');

                return;
            }

            $stub = $this->files->get(__DIR__.'/stubs/generatemodel.stub');
            $stub = str_replace('{{ namespace }}', 'App\Models', $stub);
            $stub = str_replace('{{ class }}', $name, $stub);
            $stub = str_replace('{{ nama_tabel }}', $tableName, $stub);
            $stub = str_replace('{{ primary_key }}', $pk, $stub);
            $stub = str_replace('{{ list_kolom }}', $columnsList, $stub);
            $this->files->put($path, $stub);
            $this->info('Model berhasil dibuat!.');
        }
    }
}
