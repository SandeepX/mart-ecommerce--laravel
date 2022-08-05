$(document).ready(function () {

   /* $(document).on({
        ajaxStart: function () {
           // $("body").addClass('loading');
            $("#custom-loader").css('display','block');
        },
        ajaxStop: function () {
            $("#custom-loader").css('display','none');
        }
    });*/
    $("form").on("submit", function (event) {
        event.preventDefault();
        $(document).ajaxStart(function(){
            $("#custom-loader").css('display','block');
        });

        $(document).ajaxStop(function(){
            $("#custom-loader").css('display','none');
        });
    });

});