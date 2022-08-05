@if(isset($stockTransferMeta))
    <tr>
        <td>{{ $stockTransferMeta->key }}</td>
        <td>
            <?php $value = explode('.', $stockTransferMeta->value); ?>
            @if(isset($value[1]))
                @if(in_array($value[1], ['jpeg', 'jpg', 'png']))
                    <img src="{{photoToUrl($stockTransferMeta->value, asset('uploads/warehouse-stock-transfer/delivery-detail'))}}" alt="delivery image" height="100px" width="150px">
                @elseif(in_array($value[1], ['txt', 'pdf', 'doc', 'docx']))
                    <a href="{{photoToUrl($stockTransferMeta->value, asset('uploads/warehouse-stock-transfer/delivery-detail'))}}" target="_blank">View file</a>
                @endif
            @else
                {{ Str::ucfirst($stockTransferMeta->value) }}
            @endif
        </td>
    </tr>
@endif
