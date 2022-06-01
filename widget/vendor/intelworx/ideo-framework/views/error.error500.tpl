{strip}
    <div>
        <h2><span>internal</span> server error occured</h2>
        <p>{$error}</p>
        {if $showErrors}
            <pre style="padding: 5px; overflow: auto;">
<b>Message:</b>
<br/>
                {$message|html_entity_decode}
<br/>
<br/>
<b>Stack Trace:</b>
<br/>
                {$stackTrace|html_entity_decode}
            </pre>
        {/if}
    </div>
{/strip}