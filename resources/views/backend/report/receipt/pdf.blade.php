<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Product Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">

    <style>
        *{
            font-family:"DeJaVu Sans Mono",monospace !important;
        }
        body{
            background-color: #fff !important;
        }

        h4{
            font-family:"DeJaVu Sans Mono",monospace !important;
            font-size: 16px;
            font-weight:unset;
        }

        h5{
            font-family:"DeJaVu Sans Mono",monospace !important;
            font-size: 14px;
            font-weight:unset;
        }

        .table-bordered>thead>tr>th {
            font-size:11px !important;
        }

        .table-bordered>tbody>tr>td {
            font-size: 11px !important;
        }

        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 3px;
            line-height: 1.42857143;
            vertical-align: top;
            border: 1px solid #ddd;
        }

        .container{
            width: 100%;
            max-width: 100%;
            padding-left: 0px !important;
            margin-left: -18px !important;
        }

    </style>
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col" style="min-width: 100px">Product Name</th>
                    <th scope="col">Upc</th>
                    <th scope="col">Sku</th>
                    <th scope="col">Shade</th>
                    <th scope="col">Brand</th>
                    <th scope="col">Discount</th>
                    <th scope="col" style="width: 80px">Total Quantity</th>
                    <th scope="col" style="width: 80px">Paid Price</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($products))
                    @foreach($products as $product)
                        <tr>
                            <td>{{$product->product_name}}</td>
                            <td>{{$product->product_upc}}</td>
                            <td>{{$product->product_sku}}</td>
                            <td>{{$product->shade_no}}</td>
                            <td>{{$product->brand_name}}</td>
                            <td>{{$product->discount}}</td>
                            <td>{{$product->total_quantity}}</td>
                            <td>{{$product->product_paid_price}} $</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>

</html>
