<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Purchase Order Bill</title>
    <style>
        .bill {
            width: 600px;
            margin:auto;
            margin-top: 20px;
        }

        .intro {
            text-align: center;
            font-weight: bold;
        }

        .company-title {
            font-size: 24px;
        }

        .intro-body {
            margin-top: 12px;
        }

        .intro-table td {
            border: none;
            padding: 0;
        }

        .bill-table {
            margin-top: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            padding: 8px;
            text-align: left;
        }

        td, th {
            border: 1px solid black;
        }

        td {
            padding: 8px;
        }

        .bill-footer {
            font-size: 14px;
            margin-top: 28px;
        }
    </style>
</head>
<body>
@foreach($fullWarehouseOrderDetails as $fullOrderDetail)
    <div class="bill" {{$loop->index > 0 ? "style=page-break-before:always;" : ""}}>
    <div class="intro">
        <div>Tax Invoice</div>
        <div class="company-title">ALL PASAL PUBLIC LIMITED</div>
        <div>New Baneshwor-10, Nepal</div>
        <div>Warehouse, Budhanilkantha-10, Kathmandu</div>
    </div>
    <div class="intro-body">
        <table class="intro-table">
            <tr style=" vertical-align: top;">
                <td><p>Vendor Name: {{$fullOrderDetail['vendor_name']}}</p>
                    <p>Vendor Code: {{$fullOrderDetail['vendor_code']}}</p>
                    <p>VAT/PAN: {{$fullOrderDetail['vendor_pan_vat']}}</p>
                    <p>Contact No: {{$fullOrderDetail['vendor_contact']}}</p>
                    <p>Address: {{$fullOrderDetail['vendor_address']}}</p></td>
                <td align="right">
                    <p>Warehouse Name : {{$fullOrderDetail['warehouse_name']}}</p>
                    <p>Warehouse Code : {{$fullOrderDetail['warehouse_code']}}</p>
                    <p>VAT/PAN : {{$fullOrderDetail['warehouse_contact_num']}}</p>
                    <p>{{$fullOrderDetail['warehouse_contact_num']}}</p>
{{--                    <p>{{$fullOrderDetail['contact_num']}}</p>--}}

                    <p>Address : {{$fullOrderDetail['warehouse_address']}}</p>
                    <p>Transaction Date: {{$fullOrderDetail['transaction_date']}}</p>
                    <p>Invoice #: {{$fullOrderDetail['invoice_num']}}</p>
                </td>
            </tr>
        </table>
    </div>
    <div class="bill-table">
        <table>
            <thead>
            <tr>
                <th style="text-align: center;">S.N.</th>
                <th style="text-align: center;">Particulars</th>
                <th>Qty.</th>
                <th>Rate</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>

            @foreach($fullOrderDetail['warehouse_order_details'] as $warehouseOrderDetail)
                <tr class=" vertical-align: top;">
                    <td align="center">{{$loop->iteration}}.</td>
                    <td>
                        {{$warehouseOrderDetail['product_name']}}<br>
                        <small>{{$warehouseOrderDetail['product_variant_name']}}</small>
                    </td>
                    <td> {{$warehouseOrderDetail['quantity']}}</td>
                    <td> {{$warehouseOrderDetail['unit_rate']}}</td>
                    <td> {{$warehouseOrderDetail['amount']}}</td>
                </tr>

            @endforeach


            <tr>
                <td colspan="2" rowspan="3"><span style="font-weight: bold; ">In words:</span>
                    {{currencyInWords($fullOrderDetail['grand_total'])}}
                </td>
                <td colspan="2" align="right"> Amount:</td>
                <td>{{$fullOrderDetail['sub_total']}}</td>
            </tr>
            <tr>
                <td colspan="2" align="right"> {{$fullOrderDetail['vat']}} VAT:</td>
                <td>{{$fullOrderDetail['taxable_amount']}}</td>
            </tr>
            <tr>
                <td colspan="2" align="right"> Total:</td>
                <td>{{$fullOrderDetail['grand_total']}}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="bill-footer">
        <div style="text-align: left; float: left; width: 33%">
            <div>__________</div>
            <div style="margin-top: 8px;">Createdy By</div>
        </div>
        <div style="text-align: center; float: left; width: 33%">
            <div>____________</div>
            <div style="margin-top: 8px;">Accounted By</div>
        </div>
        <div style="text-align: right; float: left; width: 33%;">
            <div>__________</div>
            <div style="margin-top: 8px;">Verified By</div>
        </div>
    </div>
</div>
@endforeach
</body>
</html>
