<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Sarmad\Gateway\GatewayManager;
use Sarmad\Gateway\Transaction;

class CreateGatewayTransactionsTable extends Migration
{
    private function getTable()
    {
        return config(GatewayManager::CONFIG_FILE_NAME . '.table', 'gateway_transactions');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTable(), function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->smallInteger('provider');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->nullable();
            $table->string('ref_id', 100)->nullable();
            $table->string('tracking_code', 50)->nullable();
            $table->string('card_number', 50)->nullable();
            $table->smallInteger('status')->default(Transaction::STATE_INIT);
            $table->string('ip', 20)->nullable();
            $table->json('extra')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->getTable());
    }
}
