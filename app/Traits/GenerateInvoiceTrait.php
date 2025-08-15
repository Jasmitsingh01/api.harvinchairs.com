<?php
namespace App\Traits;

use App\Models\Order;
use App\Traits\TranslationTrait;
use App\Database\Models\Settings;
use niklasravnsborg\LaravelPdf\Facades\Pdf as PDF;

trait GenerateInvoiceTrait
{
    use TranslationTrait;
    public function generateInvoice($order_id)
    {
        if(!file_exists(public_path('storage/pdfs/'.$order_id.'.pdf'))){
            //return response()->download(public_path('invoices/'.$order_id.'.pdf'));

            //find the order using order id
            $order = Order::find($order_id);
            $payloads = [
                'order_id'        => $order->id,
                'language'        => config('shop.default_language'),
                'translated_text' => $this->formatInvoiceTranslateText([]),
                'is_rtl'          => false,
            ];
            $settings = Settings::getData($payloads['language']);
            $invoiceData = [
                'order'           => $order,
                'settings'        => $settings,
                'translated_text' => $payloads['translated_text'],
                'is_rtl'          => $payloads['is_rtl'],
                'language'        => $payloads['language'],
            ];

            $pdf = PDF::loadView('pdf.order-invoice', $invoiceData);
            $filename = 'invoice-order-' . $payloads['order_id'] . '.pdf';
            $pdf->save(public_path('storage/pdfs/' . $filename));
        }
        return ['filepath' => public_path('storage/pdfs/'.$filename),'filelink' => asset('storage/pdfs/'.$filename)];
    }
}
?>
