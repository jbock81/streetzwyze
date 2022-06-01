<link href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.9.1/styles/github.min.css" rel="stylesheet"
      media="screen"/>
<div>
    {if !empty($responseData)}
        <div class="code-wrap">
            <strong>Callback Response</strong>
            <pre>
                <code>
                    {$responseData|http_build_query}
                </code>
            </pre>
        </div>
    {/if}

    <h2>Plugin Demo</h2>

    <p>
        This page shows a demo of the JS plugin, which can be used to easily display payment page as a pop-up.
    </p>

    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            {include file='_plugin.tpl'}
        </div>
    </div>

    <section>
        <h2>Source Code for Example</h2>

        <div class="code-wrap">
            {strip}
                <pre>
                <code class="html">
                    {$sourceCode}
                </code>
            </pre>
            {/strip}
        </div>
    </section>
</div>
{capture_to_section name='footerScripts' global=true}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.9.1/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
{/capture_to_section}