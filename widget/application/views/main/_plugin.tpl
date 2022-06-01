<!-- Should be included once on your page, can be included anywhere -->
<script src="{assets_url}plugin.min.js"></script>

<!--
This is a clickable element, preferably an <a> or <button> element. You
 The allowed data-* attributes are as follows:
 - data-reservation-id: Required. The Reservation ID for the already requested reservation, must be done by making a
 POST call
  to {build_url controller='reservation' action='default'}
 - data-return-url : Optional. The callback URL where the user wil be redirected on successful or failed payment attempt
 - data-auto-show : Optional. Set to `true` or `false`, true implies the payment pop-up will be automatically
 displayed, false implies the user will have to click this element to display popup
-->
<a href="#" class="btn btn-success" data-reservation-id="{$reservationId}" data-return-url="{$returnUrl}">
    <!-- can be any text --->
    <i class="glyphicon glyphicon-shopping-cart"></i> Pay with Cashvault
</a>