<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirmKycDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firm_kyc_documents', function (Blueprint $table) {
            $table->string('kyc_document_code')->unique()->primary();
            $table->string('kyc_code');

            $table->enum('document_type',[
                'firm_darta_pramaan_patra',
                'prabhanda_patra',
                'niyamaawali',
                'pan_vat_darta',
                'minute'
                ]
            );

            $table->string('document_file');

            $table->timestamps();

            $table->foreign('kyc_code')->references('kyc_code')->on('firm_kyc_master');

        });
        DB::statement('ALTER Table firm_kyc_documents add id BIGINT NOT NULL UNIQUE AUTO_INCREMENT FIRST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firm_kyc_documents');
    }
}
