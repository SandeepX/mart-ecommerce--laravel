<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            font-size: 24px;
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
        .intro-table td{
            border:none;
            padding: 0;
        }
    </style>
</head>
<body>

@foreach($fullOrderDetail['store_order_details'] as $storeOrderDetails)
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
                    <td>
                        <p>Vat No. 609762431</p>
                        <p>Name: Chain Store Hatiya, Hetauda Hatiya, Hetauda</p>
                        <p>Address: Hatiya, Hetauda Hatiya, Hetauda Hatiya, Hetauda</p>
                        <p>Customer's PAN No: 614120523</p>
                    </td>
                    <td align="right">
                        <p>Contact No: 984003071</p>
                        <p>Date of bill issue: 2077/08/12</p>
                        <p>Transaction Date: 2077/08/08</p>
                    </td>
                </tr>
            </table>
        </div>
        <div class="invoice">
            <div>Invoice: 001</div>
        </div>
        <div class="payment-method">
            Payment Method: {{$fullOrderDetail['payment_method']}}
        </div>
        <div class="bill-table">
            <table>
                <thead>
                <tr>
                    <th style="text-align: center;">S.N.</th>
                    <th style=" text-align: center;">Particulars</th>
                    <th>Qty.</th>
                    <th>Rate</th>
                    <th>Amount (Rs.)</th>
                </tr>
                </thead>
                <tbody>

                @php($grandTotal=0)
                @foreach($storeOrderDetails as $storeOrderDetail)
                    @php($grandTotal = $storeOrderDetail['grand_total'])
                    <tr>
                        <td align="center">{{$loop->iteration}}</td>
                        <td>{{$storeOrderDetail['product_name']}}</td>
                        <td>{{$storeOrderDetail['quantity']}}</td>
                        <td>{{$storeOrderDetail['unit_rate']}}</td>
                        <td>{{$storeOrderDetail['amount']}}</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="2" rowspan="5">
                        <span style="font-weight: bold;">In words:</span>
                        Eighty six thousand nine hundred fifty eight and sixty paisa only.
                    </td>
                    <td colspan="2" align="right">
                        Sub Total:
                    </td>
                    <td>{{$fullOrderDetail['sub_total']}}</td>
                </tr>
                <tr>
                    <td colspan="2" align="right">
                        Discount:
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2" align="right">
                        Taxable Amount:
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2" align="right">
                        % VAT:
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2" align="right">
                        Grand Total:
                    </td>
                    <td>{{$grandTotal}}</td>
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