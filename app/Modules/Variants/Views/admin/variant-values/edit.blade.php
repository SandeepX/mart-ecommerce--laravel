<div id="{{$targetModalID}}{{$variantValue->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Variant Value : {{$variantValue->variant_value_name}}</h4>
            </div>
            <form method="post" id="updateVariantvalue" action="{{route('admin.variant-values.update',$variantValue->variant_value_code)}}">
                @method('PUT')
                @csrf
            <div class="modal-body">
                @include('Variants::admin.variant-values.partials.form',['variantValue' => $variantValue])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success editVariantValue">Submit</button>
            </div>
            </form>
        </div>

    </div>
</div>

@push('scripts')

    <script>
        $('#updateVariantvalue').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }


            Swal.fire({
                title: 'Are you sure you want to edit variant value  ?',
                showCancelButton: true,
                confirmButtonText: `Yes`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {

                    $(e.currentTarget).trigger(e.type, { 'send': true });
                    Swal.fire({
                        title: 'Please wait...',
                        hideClass: {
                            popup: ''
                        }
                    })
                }
            })
        });
    </script>

@endpush
