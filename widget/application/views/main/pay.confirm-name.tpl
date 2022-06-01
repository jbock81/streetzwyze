<form class="form-horizontal vault-form" method="POST" action="{build_url action='confirm-name'}?rid={$reservation->id}"
      id="confirmForm">
    {if $error}
        <div class="alert alert-danger">
            {$error}
        </div>
    {/if}

    {if $reservation->hasCancelCharge()}
        <div class="error" style="font-weight: 700; text-transform: uppercase; font-size: 12px;">
            Non refundable fee of {$reservation->delivery_fee|currency}
        </div>
        <br/>
    {/if}
    {if $customer}
        {if $customer->CustomerLastName}
            <div class="well well-sm">
                {$customer->CustomerFirstName} {$customer->CustomerLastName}
            </div>
        {else}
            <div class="well well-sm">
                Welcome back!
            </div>
        {/if}
    {else}
        <div class="well well-sm">
            Welcome!
        </div>
    {/if}

    <div>
        <button class="btn btn-block btn-success btn-lg" name="action" value="confirmName" id="proceedToPayBtn">
            Proceed
        </button>
        <div class="help-block">
            <br/>
            <small>Clicking on proceed you confirm acceptance of the <a href="/legal.php" target="_blank">terms and conditions</a></small>
        </div>
    </div>
</form>
{capture_to_section name='footerScripts' global=true}
<script>
    $(function () {
        $('#proceedToPayBtn').on('click', function (e) {
            //e.preventDefault();
            //console.log('Posting size');
            window.parent.postMessage({
                type: 'size',
                height: 660,
                width: 530
            }, '*');
            //$('#confirmForm')[0].submit();
        });

    });

</script>
{/capture_to_section}
{include file='_footer.tpl'}