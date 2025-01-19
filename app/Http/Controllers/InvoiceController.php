<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\ProductInvoice;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    function invoiceCreate(Request $request)
    {
        DB::beginTransaction();
        try {

            $user_id = $request->header('id');
            $customer_id = $request->input('customer_id');
            $payable = $request->input('payable');
            $discount = $request->input('discount');
            $vat = $request->input('vat');
            $total = $request->input('total');

            $invoice = Invoice::create([
                'user_id' => $user_id,
                'customer_id' => $customer_id,
                'discount' => $discount,
                'vat' => $vat,
                'payable' => $payable,
                'total' => $total,
            ]);


            $invoiceID = $invoice->id;
            $products = $request->input('products');

            foreach ($products as $EachProduct) {
                ProductInvoice::create([
                    'invoice_id' => $invoiceID,
                    'user_id' => $user_id,
                    'product_id' => $EachProduct['product_id'],
                    'qty' => $EachProduct['qty'],
                    'sale_price' => $EachProduct['sale_price'],
                ]);
            }
            DB::commit();
            return response()->json(['message' => 'Invoice created successfully'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Invoice creation failed'], 500);
        }
    }
    function invoiceShow(Request $request)
    {
        $user_id = $request->header('id');
        return Invoice::where('user_id', $user_id)->get();
    }

    function invoiceDetails(Request $request)
    {
        $user_id = $request->header('id');
        $customerDetails = Customer::where('user_id', $user_id)->where('id', $request->input('cus_id'))->first();
        $invoiceTotal = Invoice::where('user_id', '=', $user_id)->where('id', $request->input('inv_id'))->first();
        $invoiceProduct = ProductInvoice::where('invoice_id', $request->input('inv_id'))
            ->where('user_id', $user_id)->with('product')
            ->get();
        return array(
            'customer' => $customerDetails,
            'invoice' => $invoiceTotal,
            'product' => $invoiceProduct,
        );
    }

    function invoiceDestroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            ProductInvoice::where('invoice_id', $request->input('inv_id'))
                ->where('user_id', $user_id)
                ->delete();

            Invoice::where('id', $request->input('inv_id'))->delete();

            DB::commit();
            return response()->json(['message' => 'Invoice deleted successfully'], 200);


        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Invoice deletion failed'], 500);
        }
    }
}
