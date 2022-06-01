<form class="form-horizontal vault-form" method="POST" action="">
    {if $error}
        <div class="alert alert-danger">
            {$error}
        </div>
    {/if}

    <div class="form-group">
        <div class="col-md-12">
            <input type="text" pattern="^\d+$" class="form-control" name="mobile_number" placeholder="Mobile Number" required="" value="{$data.mobile_number}"/>
        </div>
    </div>

    <div>
        <button class="btn btn-block btn-success btn-lg" name="action" value="begin">
            Continue
        </button>
    </div>
</form>
{include file='_footer.tpl'}