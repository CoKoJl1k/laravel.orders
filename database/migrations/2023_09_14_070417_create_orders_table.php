<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sql= "create table laravel_orders.orders
            (
                id         bigint unsigned auto_increment primary key,
                name       varchar(100)                         not null comment 'Имя пользователя',
                email      varchar(100)                         not null comment 'Email пользователя',
                status     enum ('Active', 'Resolved') default 'Active' not null comment 'Статус - enum(“Active”, “Resolved”)',
                message    text                                 not null comment 'Сообщение пользователя',
                comment    text                                 null comment 'Ответ ответственного лица ',
                created_at timestamp default current_timestamp not null,
                updated_at timestamp default current_timestamp not null
            )
                collate = utf8mb4_unicode_ci;
            ";
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
