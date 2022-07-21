<?php

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault8a059e86a390ae26eb2ae4926b4e613e extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('channels')
            ->addColumn('id', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 255
            ])
            ->addColumn('route', 'string', [
                'nullable' => true,
                'default'  => null,
                'size'     => 255
            ])
            ->addColumn('count', 'integer', [
                'nullable' => false,
                'default'  => null
            ])
            ->setPrimaryKeys(["id"])
            ->create();
    }

    public function down(): void
    {
        $this->table('channels')->drop();
    }
}
