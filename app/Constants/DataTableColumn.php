<?php

namespace App\Constants;

class DataTableColumn
{
    const SaleOrderPage=[
        'sale_orders.id','receipt_barcode',null,null,'trans_no','order_date','total','received','e_receipt',null
    ];

    const ReceiptProductPage=[
        null,null,null,'product_name','product_upc','product_sku','shade_no','brand_name',null,'total_quantity','product_paid_price','stock'
    ];

    const ReceivedProductPage=[
        'product_name','product_upc','product_sku','shade_no','brand_name','receipt_barcode','order_date','quantity'
    ];

    const StoreReportPage=[
       'store_name','store_state','store_code','total_order','total_price'
    ];

    const UserReportPage=[
       'fullname','total_order','total_price'
    ];

    const MissingProductPage=[
        'product_name',null,'store_name','user_name','quantity','receipt_barcode','order_date','created_at',null
    ];

    const NotReceivedOrder=[
        'receipt_barcode',null,null,'trans_no','order_date','total','received','e_receipt',null
    ];
}
