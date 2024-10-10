<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateGeneralConfigurationTable extends Migration
{
    public function up()
    {
        Schema::create('general_configuration', function (Blueprint $table) {
            $table->id();
            $table->timestamp('start_time')->nullable()->comment('Thời gian bắt đầu');
            $table->timestamp('end_time')->nullable()->comment('Thời gian kết thúc');
            $table->tinyInteger('status')->default(0)->comment('Trạng thái (0: không kích hoạt, 1: kích hoạt)');
            $table->string('configuration_type')->comment('Loại config');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('general_configuration');
    }
}
