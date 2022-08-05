<!-- Modal -->
<div class="modal fade" id="updatePriceSettingModal" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Price Setting of Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="update-price-form"
                action=""
                method="post">
                {{csrf_field()}}
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Variant</th>
                            <th>Price Setting</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <div id="variant-td">

                                </div>
                                <input type="hidden" id="product_variant_code" name="product_variant_code"/>
                                <input type="hidden" id="warehouse_product_master_code"
                                       name="warehouse_product_master_code"/>
                            </td>
                            <td>
                                <div>
                                    <label for="mrp">Mrp:</label>
                                    <input id="mrp" type="number" min="1" name="mrp" required/>
                                </div>
                                <br>
                                <div>
                                    <label for="admin_margin_type">Admin Margin:</label>

                                    &nbsp;<select id="admin_margin_type" name="admin_margin_type" required>
                                        <option value="p">%</option>
                                        <option value="f">F</option>
                                    </select>

                                    <input id="admin_margin_value" type="number" min="0" step=".01" name="admin_margin_value" required/>
                                </div>
                                <br>
                                <div>
                                    <label for="wholesale_margin_type">Wholesale Margin:</label>

                                    &nbsp;<select id="wholesale_margin_type" name="wholesale_margin_type" required>
                                        <option value="p">%</option>
                                        <option value="f">F</option>
                                    </select>

                                    <input id="wholesale_margin_value" type="number" min="0" step=".01"
                                           name="wholesale_margin_value" required/>
                                </div>
                                <br>
                                <div>
                                    <label for="retail_margin_type">Retail Margin:</label>

                                    &nbsp;<select id="retail_margin_type" name="retail_margin_type" required>
                                        <option value="p">%</option>
                                        <option value="f">F</option>
                                    </select>

                                    <input id="retail_margin_value" type="number" min="0" step=".01" name="retail_margin_value" required/>
                                </div>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary updatePrice">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')

    <script>
        $('.updatePrice').click(function (e){
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to save the Changes ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#update-price-form').submit();
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })
        })

    </script>

@endpush


