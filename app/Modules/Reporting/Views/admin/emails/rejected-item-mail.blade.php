
<head>
    <title></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
        #outlook a {
            padding: 0;
        }

        .ReadMsgBody {
            width: 100%;
        }

        .ExternalClass {
            width: 100%;
        }

        .ExternalClass * {
            line-height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        p {
            display: block;
            margin: 13px 0;
        }
    </style>

</head>

<body style="background: #F9F9F9;">

<div style="background-color:#F9F9F9;">
    <style type="text/css">
        html,
        body,
        * {
            -webkit-text-size-adjust: none;
            text-size-adjust: none;
        }

        a {
            color: #1EB0F4;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        p {
            margin-left: 10px;
        }
    </style>
    <div style="margin:0px auto;max-width:640px;background:transparent;">
        <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:transparent;" align="center" border="0">
            <tbody>
            <tr>
                <div class="image" align="center">
                    <img src="{{ asset('default/images/alplogo.png') }}" alt="company_logo">
                </div>
                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;">
                    <div aria-labelledby="mj-column-per-100" class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                            <tbody>
                            <tr>
                                <td style="word-break:break-word;font-size:0px;padding:0px;" align="center">
                                    <div style="color:#0000FF;cursor:auto;font-size:16px;line-height:24px;text-align:center;">
                                       <h3>Rejected Item Sync detatil started on: {{$sync_started_at}} {{($sync_status == 'success')? 'and ended on:'.$sync_ended_at : '' }}</h3>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div style="margin:0px auto;max-width:640px;background:transparent;">
        <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:transparent;" align="center" border="0">
            <tbody>
            <div class="row">
                <p ><strong>Status:</strong> {{$sync_status}}</p>
                @if($sync_status == 'success')
                    <p><strong>Order Type:</strong> {{$order_type}}</p>
                    <p><strong>Synced orders count:</strong> {{$synced_orders_count}} </p>
{{--                    <p><strong>Synced Remark:</strong> {{ $sync_remarks }}</p>--}}
                @else
                    <p>Error: {{ $sync_remarks }}</p>
                @endif
            </div>
            </tbody>
        </table>
    </div>

    <div style="margin:0px auto;max-width:640px;background:transparent;">
        <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:transparent;" align="center" border="0">
            <tbody>
            <tr>
                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;">
                    <div aria-labelledby="mj-column-per-100" class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                            <tbody>
                            <tr>
                                <td style="word-break:break-word;font-size:0px;padding:0px;" align="center">
                                    <div style="cursor:auto;color:#0000FF;font-family:Whitney, Helvetica Neue, Helvetica, Arial, Lucida Grande, sans-serif;font-size:12px;line-height:24px;text-align:center;">
                                        Copyright © {{ now()->year }} Allpasal LLC., All rights reserved.

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="word-break:break-word;font-size:0px;padding:0px;" align="center">
                                    <div style="cursor:auto;color:#0000FF;font-family:Whitney, Helvetica Neue, Helvetica, Arial, Lucida Grande, sans-serif;font-size:12px;line-height:24px;text-align:center;">
                                      Kapan, Kathmandu, KTM 44600
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>



</body>


