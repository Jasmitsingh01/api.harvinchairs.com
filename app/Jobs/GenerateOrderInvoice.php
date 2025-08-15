<?php

namespace App\Jobs;

use App\Database\Models\Settings;
use App\Traits\TranslationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use niklasravnsborg\LaravelPdf\Facades\Pdf as PDF;

class GenerateOrderInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use TranslationTrait;
    protected $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payloads = [
            'order_id'        => $this->order->id,
            'language'        => config('shop.default_language'),
            'translated_text' => $this->formatInvoiceTranslateText([]),
            'is_rtl'          => false,
        ];
        $settings = Settings::getData($payloads['language']);
        $invoiceData = [
            'order'           => $this->order,
            'settings'        => $settings,
            'translated_text' => $payloads['translated_text'],
            'is_rtl'          => $payloads['is_rtl'],
            'language'        => $payloads['language'],
        ];


        $pdf = PDF::loadView('pdf.order-invoice', $invoiceData);
        $filename = 'invoice-order-' . $payloads['order_id'] . '.pdf';

        // Save the PDF to a folder
        //$pdf->save(storage_path('app/public/pdfs/' . $filename));
        $pdf->save(public_path('storage/pdfs/' . $filename));
    }
}
