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
                                    <a href="{build_url action='test-payment-failed'}?rid={$reservation->id}" class="return-link">Return to Merchant Site </a>
                                </div>
                            </div>
                            <form method="POST" action="" id="testGatewayForm">
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
                                                        <p class="big-amount-txt" data-reservation-id="{$reservation['id']}">₦{$reservation['order_amount']}</p>
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
                                                Your card is not accepted by this merchant.
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label>Card Number</label>
                                                    <div class="ipg--icon--svg">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 125" width="30px" height="30px" enable-background="new 0 0 100 100" xml:space="preserve">
                                                        <path d="M89.3,18.7H10.9c-3.1,0-5.7,2.5-5.7,5.7V31v8.8V72c0,3.1,2.5,5.7,5.7,5.7h78.4c3.1,0,5.7-2.5,5.7-5.7V39.8V31v-6.6  C95,21.3,92.5,18.7,89.3,18.7z M91,72c0,0.9-0.8,1.7-1.7,1.7H10.9c-0.9,0-1.7-0.8-1.7-1.7V39.8H91V72z M9.3,31v-6.6  c0-0.9,0.8-1.7,1.7-1.7h78.4c0.9,0,1.7,0.8,1.7,1.7V31H9.3z" fill="#CCCCCC"></path>
                                                        </svg>
                                                    </div>
                                                    <input type="text" class="form-control" id="Cc" name="card-number" maxlength="16" data-validate="required" data-validateError="Fill in your card number" data-pattern="^\d{literal}{16}{/literal}$" data-patternError="Card number is incorrect">
                                                    <div class="card-type-container">
                                                        <img id="card-type-container-img" data-src="{assets_url}img/" data-cardType="" src="">
                                                    </div>
                                                    <span class="error"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <label>MM / YY</label>
                                                    <div class="ipg--icon--svg">
                                                        <svg width="25px" height="21px" viewBox="0.000000 -55.714352 27.857176 30.000036" xmlns="">
                                                        <path d="M2.142860 -27.857176L2.142860 -32.678610L6.964294 -32.678610L6.964294 -27.857176ZM8.035724 -27.857176L8.035724 -32.678610L13.392873 -32.678610L13.392873 -27.857176ZM2.142860 -33.750040L2.142860 -39.107189L6.964294 -39.107189L6.964294 -33.750040ZM8.035724 -33.750040L8.035724 -39.107189L13.392873 -39.107189L13.392873 -33.750040ZM2.142860 -40.178619L2.142860 -45.000054L6.964294 -45.000054L6.964294 -40.178619ZM14.464303 -27.857176L14.464303 -32.678610L19.821452 -32.678610L19.821452 -27.857176ZM8.035724 -40.178619L8.035724 -45.000054L13.392873 -45.000054L13.392873 -40.178619ZM20.892882 -27.857176L20.892882 -32.678610L25.714316 -32.678610L25.714316 -27.857176ZM14.464303 -33.750040L14.464303 -39.107189L19.821452 -39.107189L19.821452 -33.750040ZM8.571439 -48.214343C8.571439 -47.929745 8.320322 -47.678628 8.035724 -47.678628L6.964294 -47.678628C6.679695 -47.678628 6.428579 -47.929745 6.428579 -48.214343L6.428579 -53.035778C6.428579 -53.320376 6.679695 -53.571492 6.964294 -53.571492L8.035724 -53.571492C8.320322 -53.571492 8.571439 -53.320376 8.571439 -53.035778ZM20.892882 -33.750040L20.892882 -39.107189L25.714316 -39.107189L25.714316 -33.750040ZM14.464303 -40.178619L14.464303 -45.000054L19.821452 -45.000054L19.821452 -40.178619ZM20.892882 -40.178619L20.892882 -45.000054L25.714316 -45.000054L25.714316 -40.178619ZM21.428597 -48.214343C21.428597 -47.929745 21.177481 -47.678628 20.892882 -47.678628L19.821452 -47.678628C19.536854 -47.678628 19.285737 -47.929745 19.285737 -48.214343L19.285737 -53.035778C19.285737 -53.320376 19.536854 -53.571492 19.821452 -53.571492L20.892882 -53.571492C21.177481 -53.571492 21.428597 -53.320376 21.428597 -53.035778ZM27.857176 -49.285773C27.857176 -50.457649 26.886193 -51.428633 25.714316 -51.428633L23.571457 -51.428633L23.571457 -53.035778C23.571457 -54.508994 22.366098 -55.714352 20.892882 -55.714352L19.821452 -55.714352C18.348236 -55.714352 17.142878 -54.508994 17.142878 -53.035778L17.142878 -51.428633L10.714298 -51.428633L10.714298 -53.035778C10.714298 -54.508994 9.508940 -55.714352 8.035724 -55.714352L6.964294 -55.714352C5.491078 -55.714352 4.285719 -54.508994 4.285719 -53.035778L4.285719 -51.428633L2.142860 -51.428633C0.970983 -51.428633 0.000000 -50.457649 0.000000 -49.285773L0.000000 -27.857176C0.000000 -26.685300 0.970983 -25.714316 2.142860 -25.714316L25.714316 -25.714316C26.886193 -25.714316 27.857176 -26.685300 27.857176 -27.857176ZM27.857176 -49.285773" fill="#CCCCCC"></path>
                                                        </svg>
                                                    </div>
                                                    <input type="text" class="form-control" id="CCexpiry" name="cc-expiry" maxlength="7" data-validate="required" data-validateError="Card Expiry is required" data-pattern="^(?:[0][1-9]|[1][0-2]) / [0-9][0-9]$" data-patternError="Card expired">
                                                    <span class="error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <label>CVV</label>
                                                    <div class="ipg--icon--svg">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" width="30px" height="28px" viewBox="0 0 100 125" enable-background="new 0 0 100 100" xml:space="preserve"><path d="M78.1,79.4V52c0-3.8-3.1-7-7-7h-0.7v-11c0-11.3-9.2-20.5-20.5-20.5c-11.3,0-20.5,9.2-20.5,20.5v11h-0.7c-3.8,0-7,3.1-7,7  v27.4c0,3.8,3.1,7,7,7h42.2C75,86.4,78.1,83.2,78.1,79.4z M33.8,34.1c0-8.9,7.3-16.2,16.2-16.2s16.2,7.3,16.2,16.2v11H33.8V34.1z   M26.1,79.4V52c0-1.5,1.2-2.7,2.7-2.7h42.2c1.5,0,2.7,1.2,2.7,2.7v27.4c0,1.5-1.2,2.7-2.7,2.7H28.9C27.4,82.1,26.1,80.9,26.1,79.4z   M54.1,63.1c0,1.4-0.5,2.5-1.7,3.3c-0.3,0.2-0.4,0.5-0.4,0.9c0,1.8,0,3.5,0,5.3v0c0,0.7-0.3,1.4-1,1.8C49.5,75.1,48,74,48,72.6  c0,0,0,0,0,0c0-1.8,0-3.5,0-5.3c0-0.4-0.1-0.6-0.4-0.8c-1.6-1.2-2.1-3.2-1.3-4.9c0.8-1.7,2.7-2.7,4.5-2.4  C52.7,59.5,54.1,61.1,54.1,63.1z" fill="#CCCCCC"></path>
                                                        </svg>
                                                    </div>
                                                    <input type="password" class="form-control" id="Cvv" name="cc-cvv" maxlength="3" data-validate="required" data-validateError="Invalid CVV" data-pattern="^\d{literal}{3}{/literal}$" data-patternError="Invalid CVV">
                                                    <span class="error"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label> Card Pin</label>
                                                    <input type="password" class="form-control text-align-center" id="Pin" name="cc-pin" maxlength="4" readonly data-validate="required" data-validateError="Invalid Pin" data-pattern="^\d{literal}{4}{/literal}$" data-patternError="Invalid Pin">
                                                    <span class="error"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row hidden">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="virtual-keyboard">
                                                    <button class="small-keypay-bt" type="button" data-key="3"> 3</button>
                                                    <button class="small-keypay-bt" type="button" data-key="8"> 8</button>
                                                    <button class="small-keypay-bt" type="button" data-key="2"> 2</button>
                                                    <button class="small-keypay-bt" type="button" data-key="5"> 5</button>
                                                    <button class="small-keypay-bt" type="button" data-key="1"> 1</button>
                                                    <button class="small-keypay-bt" type="button" data-key="9"> 9</button>
                                                    <button class="small-keypay-bt" type="button" data-key="6"> 6</button>
                                                    <button class="small-keypay-bt" type="button" data-key="4"> 4</button>
                                                    <button class="small-keypay-bt" type="button" data-key="0"> 0</button>
                                                    <button class="small-keypay-bt" type="button" data-key="7"> 7</button>
                                                    <button class="small-keypay-bt" type="button" data-key="del"> DEL</button>
                                                    <button class="small-keypay-bt" type="button" data-key="clr"> CLR</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row btn-row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden"  name="action" value="otpInput"/>
                                                <button class="pay-bt" id="test-pay-bt" type="button">Pay</button>
                                            </div>
                                        </div>
                                        <div class="or-divider">
                                            <span class="or-text">or</span>
                                        </div>
                                        <div class="row btn-row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <button class="pay-bt verve-ewallet-btn" type="button">Login to Verve eWallet</button>
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