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
            <section class="payment-form mar-top">
                <div class="container">
                    <div class="row mar-top">
                        <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-1-5">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                </div>
                            </div>
                            <form id="testPaymentSuccessForm" action="{build_url action='test-payment-success'}?rid={$reservation->id}" method="POST">
                                <div class="form-box mar-top">
                                    <div class="form-body">
                                        <div class="form-group text-center">
                                            <h4 class="green "><i class="fa fa-check" aria-hidden="true"></i></h4>
                                            <h5> Transaction successful</h5>
                                            <p>Returning to Merchant</p>
                                            <div id="countdown">
                                                <div id="countdown-number"></div>
                                                <svg>
                                                <circle r="18" cx="20" cy="20"></circle>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="row btn-row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="action" value="PaymentSuccessRedirect"/>
                                                <button class="pay-bt mar-top" type="button"> Return to Merchant</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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