<?php

namespace App\Jobs;

use App\Mail\ImportProductCompleteMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BulkProductImport;
use App\Models\ImportFileDetail;

class ImportProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $filename;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $filepath = public_path().'/storage/importfile/'.$this->filename;
        ImportFileDetail::where('import_filename',$this->filename)->update(['status'=>'processing']);
        try{
            Excel::import(new BulkProductImport, $filepath);
            ImportFileDetail::where('import_filename',$this->filename)->update(['status'=>'completed']);
            //send mail
            $details = [
                'name' => 'Admin',
                'url' => config('app.url').'admin/products'
            ];
            //Mail::to(config('shop.admin_email'))->send(new ImportProductCompleteMail($details));
        } catch(\Exception $e){
            ImportFileDetail::where('import_filename',$this->filename)->update([
                'status'=>'failed',
                'error_message' => $e->getMessage()
            ]);
        }
    }
}
