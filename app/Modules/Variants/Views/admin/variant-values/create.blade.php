
<form method="post" id="addNewVariantvalue" action="{{route('admin.variant-values.store',['variantID'=> $variant->id])}}">
    @csrf
<div id="{{$targetModalID}}" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Variant Value of {{$variant->variant_name}}</h4>
            </div>
            <div class="modal-body">
               @include('Variants::admin.variant-values.partials.form')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success addVariantValue">Submit</button>
            </div>
        </div>

    </div>
</div>
</form>

@push('scripts')

    <script>
        $('#addNewVariantvalue').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }


            Swal.fire({
                title: 'Are you sure you want to add new variant value  ?',
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
