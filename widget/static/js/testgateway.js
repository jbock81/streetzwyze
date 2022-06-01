var firstmonthpress = true,
        pressedkey,
        whichposition = 0;
testgateway = {
// Initialize functions
    init: function ($) {
        $(document).on('keydown', '#Cc,#CCexpiry,#Cvv,#OTP', function (e) {
// Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [8, 9, 27, 13, 110, 190]) !== -1 ||
                    // Allow: Ctrl+A, Command+A
                            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                            // Allow: home, end, left, right, down, up
                                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                        // let it happen, don't do anything
                        return;
                    }
                    // Ensure that it is a number and stop the keypress
                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                        e.preventDefault();
                    }
                }).on('keyup', '#Cc', function (e) {
            var cardtype = testgateway.detectCardType($('#Cc').val());
            if (!($('#card-type-container-img').attr("data-cardType") === cardtype)) {
                $('#card-type-container-img').attr("data-cardType", cardtype);
                $('#card-type-container-img').attr("src", $("#card-type-container-img").attr("data-src") + cardtype + '.png');
            }
            // Show numpad on click of pin input box
        }).on('focus', '#Pin', function () {
            $('.virtual-keyboard').closest('.row').removeClass('hidden');
            // Set key in PIN input box
        }).on('click', '.small-keypay-bt', function (e) {
            e.preventDefault();
            var keypress = $(this).attr("data-key"),
                    pin = $("#Pin").val();
            if (keypress === 'del') {
                $("#Pin").val(pin.slice(0, -1));
            } else if (keypress === 'clr') {
                $("#Pin").val('');
            } else {
                if (pin.length < $("#Pin").attr("maxlength"))
                    $("#Pin").val(pin + keypress);
            }
            testgateway.validaterequired($('#Pin'));
            testgateway.validatepattern($('#Pin'));
            // Entering month and year for cc
        }).on('keydown', '#CCexpiry', function (e) {
			charCode=(e.which) ? e.which : e.keyCode
            var expiry = $('#CCexpiry').val();
            if (expiry.length < $('#CCexpiry').attr('maxlength')) {
                if (expiry.length == 0 && ((charCode >= 50 && charCode <= 57) || (charCode >= 98 && charCode <= 105))) {
                    $('#CCexpiry').val('0' + String.fromCharCode(charCode) + ' / ');
                    e.preventDefault();
                } else if (expiry.length == 1 && (expiry == '1') && ((charCode >= 51 && charCode <= 57) || (charCode >= 99 && charCode <= 105))) {

                    $('#CCexpiry').val('01 / ' + String.fromCharCode(charCode));
                    e.preventDefault();
                } else if (expiry.length == 1 && (expiry == '1' || expiry == '0') && ((charCode >= 48 && charCode <= 57) || (charCode >= 97 && charCode <= 105))) {
                    $('#CCexpiry').val(expiry + String.fromCharCode(charCode) + ' / ');
                    e.preventDefault();
                } else if (expiry.length == 5 && (charCode == '8')) {
                    $('#CCexpiry').val(expiry.slice(0, -4));
                    e.preventDefault();
                }
            }
        }).on('blur', '#Cc,#CCexpiry,#Cvv,#Pin,#OTP', function (e) {
            var validateError = testgateway.validaterequired(this);
            var patternError = testgateway.validatepattern(this);
            if (validateError !== '' || patternError !== '') {
                $(this).closest('.form-group').find('.error').html(validateError + patternError);
                $(this).closest('.form-group').addClass('has-error');
            }
        }).on('focus', '#Cc,#CCexpiry,#Cvv,#Pin,#OTP', function (e) {
            $(this).closest('.form-group').find('label').addClass('onfocus');
        }).on('click', 'label', function () {
            $(this).addClass('onfocus');
            $(this).closest('.form-group').find('input').focus();
        }).on('click', '#test-pay-bt', function () {
            var formError = testgateway.validateForm('#testGatewayForm');
            var cardError = testgateway.validateCard();
            var cardExpiryError = testgateway.validateCardExpiry($('#CCexpiry').val());
            if (formError === '' && cardError === '' && cardExpiryError === '') {
                $('#testGatewayForm').submit();
            }
        }).on('click', '#otp-input-bt', function () {
            var formError = testgateway.validateForm('#testOTPForm');
            var otpError = testgateway.validateOtp($('#OTP').val());
            if (formError === '' && otpError === '') {
                $('#testOTPForm').submit();
            }
        });

        if ($('#countdown').length) {
            var countdown = 5;
            $('#countdown-number').html(countdown + 's');
            var timer = setInterval(function () {
                countdown = --countdown <= 0 ? 0 : countdown;
                $('#countdown-number').html(countdown + 's');
                if (countdown == 0) {
                    $('#testPaymentSuccessForm').submit();
                    clearInterval(timer);
                }
            }, 1000);
        }
    },
    // Detecting type of card and returning background image
    detectCardType: function (number) {
        var re = {
            visa: /^4(?:[0-9]{0,15})?$/,
            mastercard: /^5[1-5](?:[0-9]{0,14})?$|^(?:222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[0-1][0-9]|2720)(?:[0-9]{0,12})?$/,
            verve: /^506(?:[0][9][9]|[1][0-9][0-8])(?:[0-9]{0,13})?$|^6500(?:[0][2-9]|[1][0-9]|[2][0-7])(?:[0-9]{0,13})?$|^6280511(?:[0-9]{0,12})?$/
        }

        for (var key in re) {
            var regex = new RegExp(re[key]);
            if (regex.test(number)) {
                return key;
            }
        }
        return 'blank';
    },
    // Function to validate form
    validateForm: function (formID) {
        var errormessage = '';
        $(formID + ' :input').filter(function (index, element) {
            if ($(element).attr("data-validate") === 'required') {
                validateError = testgateway.validaterequired(element);
                if (validateError !== '') {
                    errormessage += validateError;
                }
            }
            if ($(element).attr("data-pattern")) {
                patternError = testgateway.validatepattern(element);
                if (patternError !== '') {
                    errormessage += patternError;
                }
            }

        });
        return errormessage;
    },
    // Function to validate required fields
    validaterequired: function (element) {
        var errormessage = '';
        if ($(element).attr("data-validate") === 'required') {
            if ($(element).val()) {
                $(element).closest('.form-group').find('.error').html('');
                $(element).closest('.form-group').removeClass('has-error');
            } else {
                errormessage = $(element).attr("data-validateError");
                $(element).closest('.form-group').find('.error').html(errormessage);
                $(element).closest('.form-group').addClass('has-error');
            }
        }
        return errormessage;
    },
    // Function to validate pattern for input fields
    validatepattern: function (element) {
        var errormessage = '';
        var pattern = jQuery(element).attr("data-pattern"),
                regex = new RegExp(pattern),
                value = jQuery(element).val();
        if (value) {
            if (regex.test(value)) {
                $(element).closest('.form-group').find('.error').html('');
                $(element).closest('.form-group').removeClass('has-error');
            } else {
                errormessage = jQuery(element).attr("data-patternError");
                $(element).closest('.form-group').find('.error').html(errormessage);
                $(element).closest('.form-group').addClass('has-error');
            }
        }
        return errormessage;
    },
    // Function to validate card number
    validateCard: function () {
        var errormessage = '';
        var inputMonth = $('#CCexpiry').val().substring(0, 2);
        var inputYear = $('#CCexpiry').val().substring(5, 7);
        if ($('#Cc').val() !== '6280511000000095' || $('#Cvv').val() !== '123' || $('#Pin').val() !== '0000' || inputMonth !== '12' || inputYear !== '26') {
            $('.alert--error').removeClass('hidden');
            errormessage = 'error';
        } else {
            $('.alert--error').addClass('hidden');
        }
        return errormessage;
    },
    // Function to validate OTP number
    validateOtp: function (value) {
        var errormessage = '';
        if (value === '000000') {
            $('.alert--error').addClass('hidden');
        } else {
            $('.alert--error').removeClass('hidden');
            errormessage = 'error';
        }
        return errormessage;
    },
    // Funtion to validate expiry date of card
    validateCardExpiry: function (value) {
        var errormessage = ''
        var date = new Date();
        var currentMonth = (date.getMonth() + 1) < 10 ? ('0' + (date.getMonth() + 1)) : ((date.getMonth() + 1) + '');
        var currentYear = date.getFullYear().toString().substring(2, 4);
        var inputMonth = value.substring(0, 2);
        var inputYear = value.substring(5, 7);
        if (parseInt(inputYear) < parseInt(currentYear) || ((inputYear === currentYear) && (parseInt(inputMonth) < parseInt(currentMonth)))) {
            $('#CCexpiry').closest('.form-group').find('.error').html('Card Expired');
            errormessage = 'error';
        }
        return errormessage;
    }
}

testgateway.init(jQuery);