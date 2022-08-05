<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill</title>
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
<div class="bill">
    <div class="intro">
        <div>Tax Invoice</div>
        <div class="company-title">ALL PASAL PUBLIC LIMITED</div>
        <div>New Baneshwor-10, Nepal</div>
        <div>Warehouse, Budhanilkantha-10, Kathmandu</div>
    </div>
    <div class="intro-body">
        <table class="intro-table">
            <tr style=" vertical-align: top;">
                <td><p>Vendor Name: Pratik Pokharel</p>
                    <p>Vendor Code: 1234</p>
                    <p>VAT/PAN: 54968493</p>
                    <p>Contact No: 9840030271</p>
                    <p>Address: Hatiya, Hetauda</p></td>
                <td align="right"><p>Allpasal Public Limited</p>
                    <p>0013</p>
                    <p>095516452</p>
                    <p>9857071023</p>
                    <p>Kapan, Kathmandu</p></td>
            </tr>
        </table>
    </div>
    <div class="bill-table">
        <table>
            <thead>
            <tr>
                <th style="text-align: center;">S.N.</th>
                <th style="text-align: center;">Particulars</th>
                <th>Tax/Non</th>
                <th>Qty.</th>
                <th>Rate</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            <tr class=" vertical-align: top;">
                <td align="center">1.</td>
                <td>Maria Anders</td>
                <td>23</td>
                <td>25</td>
                <td>12654</td>
                <td>2452.00</td>
            </tr>
            <tr>
                <td align="center">2.</td>
                <td>Roland Mendel</td>
                <td>23</td>
                <td>166</td>
                <td>2354</td>
                <td>1124.45</td>
            </tr>
            <tr>
                <td align="center">3.</td>
                <td>Helen Bennett</td>
                <td>23</td>
                <td>7</td>
                <td>2465</td>
                <td>91224.87</td>
            </tr>
            <tr>
                <td align="center">4.</td>
                <td>Yoshi Tannamuri</td>
                <td>23</td>
                <td>42</td>
                <td>3235322</td>
                <td>9612.45</td>
            </tr>
            <tr>
                <td colspan="3" rowspan="3"><span style="font-weight: bold; ">In words:</span> Eighty six thousand nine
                    hundred fifty eight and sixty paisa only.
                </td>
                <td colspan="2" align="right"> Amount:</td>
                <td>942154.26</td>
            </tr>
            <tr>
                <td colspan="2" align="right"> % VAT:</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2" align="right"> Total:</td>
                <td>942154.26</td>
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
</body>
</html>