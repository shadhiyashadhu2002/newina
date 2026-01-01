<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('expenses')->update([
            'manager' => DB::raw('UPPER(manager)')
        ]);
    }

    public function down()
    {
        DB::table('expenses')->update([
            'manager' => DB::raw('LOWER(manager)')
        ]);
    }
};