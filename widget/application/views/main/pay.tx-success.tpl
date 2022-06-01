<form class="form-horizontal vault-form" method="POST" action="">
    {if $error}
        <div class="alert alert-danger">
            {$error}
        </div>
    {/if}

    {if !$reservation->hasCustomerInfo()}
        <p>
            {*Just a few more details and we are done*}
        </p>
    {else}
        <p style="color: #16a085; font-weight: 700; text-transform: uppercase; font-size: 12px;">
            We noticed payment was with a new card
        </p>
    {/if}

    {if !$reservation->hasCustomerInfo()}
        <div>
            <div class="form-group">
                <div class="col-md-12">
                    <input class="form-control" name="customer[email]" type="email" placeholder="E-Mail Address"
                           required="" value="{$data.customer.email}"/>
                </div>
            </div>
        </div>
    {/if}

    {if $transaction->isNewBank()}
        <div>
            <div class="form-group">
                <div class="col-md-12">
                    <input type="text" class="form-control" value="{$data.bank.account}" name="bank[account]"
                           placeholder="Account Number for reversal"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <div class="form-control-static text-left" style="font-weight: bold; line-height: 50px;">
                        <img src="{assets_url}img/logos/{$transaction->bank->qt_code|lower}.png"
                             style="max-width: 100%; height: 50px; float: right;">
                        {$transaction->bank->getName()}
                    </div>

                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    {/if}
    <div>
        <button class="btn btn-block btn-success btn-lg" name="action" value="complete" type="submit">
           Secure Fund
        </button>
    </div>
</form>

