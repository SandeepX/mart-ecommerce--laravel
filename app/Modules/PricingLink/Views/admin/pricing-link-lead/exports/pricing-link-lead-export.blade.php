<table>
    <thead>
    <tr>
        <th><b>S.N</b></th>
        <th><b>Full Name</b></th>
        <th><b>Mobile Number</b></th>
        <th><b>Location</b></th>
        <th><b>Pricing Master Code</b></th>
        <th><b>Is Verified</b></th>
        <th><b>Created At</b></th>
    </tr>
    </thead>
    <tbody>
    <?php $n = 1; ?>
    @foreach($pricingLinkLeads as $pricingLinkLead)
        <tr>
            <td>{{$n++}}</td>
            <td>
                {{ $pricingLinkLead->full_name }}
            </td>
            <td>
                {{$pricingLinkLead->mobile_number}}
            </td>
            <td>
                {{$pricingLinkLead->getFullLocationPath()}}
            </td>
            <td>
                {{$pricingLinkLead->pricing_master_code}}
            </td>
            <td>
                @if($pricingLinkLead->is_verified == 1)
                    <span style='font-size:20px;'>&#10004;</span>
                @elseif($pricingLinkLead->is_verified == 0)
                    <span style='font-size:20px;'>&#10006;</span>
                @endif
            </td>
            <td>
                {{getReadableDate(getNepTimeZoneDateTime($pricingLinkLead->created_at),'Y-M-d')}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
