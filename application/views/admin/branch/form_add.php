 <form action="<?php echo base_url(); ?>leads/lead/action_payment/<?php echo $this->uri->segment(4); ?>" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>From Email&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="email" required class="form-control" placeholder="from" readonly="" value="<?php echo $this->auth_email; ?>" name="from_email">
                                        </div>
                                        <div class="form-group">
                                            <label>To Email (Customer)&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="email" required class="form-control" placeholder="from" readonly="" value="<?php echo $lead_details['customer_email']; ?>" name="customer_email">
                                            <input type="hidden" name="agent_email" value="<?php echo $this->auth_email; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Subject&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" required class="form-control" placeholder="from" value="ONTIME - Followup regarding - Payment Link'; ?>" name="email_subject">
                                        </div>

                                        <div class="form-group">
                                            <label>Body&nbsp;<span class="text-danger required">*</span></label>
                                            <textarea rows="10" class="form-control" name="email_message" id="email_editor2"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Amount&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="number" required class="form-control" placeholder="AED" step="0.01" name="amount_payment">
                                        </div>
                                        <div class="form-group">
                                            <label>Remarks for your reference (Optional)</label>
                                            <textarea rows="3" class="form-control" name="email_remarks" id="editor2"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Next Contactable Date&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" id="email_date" name="contactable_date" class="form-control" placeholder="" value="<?php echo date('Y-m-d H:i:s', time() + 86400); ?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" name="action_payment" class="btn btn-primary btn-block" value="SEND EMAIL">
                                        </div>
                                    </div>
                                </div>
                            </form>
                       