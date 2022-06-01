<div class="alert alert-danger reservation-failed">
    <h2>Reservation Failed</h2>
    REASON: {$transaction->gateway_status_text}
</div>
<br/>
{*<p class="text-center">*}
{*Your Transaction Reference is {$transaction->gateway_ref}*}
{*</p>*}

<div class="text-left">
    {*<div class="row">*}
    {*<div class="col-sm-5">*}
    {*<strong>Amount Due</strong>*}
    {*</div>*}
    {*<div class="col-sm-7">*}
    {*{$transaction->amount}*}
    {*</div>*}
    {*</div>*}
    {*<div class="row">*}
    {*<div class="col-sm-5">*}
    {*<strong>Description</strong>*}
    {*</div>*}
    {*<div class="col-sm-7">*}
    {*{$transaction->memo}*}
    {*</div>*}
    {*</div>*}
</div>
{include file='_tx_return.tpl'}