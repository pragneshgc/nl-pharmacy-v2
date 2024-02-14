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
        //DB::statement("CREATE VIEW `view_shipped` AS select `prs`.`PrescriptionID` AS `prs_id`,`prs`.`ReferenceNumber` AS `prs_ref`,`cln`.`TradingName` AS `cln_name`,date_format(from_unixtime(`prs`.`CreatedDate`),'%a %d %b %y %H:%i') AS `prs_date`,`prd`.`Description` AS `prd_desc`,replace(`prs`.`Status`,'8','SHIPPED') AS `prs_status`,(`cor`.`Correspondence` is not null) AS `cor_exists` from (((`natcoldev`.`prescription` `prs` left join `natcoldev`.`product` `prd` on((`prd`.`PrescriptionID` = `prs`.`PrescriptionID`))) left join `natcoldev`.`correspondence` `cor` on((`cor`.`PrescriptionID` = `prs`.`PrescriptionID`))) left join `natcoldev`.`client` `cln` on((`cln`.`ClientID` = `prs`.`ClientID`))) where ((`prs`.`Status` = 8) and (cast(from_unixtime(`prs`.`CreatedDate`) as date) = curdate()))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //DB::statement("DROP VIEW IF EXISTS `view_shipped`");
    }
};