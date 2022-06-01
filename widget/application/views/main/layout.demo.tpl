<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{if $page_title}{$page_title}-{/if}Cashvault - Distribution Payment Service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <link rel="stylesheet" href="{assets_url}css/bootstrap-flat.min.css"/>
    <link rel="stylesheet" href="{assets_url}css/styles.css"/>
    <link rel="icon" href="{assets_url}favicon.png" type="image/png"/>
</head>
<body>

<div class="container payportal-body">
    <div class="themed contentWrap">
        <div id="mainContent">
            <div class="logo text-center">
                <img src="{assets_url}img/logo.png"/>
            </div>
            <div class="clearfix"></div>
            <div>
                {$MAIN_CONTENT}
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>


<script src="{assets_url}js/jquery.min.js"></script>
{captured_section name='footerScripts' global=true}
</body>
</html>
