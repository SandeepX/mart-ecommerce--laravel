<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" rel="stylesheet"/>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet"/>

</head>
<body>
<div class="container">
    <div class="row">
        <div class='col-sm-6'>
            <div class="form-group">
                <input type="text" class="datepicker"/>
            </div>
        </div>
        <script type="text/javascript">
            $('.datepicker').datetimepicker({
                format:'DD.MM.YYYY h:mm a',
                formatTime:'h:mm a',
                formatDate:'DD.MM.YYYY'
            });

            $.datetimepicker.setDateFormatter('moment');
        </script>
    </div>
</div>
</body>
</html>
