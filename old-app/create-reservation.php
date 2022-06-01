<?php

/*

 * To change this license header, choose License Headers in Project Properties.

 * To change this template file, choose Tools | Templates

 * and open the template in the editor.

 */

include_once 'header.php';

$file = fopen("bank.csv","r");

while(! feof($file))

{

  $row[]=fgetcsv($file);

}

fclose($file);

?>
<style type="text/css">
    .sectionHero-upsell{
        /*background: #fff;*/
        padding: 30px;
        /*border-radius: 10px;*/
        /*border: 1px solid #ccc;*/
    }
    .sectionHero-upsell-img {
        display: block;
        -webkit-animation-duration: 1s;
        animation-duration: 1s;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
        -webkit-animation-name: b;
        animation-name: b
    }

    .sectionHero-upsell-imgShadow {
        position: absolute;
        top: 0;
        left: 0;
        z-index: -1
    }

    .sectionHero-upsell-title {
        font-size: 24px;
        font-size: 2.4rem;
        line-height: 1.33;
        z-index: 1;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        min-height: 192px;
        margin: 0;
        position: relative;
        font-weight: 400
    }

    .sectionHero-upsell-list {
        min-height: 136px;
        margin-top: 0;
        margin-bottom: 0;
    }

    .sectionHero-upsell-list
     li {
        font-size: 16px;
        margin-bottom: 5px
    }
    .sectionHero-upsell-icon {
        vertical-align: middle;
        -webkit-transition: all .15s ease-in-out;
        transition: all .15s ease-in-out;
    }   
    .is-active{
        color: #BB2D27;
        font-size: 18px!important;
        right: 32px;
        position: relative;
    } 
    .is-active .sectionHero-upsell-icon{
        width: 30px!important;
        display: inline-block!important;
        fill: #BB2D27;
    }
    .is-active.sectionHero-upsell-item:before{
        display: none!important;
    }
    .sectionHero-upsell-icon{
        display: none;
    }
    .sectionHero-upsell-item:before {
        position: absolute;
        left: 45px;
        opacity: .7;
        width: 16px;
        height: 16px;
        border: 2px solid currentColor;
        border-radius: 50%;
        content: "";
    }
    .sectionHero-upsell-text {
        padding-left: 20px;
        font-size: 30px;
    }
    @media only screen and (min-width: 992px) and (max-width: 1200px) {
        .col-sp{
            width: 100%!important;
        }
    }
</style>

<script type="text/javascript">
    $(function() {
        var lis = $(".sectionHero-upsell-list > li"),
            currentHighlight = 0;
            N = 5;//interval in seconds
        setInterval(function() {
            currentHighlight = (currentHighlight + 1) % lis.length;
            lis.removeClass('is-active').eq(currentHighlight).addClass('is-active');
        }, N * 1000);
    });
</script>


<section id="secure">

    <div class="container">

        <div class="row">

            <div class="col-md-5 hidden-sm hidden-xs">

                <div class="secure-slider">
                    <div>
                        <div class="sectionHero-upsell">
                            <span class="sectionHero-upsell-title">
                              <span class="sectionHero-upsell-logo">
                                <span class="sectionHero-upsell-imgShadow">
                                <svg width="231" height="192" viewBox="0 0 231 192" xmlns="http://www.w3.org/2000/svg"><g fill="#76CEF1" fill-rule="evenodd" opacity=".305"><circle cx="135" cy="96" r="96" opacity=".3"></circle><circle cx="115" cy="96" r="96" opacity=".3"></circle><circle cx="96" cy="96" r="96" opacity=".3"></circle></g></svg>        </span>
                              </span>
                              <span class="sectionHero-upsell-text">Secure <br> Item purchase</span>
                            </span>
                            <div class="sectionHero-steps">
                              <span class="sectionHero-steps-decorator"></span>
                              <ol class="sectionHero-upsell-list">
                                  <li class="sectionHero-upsell-item is-active">
                                    <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg"><path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path></svg>
                                    <span class="sectionHero-upsell-desc">
                                      Agree on terms with seller
                                    </span>
                                  </li>
                                  <li class="sectionHero-upsell-item">
                                    <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg"><path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path></svg>
                                    <span class="sectionHero-upsell-desc">
                                      Generate reservation
                                    </span>
                                  </li>
                                  <li class="sectionHero-upsell-item">
                                    <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg"><path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path></svg>
                                    <span class="sectionHero-upsell-desc">
                                      Secure fund
                                    </span>
                                  </li>
                                  <li class="sectionHero-upsell-item">
                                    <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg"><path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path></svg>
                                    <span class="sectionHero-upsell-desc">
                                      Notify seller
                                    </span>
                                  </li>
                                  <li class="sectionHero-upsell-item">
                                    <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg"><path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path></svg>
                                    <span class="sectionHero-upsell-desc">
                                      Wait for delivery
                                    </span>
                                  </li>
                              </ol>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="sectionHero-upsell">
                            <span class="sectionHero-upsell-title">
                              <span class="sectionHero-upsell-logo">
                                <span class="sectionHero-upsell-imgShadow">
                                <svg width="231" height="192" viewBox="0 0 231 192" xmlns="http://www.w3.org/2000/svg"><g fill="#76CEF1" fill-rule="evenodd" opacity=".305"><circle cx="135" cy="96" r="96" opacity=".3"></circle><circle cx="115" cy="96" r="96" opacity=".3"></circle><circle cx="96" cy="96" r="96" opacity=".3"></circle></g></svg>        </span>
                              </span>
                              <span class="sectionHero-upsell-text">Seamless <br>Payment collection</span>
                            </span>
                            <div class="sectionHero-steps">
                              <span class="sectionHero-steps-decorator"></span>
                              <ol class="sectionHero-upsell-list">
                                  <li class="sectionHero-upsell-item is-active">
                                    <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg"><path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path></svg>
                                    <span class="sectionHero-upsell-desc">
                                      Agree on terms with buyer
                                    </span>
                                  </li>
                                  <li class="sectionHero-upsell-item">
                                    <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg"><path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path></svg>
                                    <span class="sectionHero-upsell-desc">
                                      Generate a reservation
                                    </span>
                                  </li>
                                  <li class="sectionHero-upsell-item">
                                    <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg"><path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path></svg>
                                    <span class="sectionHero-upsell-desc">
                                      Share with buyer
                                    </span>
                                  </li>
                                  <li class="sectionHero-upsell-item">
                                    <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg"><path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path></svg>
                                    <span class="sectionHero-upsell-desc">
                                      Receive confirmation 
                                    </span>
                                  </li>
                                  <li class="sectionHero-upsell-item">
                                    <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg"><path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path></svg>
                                    <span class="sectionHero-upsell-desc">
                                      Deliver
                                    </span>
                                  </li>
                              </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7 col-sm-12">

                <div class="row">

                    <div class="col-md-8">

                        <form id="secure_form" data-formpa="secure_form">

                            <input type="hidden" name="action" data-name="action" value="generate_URL"/>

                            <input type="hidden" name="key" data-name="key" value="<?php echo $key_verify ?>"/>

                            <div class="form-group">

                                <div class="input-group">

                                    <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>

                                    <input type="text" class="form-control" id="email" name="email" data-name="email" placeholder="Merchant email address" data-help="Provide seller email address to receive notification that payment been successfully secured pending delivery of good or service.<br><br>" data-validate="required" data-pattern="^[\W\w]+@[\W\w]{3,}\.[\W\w]{2,3}$" data-patternError="Invalid email address please update.<br><br>" data-validateError="Provide valid data for seller email address.<br><br>">

                                </div>

                            </div>

                            <div class="form-group">

                                <div class="input-group">

                                    <span class="input-group-addon"><i class="fa fa-mobile fa-fw"></i></span>

                                    <input type="text" class="form-control" id="mobile" name="mobile" data-name="mobile" placeholder="Merchant mobile number" data-help="Provide seller mobile number to receive notification that payment been successfully secured pending delivery of good or service.<br><br>" data-validate="required" data-pattern="^[0]\d{10}$" data-patternError="Invalid mobile number please update.<br><br>" data-validateError="Provide valid data for seller mobile number.<br><br>">

                                </div>

                            </div>

                            <div class="form-group col-md-6 account_input">

                                <div class="input-group">

                                    <span class="input-group-addon"><i class="fa fa-building fa-fw"></i></span>

                                    <input type="text" class="form-control" id="account_number" name="account_number" data-name="account_number" placeholder="Account number" data-help="Provide seller account number.<br><br>Request for this information if your generating reservation on behalf of someone else.<br><br>" data-validate="required" data-pattern="^\d{10}$" data-patternError="Invalid account number please update.<br><br>" data-validateError="Please enter account number.<br><br>">

                                </div>

                            </div>

                            <div class="form-group col-md-6 bank_input">

                                <div class="input-group">

                                    <span class="input-group-addon"><i class="fa fa-university fa-fw"></i></span>

                                    <select class="form-control" id="bank_code" name="bank_code" data-name="bank_code" data-help="Select destination bank from dropdown for disbursement of payment to seller.<br><br>" data-validate="required" data-validateError="Please select bank.<br><br>">

                                        <option value="">Select bank</option>

                                        <?php

									foreach($row as $item)

									{

										echo "<option value='".$item[2]."'>".$item[1]."</option>";

									}

									?>

                                    </select>

                                </div>

                            </div>



                            <div class="form-group">

                                <div class="input-group">

                                    <span class="input-group-addon"><i class="fa fa-money fa-fw" aria-hidden="true"></i></span>

                                    <input type="text" class="form-control" id="amount" name="amount" data-name="amount" placeholder="Reservation amount" data-help="Provide amount agreed for good or service.<br><br>" data-validate="required" data-pattern="^(150000|[1]{1}[0-9]{3,5}(\.\d{1,2})?|[2-4]{1}[0-9]{2,5}(\.\d{1,2})?|[5-9]{1}[0-9]{2,4}(\.\d{1,2})?)$" data-patternError="You cannot make a reservation for an amount less than 200 or above 150,000<br>" data-validateError="Input amount value within accepted range.<br><br>">

                                </div>

                            </div>

                            <div class="form-group">

                                <div class="input-group">

                                    <span class="input-group-addon"><i class="fa fa-clock-o fa-fw"></i></span>

                                    <input type="text" class="form-control" id="duration" name="duration" data-name="duration" placeholder="Delivery duration" data-help="Provide agreed delivery duration for good or service in hours<br><br>Ensure this duration has been agreed with the seller.<br><br>" data-validate="required" data-pattern="^(0?[0-9]{1,2}|1[0-1][0-9]|120)$" data-patternError="Delivery duration cannot exceed 5 days please update.<br><br>" data-validateError="Provide duration in hours format.<br><br>">

                                </div>

                            </div>

                            <div class="form-group">

                                <div class="input-group">

                                    <span class="input-group-addon"><i class="fa fa-tag fa-fw"></i></span>

                                    <input type="text" class="form-control" id="payeemobile" name="payeemobile" data-name="payeemobile" placeholder="Reservation tag" data-help="Additional information provided by seller to uniquely identify this reservation.<br><br>" data-validate="required" data-pattern="^[a-zA-Z0-9]{1,10}$" data-patternError="Invalid reservation tag please update must be less than 11 characters.<br><br>" data-validateError="Please provide a reservation tag.<br><br>">

                                </div>

                            </div>

                            <div class="form-group">

                                <input type="button" class="btn form-control btn-md btn-danger" id="secure_form_btn" value="Generate Reservation" onclick="return ga('send','event','Reservation','Generation');" disabled="true"/>

                                <a href="javascript:void(0);" title=" " onclick="return ga('send', 'event','Reservation','Secure');" class="form-control btn hidden btn-danger" id="secure_url">Secure Fund</a>

                            </div>

                        </form>

                        <div class="stepwizard">

                            <div class="stepwizard-row setup-panel">

                                <div class="stepwizard-step">

                                    <a href="#step-1" type="button" class="btn btn-danger btn-circle">1</a>

                                    <p>Step 1</p>

                                </div>

                                <div class="stepwizard-step">

                                    <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>

                                    <p>Step 2</p>

                                </div>

                                <div class="stepwizard-step">

                                    <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>

                                    <p>Step 3</p>

                                </div>

                            </div>

                        </div>

                        <form role="form" id="secure_form_mobile" data-formpa="secure_form">

                            <input type="hidden" name="action" data-name="action" value="generate_URL"/>

                            <input type="hidden" name="key" data-name="key" value="<?php echo $key_verify ?>"/>

                            <div class="row setup-content" id="step-1">

                                <div class="col-xs-12">

                                    <div class="col-md-12">

                                        <div class="form-group">

                                            <div class="input-group">

                                                <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>

                                                <input required="required" type="text" class="form-control" id="email" name="email" data-name="email" placeholder="Merchant email address" data-help="Provide seller email address to receive notification that payment been successfully secured pending delivery of good or service.<br><br>" data-validate="required" data-pattern="^[\W\w]+@[\W\w]{3,}\.[\W\w]{2,3}$" data-patternError="Invalid email address please update.<br><br>" data-validateError="Provide valid data for seller email address.<br><br>">

                                            </div>

                                        </div>

                                        <div class="form-group">

                                            <div class="input-group">

                                                <span class="input-group-addon"><i class="fa fa-mobile fa-fw"></i></span>

                                                <input required="required" type="text" class="form-control" id="mobile" name="mobile" data-name="mobile" placeholder="Merchant mobile number" data-help="Provide seller mobile number to receive notification that payment been successfully secured pending delivery of good or service.<br><br>" data-validate="required" data-pattern="^[0]\d{10}$" data-patternError="Invalid mobile number please update.<br><br>" data-validateError="Provide valid data for seller mobile number.<br><br>">

                                            </div>

                                        </div>

                                        <button class="btn btn-danger nextBtn pull-right" type="button" >Next</button>

                                    </div>

                                </div>

                            </div>

                            <div class="row setup-content" id="step-2">

                                <div class="col-xs-12">

                                    <div class="col-md-12">

                                        <div class="form-group col-md-6 account_input">

                                            <div class="input-group">

                                                <span class="input-group-addon"><i class="fa fa-building fa-fw"></i></span>

                                                <input required="required" type="text" class="form-control" id="account_number" name="account_number" data-name="account_number" placeholder="Merchant account number" data-help="Provide seller account number.<br><br>Request for this information if your generating reservation on behalf of someone else.<br><br>" data-validate="required" data-pattern="^\d{10}$" data-patternError="Invalid account number please update.<br><br>" data-validateError="Please enter account number.<br><br>">

                                            </div>

                                        </div>

                                        <div class="form-group col-md-6 bank_input">

                                            <div class="input-group">

                                                <span class="input-group-addon"><i class="fa fa-university fa-fw"></i></span>

                                                <select required="required" class="form-control" id="bank_code" name="bank_code" data-name="bank_code" data-help="Select destination bank from dropdown for disbursement of payment to seller.<br><br>" data-validate="required" data-validateError="Please select bank.<br><br>">

                                                    <option value="">Select bank</option>

                                                     <?php

									foreach($row as $item)

									{

										echo "<option value='".$item[2]."'>".$item[1]."</option>";

									}

									?>

                                                </select>

                                            </div>

                                        </div>

                                        <div class="form-group">

                                            <div class="input-group">

                                                <span class="input-group-addon"><i class="fa fa-money fa-fw" aria-hidden="true"></i></span>

                                                <input type="text" required="required" class="form-control" id="amount" name="amount" data-name="amount" placeholder="Reservation amount" data-help="Provide amount agreed for good or service.<br><br>" data-validate="required" data-pattern="^(150000|[1]{1}[0-9]{3,5}(\.\d{1,2})?|[2-4]{1}[0-9]{2,5}(\.\d{1,2})?|[5-9]{1}[0-9]{2,4}(\.\d{1,2})?)$" data-patternError="You cannot make a reservation for an amount less than 200 and above 150,000<br>" data-validateError="Input amount value within accepted range<br><br>">

                                            </div>

                                        </div>

                                        <button class="btn btn-danger nextBtn pull-right" type="button" >Next</button>

                                    </div>

                                </div>

                            </div>

                            <div class="row setup-content" id="step-3">

                                <div class="col-xs-12">

                                    <div class="col-md-12">

                                        <div class="form-group">

                                            <div class="input-group">

                                                <span class="input-group-addon"><i class="fa fa-clock-o fa-fw"></i></span>

                                                <input type="text" class="form-control" id="duration" name="duration" data-name="duration" placeholder="Delivery duration" data-help="Provide agreed delivery duration for good or service in hours.<br><br>Ensure this duration has been agreed with the seller.<br><br>" data-validate="required" data-pattern="^(0?[0-9]{1,2}|1[0-1][0-9]|120)$" data-patternError="Delivery duration cannot exceed 5 days please update.<br><br>" data-validateError="Provide duration in hours format.<br><br>">

                                            </div>

                                        </div>

                                        <div class="form-group">

                                            <div class="input-group">

                                                <span class="input-group-addon"><i class="fa fa-tag fa-fw"></i></span>

                                                <input type="text" class="form-control" id="payeemobile" name="payeemobile" data-name="payeemobile" placeholder="Reservation tag" data-help="Additional information provided by seller to uniquely identify this reservation.<br><br>" data-validate="required" data-pattern="^[a-zA-Z0-9]{1,10}$" data-patternError="Invalid reservation tag please update must be less than 11 characters.<br><br>" data-validateError="Please provide a reservation tag.<br><br>">

                                            </div>

                                        </div>

                                        <div class="form-group">


                                            <input type="button" class="btn form-control btn-md btn-danger" id="secure_form_btn" value="Generate Reservation" onclick="return ga('send','event','Reservation','Generation');" disabled="true"/>

                                            <a href="javascript:void(0);" title="" onclick="return ga('send', 'event','Reservation','Secure');" class="form-control btn hidden btn-danger" id="secure_url">Secure Fund</a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </form>

                        <p class="display hidden"></p>

                    </div>

                    <div class="col-md-4 aside-div" style="height: 328px;">

                        <p class="secure_form_pa">Generate a reservation on behalf of your buyer and share via Instant Messaging when browsing from a mobile device.</p>

                        <div class="share-via hidden">

                            <p>Share via</p>

                            <ul>

                                <li class="whatsapp hidden"><a href="javascript:void(0);" data-action="share/whatsapp/share" onclick="return ga('send','event','Sharing','Watsapp'); clear()">whatsapp</a></li>

                                <li class="bbm hidden"><a href="javascript:void(0);" onclick="clear()">BBM</a></li>
                                
                                <li class="fb-messenger hidden"><a href="javascript:void(0);" onclick="return ga('send','event','Sharing','fb-messenger'); clear()">Share on FB messenger</a></li>

                                <li class="email hidden"><a href="javascript:void(0);" data-toggle="modal" data-target="#emailModal">Email</a></li>

                                <li class="facebook hidden"><a href="javascript:void(0);" onclick="return ga('send','event','Sharing','Facebook'); clear()">Share on Facebook</a></li>                           

                                <li class="clpbrd hidden"><a href="javascript:void(0);" onclick="return ga('send','event','Sharing','Clipboard');">Copy</a></li>

                            </ul>

                        </div>

                        
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php

include_once 'modal.php';

include_once 'footer.php';

?>









