<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Store Order Bill</title>
    <style>
        .bill {
            width: 600px;
            margin: auto;
            margin-top: 20px;
        }

        .intro {
            text-align: center;
            font-weight: bold;
        }

        .company-title {
            font-size: 18px;
        }

        .intro-body {
            margin-top: 12px;
            position: relative;
        }

        .invoice {
            text-align: right;
        }

        .payment-method {
            margin-top: 12px;
        }

        .bill-table {
            margin-top: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            /*padding: 8px;*/
            padding: 6px;
            text-align: left;
        }

        td, th {
            border: 1px solid black;
        }

        td {
            padding: 8px;
            /*padding: 6px;*/
            font-size: 12px;
        }

        .bill-footer {
            font-size: 14px;
            margin-top: 28px;
        }
        .intro-table td{
            border:none;
            padding: 0;
        }
        .table-head{
            font-size: 10px;
        }
        .company-address{
            font-size: 14px;
        }
    </style>
</head>
<body>

@foreach($fullOrderDetailsWithChunk as $key=>$fullOrderDetails)
    @if($loop->last && $loop->first!=$loop->last)
        <div class="bill" style="page-break-before: always;">
            @endif
    @foreach($fullOrderDetails as $item=>$fullOrderDetail)
                    <div class="bill" {{$item>0 ? "style=page-break-before:always;" : ""}}>
                        <div class="intro">
                            {{--            <div>Tax Invoice</div>--}}
                            <div class="company-title">ALL PASAL PUBLIC LIMITED</div>
                            <div class="company-address">New Baneshwor-10, Nepal</div>
                            <div class="company-address">Warehouse, {{$fullOrderDetail['warehouse_name']}}</div>
                        </div>
                        <div class="intro-body">
                            <table class="intro-table">
                                <tr style=" vertical-align: top;">
                                    <td>
                                        <p>Vat No. 609762431</p>
                                        <p>Store Name: {{$fullOrderDetail['store_name']}}</p>
                                        {{--                        <p>Store Code: {{$fullOrderDetail['store_code']}}</p>--}}
                                        <p>Customer's {{$fullOrderDetail['store_vat_pan_type']}} No. : {{$fullOrderDetail['store_vat_pan']}}</p>
                                        {{--                        <p>Customer VAT/PAN : {{$fullOrderDetail['warehouse_vat_pan']}}--}}
                                        {{--                        <p>Contact: {{$fullOrderDetail['store_contact_num']}}</p>--}}
                                        <p>Address: {{$fullOrderDetail['store_address']}}</p>
                                        {{-- <p>Customer's PAN No: 614120523</p>--}}
                                    </td>
                                    <td align="right">
                                        <p>Contact: {{$fullOrderDetail['store_contact_num']}}</p>
                                        {{--                        <p>Warehouse Name : {{$fullOrderDetail['warehouse_name']}}</p>--}}
                                        {{--                        <p>Warehouse Code : {{$fullOrderDetail['warehouse_code']}}</p>--}}
                                        {{--                        <p>VAT/PAN : {{$fullOrderDetail['warehouse_vat_pan']}}</p>--}}
                                        {{--                        <p> {{$fullOrderDetail['warehouse_contact_num']}}</p>--}}
                                        {{--                        --}}
                                        <p>Transaction Date: {{$fullOrderDetail['transaction_date']}}</p>
                                        <p>Date of bill issue: </p>
                                        {{--                        <p>Address : {{$fullOrderDetail['warehouse_address']}}</p>--}}
                                        <p>Invoice No : {{substr($fullOrderDetail['invoice_num'],2)}}</p>

                                    </td>
                                </tr>
                            </table>
                        </div>
                        {{--        <div class="invoice">--}}
                        {{--            <div>Invoice: {{$fullOrderDetail['invoice_num']}}</div>--}}
                        {{--        </div>--}}
                        {{--        <div class="payment-method">--}}
                        {{--            Payment Method: {{$fullOrderDetail['payment_method']}}--}}
                        {{--        </div>--}}
                        <div class="bill-table">
                            <table>
                                <thead>
                                <tr>
                                    <th width="2%" class="table-head">S.N.</th>
                                    <th width="56%" class="table-head">Particulars</th>
                                    <th width="10%" class="table-head">Unit</th>
                                    <th width="5%" class="table-head">Qty.</th>
                                   {{-- <th width="10%" class="table-head">Unit</th>--}}
                                    <th width="12%" class="table-head">Rate</th>
                                    <th width="15%" class="table-head">Amt (Rs.)</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($fullOrderDetail['store_order_details'] as $storeOrderDetail)
                                    <tr>
                                        <td width="2%">{{$loop->iteration}}</td>
                                        <td width="56%">
                                            {{$storeOrderDetail['product_name']}}

                                            @if(isset($storeOrderDetail['product_variant_name'])
                                             && !empty($storeOrderDetail['product_variant_name'])
                                             )
                                                <span>({{$storeOrderDetail['product_variant_name']}})</span>
                                            @endif
                                        </td>
                                        <td width="10%">{{$storeOrderDetail['ordered_package_name'] ??$storeOrderDetail['old_package_name']}}</td>
                                        <td width="5%">{{$storeOrderDetail['quantity']}}</td>
                                        {{--<td width="10%">{{$storeOrderDetail['unit']}}</td>--}}
                                        <td width="12%">{{$storeOrderDetail['unit_rate']}}</td>
                                        <td width="15%">{{$storeOrderDetail['amount']}}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="3" rowspan="5">
                                        {{currencyInWords($fullOrderDetail['grand_total'])}}
                                    </td>
                                    <td colspan="2" align="right">
                                        Sub Total:
                                    </td>
                                    <td>{{$fullOrderDetail['sub_total']}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">
                                        Tax Amount ({{$fullOrderDetail['vat']}}):
                                    </td>
                                    <td>{{$fullOrderDetail['taxable_amount']}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">
                                        Discount:
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">
                                        Total Quantity:
                                    </td>
                                    <td>{{$fullOrderDetail['total_qty']}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">
                                        Grand Total:
                                    </td>
                                    <td>{{$fullOrderDetail['grand_total']}}</td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                        <div class="bill-footer">
                            <div style="text-align: left; float: left; width: 30%">
                                <div>_____________</div>
                                <div style="margin-top: 8px;">Customer's Sign</div>
                            </div>
                            <div style="text-align: center; float: left; width: 30%">
                                <div>____________</div>
                                <div style="margin-top: 8px;">Prepared By</div>
                            </div>
                            <div style="text-align: right; float: left; width: 40%;">
                                <div>___________________________</div>
                                <div style="margin-top: 8px;">ALL PASAL PUBLIC LIMITED</div>
                            </div>
                        </div>
                    </div>
            @endforeach
            @endforeach

</body>
</html>
