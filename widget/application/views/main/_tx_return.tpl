{if $transaction}
    {$reservation=$transaction->reservation}
    <div class="row">
        <!--<div class="col-sm-10 col-sm-offset-1">-->
        <div class="col-xs-12 ">
            <button type="button" class="btn btn-success btn-lg btn-block" id="returnBtn">
                Close
            </button>
        </div>
    </div>
    <script>
        (function () {
            var transactionData = {
                ReservationID: "{$transaction->reservation_id}",
                ResponseCode: "{$transaction->gateway_status_code}",
                ResponseDescription: "{$transaction->gateway_status_text}",
            };

            var returnUrl = '{if $reservation->site_return_url}{$reservation->site_return_url}{else}{build_url controller='pay' action='default'}?rid={$reservation->id}{/if}';

            document.getElementById('returnBtn').addEventListener('click', function () {
                var form = document.createElement('form');
                form.setAttribute('target', '_top');
                form.setAttribute('method', 'POST');
                form.setAttribute('name', 'merchantAlert');
                form.setAttribute('action', returnUrl);
                for (var i in transactionData) {
                    if (transactionData.hasOwnProperty(i)) {
                        form.innerHTML += '<input type="hidden" name="' + i + '" value="' + transactionData[i] + '"/>';
                    }
                }
                document.body.appendChild(form);
                form.submit();
            });
        })();

    </script>
{else}
    <div class="alert alert-danger">
        Transaction was not found, you should not be seeing this, please contact support.
    </div>
{/if}