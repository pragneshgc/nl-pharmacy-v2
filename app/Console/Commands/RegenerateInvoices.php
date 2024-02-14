<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Library\Invoice;

class RegenerateInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:regenerate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //send a message that invoice generation is starting
        $this->info('Invoice generation started');

        DB::table('Invoice')->delete();
        DB::table('InvoiceItem')->delete();
        
        $prescriptions = DB::table('Prescription')->where('Status', 8)->get();

        foreach ($prescriptions as $prescription) {
            $invoice = new Invoice();
            $invoice->generate($prescription->PrescriptionID);
            //send a message that we are generating an invoice for this prescription
            $this->info('Generating invoice for prescription ' . $prescription->PrescriptionID);
        }
        
        //send a message that invoice generation is complete
        $this->info('Invoice generation complete');
    }
}
