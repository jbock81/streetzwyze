<div id="page_content">
    <h2>Page not found</h2>
    <p>
        <i class="icon-info-sign"></i> The page you're looking for was not found. Please try again later.
    </p>
    {if $showErrors}
        <p align="center"><strong>ERROR: </strong> {$error_type}</p>
        <pre>
Params: {$request}
        </pre>
    {/if}
</div>