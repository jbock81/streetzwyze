<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta content="IE=9" http-equiv="X-UA-Compatible" />
        <meta content="width=device-width,initial-scale=1,maximum-scale=1" name="viewport" />
        <title>{if $page_title}{$page_title}-{/if}Cashvault - Distribution Payment Service</title>
        <link rel="icon" href="{assets_url}favicon.png" type="image/png"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{assets_url}css/bootstrap.min.css"/>
        <link rel="stylesheet" href="{assets_url}css/styles.css"/>
    </head>
    <body class="test-gateway">
        <div id="mainContent" onClick="">
            <section class="payment-form">
                <div class="container">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-1-5">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <a href="{build_url action='test-payment-failed'}?rid={$reservation->id}" class="return-link">Return to Merchant Site</a>
                                </div>
                            </div>
                            <form method="POST" action="{build_url action='otp-input'}?rid={$reservation->id}" id="testOTPForm">
                                <div class="form-box">
                                    <div class="form-head">
                                        <div class="row">
                                            <div class="col-md-8 col-sm-8 col-xs-8">
                                                <div class="search-bt-name">
                                                    <div class="search-bt">
                                                        <img src="{assets_url}favicon.png" alt="logo"/>
                                                    </div>
                                                    <div class="amount-txt">
                                                        <p class="sm-txt">Staging</p>
                                                        <p class="big-amount-txt">₦{$reservation['order_amount']}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-4">
                                                <div class="circle-box pull-right">
                                                    <p>CV</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-body">
                                        <div class="alert alert--error hidden">
                                            <div class="alert--icon">
                                                <svg viewBox="0 0 23 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                                                <g id="Alternative-" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                                                <g id="Card_Error-State" sketch:type="MSArtboardGroup" transform="translate(-96.000000, -42.000000)">
                                                <g id="Error-Alert_boxed-Ico" sketch:type="MSLayerGroup" transform="translate(85.000000, 28.000000)">
                                                <g id="warning-copy-2" transform="translate(12.000000, 14.000000)" sketch:type="MSShapeGroup">
                                                <path d="M0,17.84 L20.8,17.84 L10.4,0 L0,17.84 L0,17.84 Z" id="Shape" fill="#FFFFFF"></path>
                                                <path d="M11.3454545,15.0231579 L9.45454545,15.0231579 L9.45454545,13.1452632 L11.3454545,13.1452632 L11.3454545,15.0231579 L11.3454545,15.0231579 Z" id="Path" fill="#FD6B6B"></path>
                                                <path d="M11.3454545,11.2673684 L9.45454545,11.2673684 L9.45454545,7.51157895 L11.3454545,7.51157895 L11.3454545,11.2673684 L11.3454545,11.2673684 Z" id="Path" fill="#FD6B6B"></path>
                                                </g>
                                                </g>
                                                </g>
                                                </g>
                                                </svg>
                                            </div>
                                            <div class="alert--msg">
                                                This is not a valid OTP.
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <p class="otp-send-message">Kindly enter the OTP sent to {substr($reservation['customer_mobile_no'],0,4)}****{substr($reservation['customer_mobile_no'],9,11)}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label>Enter your One-Time PIN</label>
                                                    <input type="text" class="form-control text-align-center" maxlength="6" id='OTP' name='otp' data-validate="required" data-validateError="Fill in the OTP" data-pattern="^\d{literal}{6}{/literal}$" data-patternError="Invalid OTP">
                                                    <span class="error"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row btn-row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="get-otp-box form-group">
                                                    <p class="border-bt">Did not get OTP?</p>
                                                    <div class="row btn-row">
                                                        <div class="col-md-6 col-sm-6 col-xs-5">
                                                            <button type="button" class="resnd-otp">
                                                                <svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"></path>
                                                                <path d="M0 0h24v24H0z" fill="none"></path>
                                                                </svg>
                                                                Resend OTP</button>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6 col-xs-7 border-left">
                                                            <p>On your phone dial <span class="big-txt">*322*0#</span><span class="small-txt">(Airtel, Etisalat, Glo, MTN)</span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mar-top text-center">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <p><a href="#" style="color:#aaa; font-size: 15px; text-decoration: underline;">Other ways to get OTP?</a></p>
                                            </div>
                                        </div>
                                        <div class="row btn-row">
                                            <div class="col-md-5 col-sm-5 col-xs-5">
                                                <button class="back-bt" type="button">Back</button>
                                            </div>
                                            <div class="col-md-7 col-sm-7 col-xs-7">
                                                <input type="hidden" name="action" value="testPaymentSuccess" />
                                                <button class="pay-bt" id="otp-input-bt" type="button" >Continue</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                    <a href="#" class="return-link1"><img src="{assets_url}img/interswitch_logo.png"/> </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                    <a href="#" class="return-link"><img src="{assets_url}img/verified.png"/> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <script src="{assets_url}js/jquery.min.js"></script>
            <script src="{assets_url}js/testgateway.js"></script>
            <script src="{assets_url}plugin.min.js"></script>
        </div>
    </body>
</html>