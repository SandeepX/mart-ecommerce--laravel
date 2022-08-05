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
                <div class="bill" {{$key>1 ? "style=page-break-before:always;" : ""}}>
                    <div class="intro">
                        <div class="company-title">ALL PASAL PUBLIC LIMITED</div>
                        <div class="company-address">New Baneshwor-10, Nepal</div>
                        <div class="company-address">Warehouse, {{$billHeaderDetail['warehouse_name']}}</div>
                    </div>
                    <div class="intro-body">
                        <table class="intro-table">
                            <tr style=" vertical-align: top;">
                                <td>
                                    <p>Vat No. 609762431</p>
                                    <p>Store Name: {{$billHeaderDetail['store_name']}}</p>
                                    <p>Customer's {{$billHeaderDetail['store_vat_pan_type']}} No. : {{$billHeaderDetail['store_vat_pan']}}</p>
                                    <p>Address: {{$billHeaderDetail['store_address']}}</p>
                                </td>
                                <td align="right">
                                    <p>Contact: {{$billHeaderDetail['store_contact_num']}}</p>
                                    <p>Transaction Date: {{$billHeaderDetail['transaction_date']}}</p>
                                    <p>Date of bill issue: </p>
                                    <p>Invoice No : {{substr($billHeaderDetail['invoice_num'],2)}}</p>

                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="bill-table">
                        <table>
                            <thead>
                            <tr>
                                <th width="2%" class="table-head">S.N.</th>
                                <th width="56%" class="table-head">Particulars</th>
                                <th width="5%" class="table-head">Qty.</th>
                                <th width="10%" class="table-head">Unit</th>
                                <th width="12%" class="table-head">Rate</th>
                                <th width="15%" class="table-head">Amt (Rs.)</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($fullOrderDetails['store_order_details'] as $fullOrderDetail)
                                <tr>
                                    <td width="2%">{{$loop->iteration}}</td>
                                    <td width="56%">
                                        {{$fullOrderDetail['product_name']}}

                                        @if(isset($fullOrderDetail['product_variant_name'])
                                         && !empty($fullOrderDetail['product_variant_name'])
                                         )
                                            <span>({{$fullOrderDetail['product_variant_name']}})</span>
                                        @endif
                                    </td>
                                    <td width="5%">{{$fullOrderDetail['quantity']}}</td>
                                    <td width="10%">Bori</td>
                                    <td width="12%">{{$fullOrderDetail['unit_rate']}}</td>
                                    <td width="15%">{{$fullOrderDetail['amount']}}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <td colspan="3" rowspan="4">
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

</body>
</html>
