<script>

    $(function() {
        $('.datetimepicker').datepicker({
            format: 'yyyy-mm-dd',
            multidate:10,
            startDate: new Date(),
            endDate: '+10d',
        });
    });

    var i = 0;
    $("#addMore").click(function () {
        ++i;
        var questionName = 'quiz['+i+'][question]';
        var optionA = 'quiz['+i+'][option_a]';
        var optionB = 'quiz['+i+'][option_b]';
        var optionC = 'quiz['+i+'][option_c]';
        var optionD = 'quiz['+i+'][option_d]';
        var correctAnswer = 'quiz['+i+'][correct_answer]';
        var points = 'quiz['+i+'][points]';
        var questionIsActive = 'quiz['+i+'][question_is_active]';
        var questionNo = i+1;

        $(
            '<div id="questionDiv'+i+'">'+
            '<div class="form-group">'+
            '<label class="col-sm-2 control-label">Question '+questionNo+' :</label>'+
            '<div class="col-sm-6">'+
            '<input type="text"  class="form-control" name='+questionName+' required autocomplete="off" placeholder="Enter Quiz Question" value="" />'+
            '</div>'+

            '<div class="col-sm-2">'+
            '<button type="button" class="form-control btn-danger btn-xs remove-btn"  id="'+i+'">Remove Question</button>'+
            '</div>'+
            '</div>'+

            // '<div class="col-sm-2">'+
            // '<button type="button" class="form-control btn-success btn-xs " id="addMore" >Add More Question</button>'+
            // '</div>'+

            '<div class="form-group">'+

            '<div class="form-horizontal">'+
            '<div class="col-md-4 "style="margin-left: 178px;">'+
            '<label class="control-label">option A</label>'+
            '<input  class="form-control " name='+optionA+' required autocomplete="off" placeholder="Enter Option A " value="" />'+
            '</div>'+

            '<div class="col-md-4">'+
            '<label class="control-label">option B</label>'+
            '<input  class="form-control " name='+optionB+' required autocomplete="off" placeholder="Enter Option B" value="" />'+
            '</div>'+
            '</div>'+

            '<div class="form-horizontal">'+
            '<div class="col-md-4 " style="margin-left: 178px;">'+
            '<label class=" control-label">option c</label>'+
            '<input  class="form-control " name='+optionC+' required autocomplete="off" placeholder="Enter Option C" value="" />'+
            '</div>'+

            '<div class="col-md-4">'+
            '<label class=" control-label">option d</label>'+
            '<input  class="form-control " name='+optionD+' required autocomplete="off" placeholder="Enter Option D" value="" />'+
            '</div>'+
            '</div>'+
            '</div>'+

            '<div class="form-group">'+
            '<div class="row">'+
            '<label class="col-md-2 control-label">Correct Answer</label>'+
            '<div class="col-md-6">'+
            '<select class="form-control" name='+correctAnswer+' id="correct_answer">'+
            '<option value="">Select Correct Answer</option>'+
            '<option value="option_a">Option A</option>'+
            '<option value="option_b">Option B</option>'+
            '<option value="option_c">Option C</option>'+
            '<option value="option_d">Option D</option>'+
            '</select>'+
            '</div>'+
            '</div>'+
            '</div>'+

            '<div class="form-group">'+
            '<label class="col-sm-2 control-label">Point :</label>'+
            '<div class="col-sm-2">'+
            '<input type="number" min="0" class="form-control " name='+points+' required autocomplete="off" placeholder="Enter point" value="" />'+
            '</div>'+
            '</div>'+

            '<div class="form-group ">'+
            '<label  class="col-sm-2 control-label">Question Is Active</label>'+
            '<div class="col-sm-6">'+
            '<input type="checkbox" class="form-check-input " value="1"  id="question_is_active" name='+questionIsActive+' checked/> '+
            '</div>'+
            '</div>'+
            '</div>'
        ).appendTo('#dynamicForm');

    });

    $(document).on('click', '.remove-btn', function () {
        var button_id = $(this).attr("id");
        // var s =   ('#questionDiv'+button_id+'');

        $('#questionDiv'+button_id+'').remove();
    });

    $('#createNewPassage').submit(function (e, params) {
        var localParams = params || {};
        if (!localParams.send) {
            e.preventDefault();
        }
        Swal.fire({
            title: 'Are you sure you want to create New Quiz Passage  ?',
            showCancelButton: true,
            confirmButtonText: `Yes`,
            padding:'10em',
            width:'500px',
            allowOutsideClick:false

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

    $('#addMoreQuestion').submit(function (e, params) {
        var localParams = params || {};
        if (!localParams.send) {
            e.preventDefault();
        }
        Swal.fire({
            title: 'Are you sure you want to Add More Question In Passage  ?',
            showCancelButton: true,
            confirmButtonText: `Yes`,
            padding:'10em',
            width:'500px',
            allowOutsideClick:false

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
