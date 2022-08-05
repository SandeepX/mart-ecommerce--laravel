<script>

    function showHideRegistration() {
        let registrationCharge = document.getElementById("registration-charge");
        let refundableRegistrationCharge = document.getElementById("refundable-registration-charge");
        let baseInvestment = document.getElementById("base-investment");
        let remarks = document.getElementById("remarks");
        let submitBtn = document.getElementById("submit-btn");

        if (registrationCharge.style.display === "none") {
            registrationCharge.style.display = "block";
            refundableRegistrationCharge.style.display = "block";
            baseInvestment.style.display = "block";
            document.getElementById("registration-charge-input").required = true;
            document.getElementById("refundable-registration-charge-input").required = true;
            document.getElementById("base-investment-input").required = true;
            document.getElementById("remarks-input").required = false;
        } else {
            registrationCharge.style.display = "none";
            refundableRegistrationCharge.style.display = "none";
            baseInvestment.style.display = "none";
            document.getElementById("registration-charge-input").required = false;
            document.getElementById("refundable-registration-charge-input").required = false;
            document.getElementById("base-investment-input").required = false;
            document.getElementById("remarks-input").required = true;
        }
        remarks.style.display = "none";
        submitBtn.style.display = "block";

    }
    function showHideRemarks() {
        let remarks = document.getElementById("remarks");
        let registrationCharge = document.getElementById("registration-charge");
        let refundableRegistrationCharge = document.getElementById("refundable-registration-charge");
        let baseInvestment = document.getElementById("base-investment");
        let submitBtn = document.getElementById("submit-btn");


        if (remarks.style.display === "none") {
            remarks.style.display = "block";
            document.getElementById("registration-charge-input").required = false;
            document.getElementById("refundable-registration-charge-input").required = false;
            document.getElementById("base-investment-input").required = false;
            document.getElementById("remarks-input").required = true;
        } else {
            remarks.style.display = "none";
            document.getElementById("registration-charge-input").required = true;
            document.getElementById("refundable-registration-charge-input").required = true;
            document.getElementById("base-investment-input").required = true;
            document.getElementById("remarks-input").required = false;
        }
        registrationCharge.style.display = "none";
        refundableRegistrationCharge.style.display = "none";
        baseInvestment.style.display = "none";
        submitBtn.style.display = "block";
    }

</script>

<script>
    $(document).ready(function() {
        $('.fancybox').fancybox();
        $('.fancybox-buttons').fancybox({
            openEffect  : 'none',
            closeEffect : 'none',
            prevEffect : 'none',
            nextEffect : 'none',
            closeBtn  : false,
            helpers : {
                title : {
                    type : 'inside'
                },
                buttons	: {}
            },
            afterLoad : function() {

            }
        });
    });
</script>
