<div class="alert alert-success">
    <h2 style="font-weight: bold; font-size: 18px; margin: 4px">Secured</h2>
</div>

<p class="text-center">
    {$dur=$transaction->reservation->delivery_duration|delivery_duration}
    
    Delivery duration is<strong> {$transaction->reservation->delivery_duration|delivery_duration}</strong> and code to confirm purchase receipt on its way
</p>

<br/>

{include file='_tx_return.tpl'}