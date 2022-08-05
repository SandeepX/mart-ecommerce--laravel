@if(count($customer_uploads) > 0)

    @foreach($customer_uploads as $customer_upload)
        @php
          //$saved_file = str_replace('public/', '',$customer_upload->filename);
        $saved_file = $customer_upload->filename;
        $ext = pathinfo(url('uploads/customer_uploads/'.$saved_file), PATHINFO_EXTENSION);
        @endphp
    <tr data-id="{{$customer_upload->id}}">
        <td>
                @if(\App\Helpers\ViewHelper::checkIfImage($ext))
                <a href="{{url('uploads/customer_uploads/'.$saved_file)}}" target="_blank" data-gallery="">
                 <img style="width:90px;height:80px;" src="{{url('uploads/customer_uploads/'.$saved_file)}}">
                 </a>
                @else
                    <img style="width:90px;height:80px;" src="{{url('admin/images/not_image.png')}}">
                @endif

        </td>

        <td>
            <p class="file">
                <a target="_blank" href="{{url('uploads/customer_uploads/'.$saved_file)}}"  download="" data-gallery="">
                    {{$customer_upload->filename}}
                </a>
            </p>
        </td>

        <td><span class="size">{{\App\Helpers\ViewHelper::formatDate($customer_upload->created_at,true)}}</span></td>
        <td>
            <button id="delete_customer_upload" data-id="{{$customer_upload->id}}" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
        </td>
    </tr>
    @endforeach
@endif