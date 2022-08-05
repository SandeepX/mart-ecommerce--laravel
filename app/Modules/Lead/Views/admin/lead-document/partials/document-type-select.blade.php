<select required name="document_types[]" class="form-control">
<option value="">Select Document Type</option>
    @if(isset($leadDocumentTypes) && count($leadDocumentTypes))
    @foreach($leadDocumentTypes as $key => $leadDocumentType)
    <option value="{{$key}}">{{$leadDocumentType}}</option>
    @endforeach
    @endif
</select>