<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 

?>

<div id="emailModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form id="email_form" data-formpa="email_form">
                    <input type="hidden" name="action" data-name="action" value="send_mail"/>
                    <input type="hidden" id="fund_url" name="fund_url" data-name="fund_url" value=""/>
                    <input type="hidden" name="key" data-name="key" value="<?php echo $key_verify ?>"/>
                    <div class="form-group">
                        <div class="input-group">
                            <label for="from_email" class="control-label input-group-addon">From:</label>
                            <input type="text" class="form-control" id="from_email" name="from_email" data-name="from_email" readonly="readonly" data-validate="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <label for="to_email" class="control-label input-group-addon to_label">To:</label>
                            <input type="text" class="form-control" id="to_email" placeholder="Email address of recipient" name="to_email" data-name="to_email" data-help="Enter email address of recipients.<br><br>" data-validate="required" data-pattern="^[\W\w]+@[\W\w]{3,}\.[\W\w]{2,3}$" data-patternError="Invalid email please update.<br><br>" data-validateError="Please enter email.<br><br>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-control email-message">
                            <p>Hi,<br><br>You are receiving this mail because a reservation was made on your behalf on <a href="https://streetzwyze.com" target="_blank">Streetzwyze</a>. Please click <a href="javascript:void(0)" id="payment_url" target="_blank">here</a> to secure funds for this purchase.<br><br>Seller will receive notification once payment is completed and commerce delivery process. Sit back, relax and confirm delivery via communicating code to release payment.<br><br>Enjoy! and share with others,<br><br>Streetzwyze</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="button" class="btn form-control" id="email_form_btn" value="Send Email" disabled="true"/>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>