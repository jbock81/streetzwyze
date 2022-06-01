var isMobile = false; //initiate as false

// device detection

if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)

        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4)))

    isMobile = true;



formvalidate = {

// events handler

    init: function () {



// Function on generate URL button click

        jQuery(document).on('click', '#secure_form_btn', function () {

			var formID = 'secure_form';

			if( $("#secure_form").css("display")=="none")

			{

				formID='secure_form_mobile';

			}

			else

			{

				formID = 'secure_form';

			}



            

            var formError = formvalidate.validateForm('#' + formID);

            if (formError !== '') {

                formvalidate.displaymessage(formID, formError);

            } else {

                formvalidate.displaymessage(formID, '');

                formvalidate.ajaxCall('#' + formID);

            }

// Function on validate URL button click

        }).on('click', '#validate_form_btn', function () {

            var formID = 'validate_form';

            var formError = formvalidate.validateForm('#' + formID);

            if (formError !== '') {

                formvalidate.displaymessage(formID, formError);

            } else {

                formvalidate.displaymessage(formID, '');

                formvalidate.ajaxCall('#' + formID);

            }

// Function on send amount button click

        }).on('click', 'input[id^=vc_form_btn]', function () {

            var formID = jQuery(this).closest("form").attr("id");

            var formError = formvalidate.validateForm('#' + formID);

            if (formError !== '') {

                formvalidate.displaymessage('validate_form', formError);

            } else {

                formvalidate.displaymessage('validate_form', '');

                formvalidate.ajaxCall('#' + formID);

            }

// function on send mail click.

        }).on('click', '#email_form_btn', function () {

            var formID = jQuery(this).closest("form").attr("id");

            var formError = formvalidate.validateForm('#' + formID);

            if (formError !== '') {

                jQuery("#email_form_btn").val(formError).prop("disabled", "disabled");

                setTimeout(function () {

                    jQuery(formID).find("#email_form_btn").val("Send Email");

                }, 500);

            } else {

                formvalidate.ajaxCall('#' + formID);

            }

// function to check any change in input box and show relevant message.

        }).on('blur keyup keypress change', 'input[type=text],select[id="bank_code"]', function () {

            jQuery(document).off('mouseover', 'input[type=text],select[id="bank_code"]');

            var formID = jQuery(this).closest("form").attr("id");

            var form_pa = jQuery(this).closest("form").attr("data-formpa");

            var validateError = formvalidate.validaterequired(this);

            var patternError = formvalidate.validatepattern(this);

            if (validateError !== '' || patternError !== '') {

                formvalidate.displaymessage(form_pa, validateError + patternError);

               jQuery('#' + formID + ' :input').not(this).prop("disabled", true);
               jQuery('#amt').prop("disabled", false);

            } else {

                jQuery(document).on('mouseover', 'input[type=text],select[id="bank_code"]', formvalidate.mousemove);

                formvalidate.displaymessage(form_pa, '');

                jQuery('#' + formID + ' :input').not("input[type=button]").prop("disabled", false);

                if (jQuery(this).attr("id") === 'amount') {

                    var amount = jQuery(this).val();

                    var calculated_amount = 0;

                    if (amount < 200) {

                        calculated_amount = amount;

                    } else if (amount >= 200 && amount <= 1000000) {

                        calculated_amount = amount - 150;

                    }

                    formvalidate.displaymessage(form_pa, calculated_amount + ' will be deposited to the seller account upon successful validation of purchase');

                }

            }

            var formError = formvalidate.validateForm('#' + formID);

            if (formError === '') {

                jQuery(document).off('mouseover', 'input[type=text],select[id="bank_code"]');

                formvalidate.displaymessage(form_pa, 'Click on highlighted button to proceed to next step');

                jQuery("#" + formID).find("input[type=button]").prop("disabled", false);

            }

        }).on('focus', 'input[type=text],select[id="bank_code"]', function () {

            jQuery(document).on('mouseover', 'input[type=text],select[id="bank_code"]', formvalidate.mousemove);

            var formID = jQuery(this).closest("form").attr("id");

            if (formID == 'secure_form' ||formID == 'secure_form_mobile') {

                jQuery("#" + formID).find("#secure_form_btn").removeClass('hidden').prop("disabled", "disabled");

                jQuery("#" + formID).find("#secure_url").addClass('hidden');

                jQuery(".share-via").addClass('hidden');

                jQuery(".display").addClass('hidden');

            } else if (formID == 'validate_form') {

                jQuery(".validation").html("");

				jQuery(".vault").html("");

            }

            var form_pa = jQuery(this).closest("form").attr("data-formpa");

            var helpmessage = formvalidate.displayhelp(this);

            formvalidate.displaymessage(form_pa, helpmessage);

        });

    },

    // Function for mouse hover on input box

    mousemove: function () {

        var formID = jQuery(this).closest("form").attr("id");

        var form_pa = jQuery(this).closest("form").attr("data-formpa");

        var helpmessage = formvalidate.displayhelp(this);

        formvalidate.displaymessage(form_pa, helpmessage);

    },

    // Function to show help message of input box in PA section

    displayhelp: function (element) {

        var helpmessage = '';

        if (jQuery(element).attr("data-help") !== undefined) {

            helpmessage = jQuery(element).attr("data-help");

        }

        return helpmessage;

    },

    // Function to display message in PA section

    displaymessage: function (formID, message) {

        if (message !== undefined) {

            jQuery("." + formID + '_pa').html(message);

        }

    },

    // Function to validate form

    validateForm: function (formID) {

        var errormessage = '';

        jQuery(formID + ' :input[type=text]').filter(function (index, element) {

            if (jQuery(element).attr("data-validate") === 'required') {

                validateError = formvalidate.validaterequired(element);

                if (validateError !== '') {

                    errormessage += validateError;

                }

            }

            if (jQuery(element).attr("data-pattern")) {

                patternError = formvalidate.validatepattern(element);

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

        if (jQuery(element).attr("data-validate") === 'required') {

            if (jQuery(element).val()) {



            } else {

                errormessage = jQuery(element).attr("data-validateError");

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
			
			if(jQuery(element).attr("id")=="amount")
			{
				if(value>150000)
				{
					errormessage = jQuery(element).attr("data-patternError");
				}
				
				if (value<200) 
				{
                    errormessage = jQuery(element).attr("data-patternError");
				} 
			}
			else
			{
			    if (regex.test(value)) {


				} else {
	
					errormessage = jQuery(element).attr("data-patternError")
	
				}
			}

        }

        return errormessage;

    },

    // Function to serialize form

    serializeform: function (formID) {

        var inputs = {};

        jQuery(formID + ' :input').filter(function (index, element) {

            if (jQuery(element).val()) {

                var elementName = jQuery(element).attr("data-name");

                inputs[elementName] = jQuery(element).val();

            }

        });

        return JSON.stringify(inputs);

    },

    // Ajax call

    ajaxCall: function (formID) {

        jQuery.ajax({

            type: "post",

            url: 'ajaxcall.php',

            dataType: 'json',

            data: jQuery(formID).serialize(),

            success: function (response) {



                result = jQuery.parseJSON(response);



                if (formID === '#secure_form' || formID === '#secure_form_mobile') {

                    formvalidate.secureFormResponse(formID, result);

                } else if (formID == '#validate_form') {

                    formvalidate.validateFormResponse(formID, result);

                } else if (formID == '#email_form') {

                    formvalidate.emailFormResponse(formID, result);

                } else {

                    formvalidate.VCFormResponse(formID, result);

                }



            },
			
            error: function (xhr, ajaxOptions, thrownError) {


            }

        });

    },

    // Function to handle response of secure form

    secureFormResponse: function (formID, result) {

        if (result.ResponseCode === '00') {

			

			if(formID=='#secure_form')

			{

				jQuery("input[type=text],select").val("");

			}
			else
			{
				jQuery("input[type=text],select").val("");
			}

            jQuery(formID).find("#secure_form_btn").addClass('hidden');

            jQuery(formID).find("#secure_url").attr("href", result.PaymentUrl).removeClass('hidden');
            jQuery(".share-via").removeClass("hidden");

			if( $("#secure_form").css("display")=="none")

			{

				formvalidate.displaymessage('secure_form_mobile', "Payment expected from you click on secure fund.");

			}

			else

			{

				formvalidate.displaymessage('secure_form', "Payment expected from you click on secure fund.");

			}

            //isMobile=true;

            if (isMobile) {

                jQuery(".whatsapp").removeClass("hidden").find("a").attr("href", "whatsapp://send?text=Generated a reservation on streetzwyze please click on link to secure funds for this purchase.%0A%0A" + encodeURIComponent(result.PaymentUrl) + "%0A%0AWill commerce delivery process once I receive notification fund been secured.%0A%0AThanks for your patronage");

                //jQuery(".bbm").removeClass("hidden").find("a").attr("href", "bbmi://api/share?message=Hello&userCustomMessage=Generated a reservation on streetzwyze please click on link to secure funds for the reservation%0A%0A" + encodeURIComponent(result.PaymentUrl) + "%0A%0AWill commerce delivery process once I receive notification fund been secured.%0A%0AThanks for your patronage.");

                jQuery(".fb-messenger").removeClass("hidden").find("a").attr("href", "fb-messenger://share?link=" + encodeURIComponent(result.PaymentUrl));

				jQuery(".clpbrd").removeClass("hidden");
				

            } else {

                jQuery(".email").removeClass("hidden");

				jQuery(".clpbrd").removeClass("hidden");

                jQuery(".facebook").removeClass("hidden").find("a").attr("href", "https://www.facebook.com/dialog/send?app_id=1769856583294036&display=popup&link=" + encodeURIComponent(result.PaymentUrl) + "&redirect_uri=" + encodeURIComponent("https://streetzwyze.com/"));

                jQuery("#from_email").val("noreply@streetzwyze.com");

                jQuery("#payment_url").attr("href", result.PaymentUrl);

                jQuery("#fund_url").val(result.PaymentUrl);

            }

			var clipboard =new Clipboard(".share-via li.clpbrd a", {

			  text: function(trigger) {
				return result.PaymentUrl; 
			  }

			});
			
			clipboard.on('success', function(e) {
				
				/*jQuery("#secure_form").find("input[type=text],select").val("");
				jQuery("#secure_form_mobile").find("input[type=text],select").val("");
				
				jQuery(".share-via").addClass("hidden");

                jQuery("#secure_form").find("#secure_form_btn").removeClass('hidden').prop("disabled", "disabled");

                jQuery("#secure_form").find("#secure_url").addClass('hidden');
				
				 jQuery("#secure_form_mobile").find("#secure_form_btn").removeClass('hidden').prop("disabled", "disabled");

                jQuery("#secure_form_mobile").find("#secure_url").addClass('hidden');
				
				 jQuery(".display").addClass('hidden');*/
				 
				if(isMobile)
				{
					$.toast({
						heading: 'Success',
						text: 'Link copied share for classified ads, instagram or marketplace listings',
						bgColor: '#36A582',
						position: 'mid-center',
						icon: 'success'
					});
				}
				else
				{
					$.toast({
						heading: 'Success',
						text: 'Link copied share for classified ads, instagram or marketplace listings',
						bgColor: '#36A582',
						position: 'top-right',
						icon: 'success'
					});
				}
				
				setTimeout(function(){window.location.reload();},4000);
				
   				
			});
            

        } else {

            //jQuery(formID).find("input[type=text],select").val("");

            var formerrors = '';

            jQuery.each(result.Errors, function (i, error) {

                formerrors += error;

            });

            jQuery(".share-via").addClass("hidden");

            jQuery(".display").append(formerrors).removeClass("hidden");

            formvalidate.displaymessage(formID, "Please try to generate a reservation again")

        }

    },

    // Function to handle response of validate form

    validateFormResponse: function (formID, result) {

        if (result.ResponseCode === '00') {

           jQuery("input[type=text],select").val("");

		   jQuery(".vault").html("");

            jQuery("#validate_form_btn").prop("disabled", "true");

            jQuery(".validation").html("");

			formvalidate.displaymessage('validate_form', 'List of active reservations<br> <br> Click to get further details');


		   jQuery.each(result.ReservationStatusV2, function (i, status){


					if(status.FundSecured=="00")

					{

						status.OrderAmount=~~status.OrderAmount;

						var order=jQuery('<input type="button" data-id="'+status.ReservationId+'" data-order="'+status.OrderNo+'" data-duration="'+status.DeliveryDurationRemaining+'" data-amount="'+status.OrderAmount+'" onclick="createvault(this)" style="width:auto;margin:5px;" class="btn form-control btn-danger" id="btn_order'+i+'" value="'+status.OrderNo+' | '+status.DeliveryDurationRemaining+'">')

						jQuery(".validation").append(order);

					}
					

			   });

			  startTimer();

		    formvalidate.displaymessage('validate_form', 'List of purchases pending confirmation of successful delivery');
            

			
			/*jQuery.each(result.Funds, function (i, fund) {

                jQuery(".validation").append(jQuery("<form>", {"id": "vc_form-" + i, "data-formpa": "validate_form"})

                        .append(jQuery("<div>", {"class": "form-inline input-group"})

                                .append(jQuery("<input>", {"name": "action", "data-name": "action", "type": "hidden", "value": "vault_code"}))

                                .append(jQuery("<input>", {"name": "key", "data-name": "key", "type": "hidden", "value": jQuery("#validate_form").find("#key").val()}))

                                .append(jQuery("<input>", {"class": "form-control", "name": "amt", "data-name": "amt", "type": "text", "value": fund.MerchantOrderAmount}))

                                .append(jQuery("<input>", {"class": "form-control time", "name": "time", "readonly": "true", "data-name": "time", "type": "text", "value": fund.DurationRemainingHours}))

                                .append((fund.FundSecured === '00') ? jQuery("<input>", {"class": "form-control vc", "id": "vault_code", "name": "vault_code", "placeholder": "Code", "data-name": "vault_code", "type": "text", "data-help": "Enter 6 digit code.", "data-validate": "required", "data-pattern": "^\\d{6}$", "data-patternError": "Invalid code. Please update", "data-validateError": "Please enter code"}) : '')

                                .append((fund.FundSecured === '00') ? jQuery("<input>", {"class": "btn form-control", "id": "vc_form_btn", "value": "Send", "type": "button", "disabled": "true"}) : '')

                                ));

            });*/

			

        } else {

            formvalidate.displaymessage('validate_form', result.ResponseDescription);
			//formvalidate.displaymessage('validate_form', 'No active reservations for this mobile number');
            jQuery(".validation").html("");

        }

    },

    // Function to handle response of send form

    VCFormResponse: function (formID, result) {

        if (result.ResponseCode === '00') {

			jQuery("input[type=text],select").val("");

            formvalidate.displaymessage('validate_form', 'Code validation successful');

            jQuery(formID).find("#vc_form_btn").val("OK");

			jQuery(formID).find("#vc_form_btn").prop("disabled",true);

			jQuery(formID).find("#vc_form_btn").hide();

			jQuery(formID).find("input[id=vault_code]").hide();

			jQuery(formID).find("input[id=amt]").hide();

			jQuery("input[data-id='"+jQuery(formID).find("#rid").val()+"']").remove();

            setTimeout(function () {

            //jQuery(formID).find(".time").val("0");

            //jQuery(formID).find("input[id=vault_code]").addClass("hidden");

            //jQuery(formID).find("#vc_form_btn").addClass("hidden");

            //jQuery(".share-via").addClass("hidden");

            }, 500);

        } else {

            formvalidate.displaymessage('validate_form', result.ResponseDescription);

            //jQuery(formID).find("input[id=vault_code]").val("");

            jQuery(formID).find("#vc_form_btn").val("Failed");

			jQuery(formID).find("#vc_form_btn").css("background-color","#d9534f");

			

            setTimeout(function () {

                jQuery(formID).find("#vc_form_btn").css("background", "").val("Send");

            }, 2000);

        }

    },

	

	

    // Function to handle response of secure form

    emailFormResponse: function (formID, result) {

        jQuery("#email_form_btn").val(result.message);

        if (result.status === 202) {

            setTimeout(function () {
				window.location.reload();
               jQuery("#" + formID).find("input[type=text],select").val("");
				
				
                jQuery("#emailModal .close").click();

                jQuery("#email_form_btn").val("Send Email")

				

				if( $("#secure_form").css("display")=="none")

				{

					formvalidate.displaymessage('secure_form_mobile', "");

				}

				else

				{

					formvalidate.displaymessage('secure_form', "");

				}
                

                jQuery(".share-via").addClass("hidden");

                jQuery("#" + formID).find("#secure_form_btn").removeClass('hidden').prop("disabled", "disabled");

                jQuery("#" + formID).find("#secure_url").addClass('hidden');
				jQuery(".display").addClass('hidden');

            }, 500);

        } else {

            jQuery(formID).find("#email_form_btn").css("background-color", "#330000");

            setTimeout(function () {

                jQuery(formID).find("#email_form_btn").val("Send Email").css("background-color", "#36a582");

            }, 500);

        }

    }

}

function clear()
{
	/*  
	    jQuery("#secure_form").find("input[type=text],select").val("");
	    jQuery("#secure_form_mobile").find("input[type=text],select").val("");
	
	    jQuery(".share-via").addClass("hidden");

    	jQuery("#secure_form").find("#secure_form_btn").removeClass('hidden').prop("disabled", "disabled");
    	
    	jQuery("#secure_form").find("#secure_url").addClass('hidden');
	    jQuery("#secure_form_mobile").find("#secure_form_btn").removeClass('hidden').prop("disabled", "disabled");
	    jQuery("#secure_form_mobile").find("#secure_url").addClass('hidden');
	
	    jQuery(".display").addClass('hidden');
	 
	 */
	 window.location.reload();
}

function createvault(obj){

		jQuery(".vault").html("");

		formvalidate.displaymessage('validate_form', 'Validate purchase and provide code to release payment');

		jQuery(".vault").append(jQuery("<form>", {"id": "vc_form-" + 0, "data-formpa": "validate_form"})

                        .append(jQuery("<div>", {"class": "form-inline input-group"})

                                .append(jQuery("<input>", {"name": "action", "data-name": "action", "type": "hidden", "value": "vault_code"}))

                                .append(jQuery("<input>", {"name": "key", "data-name": "key", "type": "hidden", "value": jQuery("#validate_form").find("#key").val()}))

								.append(jQuery("<input>", {"name": "rid", "id":"rid", "data-name": "rid", "type": "hidden", "value": jQuery(obj).data("id")}))

                                .append(jQuery("<input>", {"class": "form-control","id": "amt", "name": "amt", "style":"width:30%;margin:3px;border-radius:3px" ,"readonly": "true", "data-name": "amt", "type": "text", "value": $(obj).data("amount")}))

                                //.append(jQuery("<input>", {"class": "form-control time", "name": "time","style":"width:22%;margin:3px;", "readonly": "true", "data-name": "time", "type": "hidden", "type": "text", "value": $(obj).data("duration")})) 

                                .append((1==1) ? jQuery("<input>", {"class": "form-control vc","style":"width:30%;margin:3px;border-radius:3px", "id": "vault_code", "name": "vault_code", "placeholder": "Release", "data-name": "vault_code", "type": "text", "data-help": "Enter 6 digit code.", "data-validate": "required", "data-pattern": "^\\d{6}$", "data-patternError": "Invalid code. Please update", "data-validateError": "Please enter code"}) : '')

                                .append( jQuery("<div>", {"class": "form-group-send"})

								.append((1==1) ? jQuery("<input>", {"class": "btn form-control","style":"width:auto;margin:3px;border-radius:3px", "id": "vc_form_btn", "value": "Send", "type": "button", "disabled": "true"}) : '')

								.append(jQuery("</div>")))

								.append(jQuery("</form>"))

                                ));

}



function startTimer()

{

	

	$("[id^='btn_order']").each(function(i,itm){

		var arr=$(itm).data("duration").split(":")

		var hr=parseInt(arr[0],10);

		var mn=parseInt(arr[1],10);

		var totalsecs=hr*3600+mn*60+parseInt(arr[2],10);

		if(totalsecs>0)

		{

			var t=setInterval(function(){

				totalsecs=totalsecs-1;

				var hours = Math.floor(totalsecs / 3600);

				var minutes = Math.floor((totalsecs%3600) / 60);

				var seconds = totalsecs % 60;

				$(itm).data("duration",("00"+hours).slice(-2)+":"+("00"+minutes).slice(-2)+":"+("00"+seconds).slice(-2))

				$(itm).val( $(itm).data("order")+" | "+ $(itm).data("duration"))

				if(totalsecs<=0)

				{

					$(itm).attr("disabled",true)

					clearTimeout(t);

				}

			},1000)

		}

	});

}


formvalidate.init();

if(!isMobile)
{
	jQuery("#fd").css('min-height','341px');
	jQuery("#sd").css('min-height','323px')
}

$(document).ajaxSend(function(event, request, settings) {

	 $.blockUI({

					message: '<img src="img/Spinner.gif" width="80px" height="80px" />', 

					css: { 

					border: 'none', 

					padding: '0px', 

					backgroundColor: 'transparent', 

					'-webkit-border-radius': '10px', 

					'-moz-border-radius': '10px',

					opacity: .5

				} }); 

});

		

$(document).ajaxComplete(function(event, request, settings) {

	$.unblockUI();

});