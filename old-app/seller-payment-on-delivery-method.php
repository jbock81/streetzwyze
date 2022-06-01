<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'header.php';
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

    .sectionHero-upsell-list li {
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

<section id="validate">
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
                              <span class="sectionHero-upsell-text">Purchase <br> Item validation</span>
                            </span>
                            <div class="sectionHero-steps">
                              <span class="sectionHero-steps-decorator"></span>
                              <ol class="sectionHero-upsell-list">
                                  <li class="sectionHero-upsell-item is-active">
                                    <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg"><path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path></svg>
                                    <span class="sectionHero-upsell-desc">
                                      Retrieve active reservation
                                    </span>
                                  </li>
                                  <li class="sectionHero-upsell-item">
                                    <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg"><path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path></svg>
                                    <span class="sectionHero-upsell-desc">
                                      Buyer inspects good or service
                                    </span>
                                  </li>
                                  <li class="sectionHero-upsell-item">
                                    <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg"><path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path></svg>
                                    <span class="sectionHero-upsell-desc">
                                      Buyer approves
                                    </span>
                                  </li>
                                  <li class="sectionHero-upsell-item">
                                    <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg"><path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path></svg>
                                    <span class="sectionHero-upsell-desc">
                                      Request for code
                                    </span>
                                  </li>
                                  <li class="sectionHero-upsell-item">
                                    <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg"><path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path></svg>
                                    <span class="sectionHero-upsell-desc">
                                      Confirm account deposit
                                    </span>
                                  </li>
                              </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-sm-12 ">
                <div class="row">
                    <div class="col-md-8" id="fd">
                        <div class="stepwizard">
                            <div class="stepwizard-row setup-panel">
                                <div class="stepwizard-step">
                                    <a href="#step-1" type="button" class="btn btn-danger btn-circle">1</a>
                                    <p>Step 1</p>
                                </div>
                            </div>
                        </div>    
                        <form id="validate_form" data-formpa="validate_form">
                            <input type="hidden" name="action" data-name="action" value="validate_URL"/>
                            <input type="hidden" id="key" name="key" data-name="key" value="<?php echo $key_verify ?>"/>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-mobile fa-fw"></i></span>
                                    <input type="text" class="form-control" id="validate_number" placeholder="Merchant mobile number" name="validate_number" data-name="validate_number" placeholder="Enter Mobile number" data-help="Provide seller mobile number to retrieve active reservations.<br><br>" data-validate="required" data-pattern="^[0]\d{10}$" data-patternError="Invalid mobile number please update.<br><br>" data-validateError="Please enter seller mobile number.<br><br>">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="button" class="btn form-control" id="validate_form_btn" value="Retrieve" disabled="true"/>
                            </div>
                        </form>
                        <div class="validation">

                        </div>
                        <div class="vault">
                        	
                        </div>
                    </div>
                    <div class="col-md-4 aside-div" id="sd">
                        <p class="validate_form_pa vc_form_pa">Provide seller mobile number to retrieve active reservation for purchase</p>
                    </div>
                    <form id="vc_form-1" style="display: none" data-formpa="validate_form"><div class="form-inline input-group"><input name="action" data-name="action" type="hidden" value="vault_code"><input name="key" data-name="key" type="hidden" value="a5e264029893b19289ddafeee3edf74f"><input class="form-control" name="amt" data-name="amt" type="text" value="500000"><input class="form-control time" name="time" readonly="readonly" data-name="time" type="text" value="120"><input class="form-control vc" id="vault_code" name="vault_code" placeholder="Code" data-name="vault_code" type="text" data-help="Enter 6 digit code." data-validate="required" data-pattern="^\d{6}$" data-patternerror="Invalid code. Please update" data-validateerror="Please enter code">
                    <input type="text" class="form-control" placeholder="Reservaton Tag" name="">
                    <input class="btn form-control" id="vc_form_btn" value="Send" type="button" disabled="disabled"></div></form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include_once 'footer.php'; ?>

