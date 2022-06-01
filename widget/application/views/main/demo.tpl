<div class="code-wrap" style="display: none;">
    <strong>Response</strong>
    <pre id="response" style="font-size: 0.8em;"></pre>
    <p>
        <a id="payUrl" class="btn btn-success btn-block btn-sm"></a>
    </p>

    <p>
        <a id="pluginUrl" class="btn btn-danger btn-block btn-sm"></a>
    </p>
</div>
<form class="vault-form" id="demoForm">
    <div class="form-group">
        <textarea class="form-control" id="messageContent" placeholder="Request Body" rows="8"
                  style="font-size: 1.0em;"></textarea>
    </div>

    <div class="form-group">
        <input type="url" id="callBackUrl" placeholder="Return URL" class="form-control"/>
    </div>

    <div>
        <button class="btn btn-block btn-primary" type="submit">
            Submit
        </button>
    </div>
</form>
{capture_to_section name='footerScripts' global=true}
{literal}
    <script>
        $(function () {
            $('#demoForm').submit(function (e) {
                e.preventDefault();
                var data = $('#messageContent').val().trim();
                var rUrl = $('#callBackUrl').val().trim();
                if (data.length < 1) {
                    alert('Data body is empty.');
                    return false;
                }

                var showResult = function (data, url) {
                    $('.code-wrap').show();
                    $('form input, form button, form textarea').removeAttr('disabled');
                    $('.code-wrap pre').html(JSON.stringify(data, null, '  '));
                    if (url) {
                        $('.code-wrap a').show();
                        $('.code-wrap #payUrl').attr('href', url)
                                .html('Click Here to Complete Payment');

                        $('.code-wrap #pluginUrl').attr('href', url.replace('/pay?', '/demo/plugin?'))
                                .html('Click Here to Use Plugin');

                    } else {
                        $('.code-wrap a').hide();
                    }
                };

                $('.code-wrap').hide();
                $('form input, form button, form textarea').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "/plugin/reservation",
                    data: data,
                    contentType: "application/json",
                    dataType: "json",
                    success: function (data) {
                        showResult(data, '/plugin/pay?rid=' + data.ReservationId + '&returnUrl=' + encodeURIComponent(rUrl));
                    },
                    error: function (errMsg) {
                        showResult(errMsg.responseJSON)
                    }
                });


            });
        });
    </script>
{/literal}
{/capture_to_section}