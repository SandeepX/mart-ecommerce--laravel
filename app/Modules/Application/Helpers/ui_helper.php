<?php
    function returnLabelColor($parameter)
    {
      //  dd($parameter);
        $parameter = (string)$parameter;
        switch ($parameter) {
            case 'accept':
            case 'accepted':
            case 'dispatched':
            case 'completed':
            case '1':
                return 'success';
            case 'pending':
            case 'processing':
                return 'warning';
            case '0':
            case 'reject':
            case 'rejected':
            case 'cancelled':
                return 'danger';

            default:
                return 'primary';
        }
}
