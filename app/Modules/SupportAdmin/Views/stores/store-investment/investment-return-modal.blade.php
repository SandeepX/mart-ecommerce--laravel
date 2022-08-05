

        <div class="box-body">
            <h4><b>Investment Return At Maturity</b></h4>
            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                <tbody>
                <tr>
                    <td>Invested Amount</td>
                    <td>Rs.{{$subscribedIP->invested_amount}}</td>
                </tr>

                <tr>
                    <td>Principle</td>
                    <td>Rs.{{ isset($investmentReturn['principle']) ? $investmentReturn['principle'] : 0 }}</td>
                </tr>

                <tr>
                    <td>Interest</td>
                    <td>Rs. {{ isset($investmentReturn['interest']) ? $investmentReturn['interest'] : 0 }}</td>
                </tr>

                <tr>
                    <td>Share</td>
                    <td>{{ isset($investmentReturn['share']) ? $investmentReturn['share'] : 0  }}</td>
                </tr>

                </tbody>

            </table>
        </div>
