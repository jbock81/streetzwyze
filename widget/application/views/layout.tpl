<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{if $page_title}{$page_title}-{/if}Streetzwyze | Secure exchange of goods and services</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <link rel="stylesheet" href="{assets_url}css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{assets_url}css/styles.css"/>
    <link rel="icon" href="{assets_url}favicon.png" type="image/png"/>
</head>
<body>

<div class="container payportal-body">
    <div class="row">
        <div class="col-lg-offset-3 col-md-offset-2 col-lg-6 col-md-8 col-sm-offset-2 col-sm-8">
            <div class="themed contentWrap vault-text">
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
    </div>
</div>
<script src="{assets_url}js/jquery.min.js"></script>
<script src="{assets_url}js/jquery.material.form.min.js"></script>
{literal}
    <script>
        $(function () {
            $('form.vault-form')
                    .addClass('material')
                    .materialForm();

            //send message.
            function heightWatch() {
                window.parent.postMessage({
                    type: 'size',
                    height: $(document.body).height() + 10
                }, '*');
            }

            heightWatch();
            document.body.addEventListener('resize', function(e){
                heightWatch();
            });
        });
    </script>
{/literal}
{captured_section name='footerScripts' global=true}
</body>
</html>
