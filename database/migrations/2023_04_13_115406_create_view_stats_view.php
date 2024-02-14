<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //DB::statement("CREATE VIEW `view_stats` AS select ifnull(sum(if((`natcoldev`.`prescription`.`Status` = 1),1,0)),0) AS `c1`,ifnull(sum(if((`natcoldev`.`prescription`.`Status` = 2),1,0)),0) AS `c2`,ifnull(sum(if((`natcoldev`.`prescription`.`Status` = 4),1,0)),0) AS `c4`,ifnull(sum(if(((`natcoldev`.`prescription`.`Status` = 7) and (`natcoldev`.`prescription`.`DeliveryID` = 4)),1,0)),0) AS `c7dpd`,ifnull(sum(if(((`natcoldev`.`prescription`.`Status` = 7) and (`natcoldev`.`prescription`.`DeliveryID` = 7)),1,0)),0) AS `c7ups`,ifnull(sum(if((`natcoldev`.`prescription`.`Status` = 9),1,0)),0) AS `c9`,ifnull(sum(if((`natcoldev`.`prescription`.`Status` = 10),1,0)),0) AS `c10`,ifnull(sum(if((`natcoldev`.`prescription`.`Status` = 11),1,0)),0) AS `c11`,ifnull(sum(if((`natcoldev`.`prescription`.`Status` = 7),1,0)),0) AS `c12` from `natcoldev`.`prescription` where (cast(from_unixtime(`natcoldev`.`prescription`.`CreatedDate`) as date) <= curdate())");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //DB::statement("DROP VIEW IF EXISTS `view_stats`");
    }
};