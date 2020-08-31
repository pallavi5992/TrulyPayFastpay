<?php
$link = $this->uri->segments[2];
$Regex = All_Regex();
$userDetails = get_user_details();

?>   
    <section class="width-100 money-remmitance-section mt-20">
    <div class="container">
    <div class="row">
    <div class="col-12">
    <div class="money-remmitance-section-outer-col width-100">
    <div class="width-100 section-top-subheading mb-3"><h6 class="dark-txt fontbold"><?php echo $link; ?></h6></div> 
    <!-- Content area -->
    <div class="content">
        <!--start of section-->
        <div class="row mt-15">
            <div class="col-lg-12">
                <section class="transaction-history-wrp">
                    <div class="panel-body">
                        <div class="col-lg-12" style="margin-bottom: 15px;">
                           <form id="pyment_req_form">
                            <div class="form-wrp"> 
                                <div class="row">
                                    <div class="col-md-12" id="SelectMenu">
                                        <div class="form-group">
                                            <label>Subject<span class="mandotry">*</span></label>
                                            <input maxlength="100" type="text" class="form-control input-lg Subject" placeholder="Enter The Subject" id="FeedbackRequest_Subject">
                                            <span data-for="FeedbackRequest_Subject"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Feedback<span class="mandotry">*</span></label>
                                            <textarea maxlength="500" name="Feedback" rows="6" placeholder="Enter The Feedback" class="form-control input-xs Feedback" id="FeedbackRequest_Feedback"></textarea>
                                            <span data-for="FeedbackRequest_Feedback"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group has-feedback text-center">
                                            <button type="submit" class="btn btn-primary btn-lg FeedbackRequest" style="margin-top: 26px;width: 150px;" id="FeedbackRequest">Submit</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                        </div>

                        
                    </div>
                </section>
            </div>
        </div>
        <!--end of section-->
    </div>
    <!-- /content area -->


                    </div>
                </div>
            </div>
        </div>
    </section>


<script>
 var FeedbackRequest = function () {
    
    var Regex = <?php echo json_encode($Regex); ?>;

    
     
        var bankOpr = {};

        
        

        $('#FeedbackRequest').click(function (e) {
            e.preventDefault();

            var params = {'valid': true};
            var actid = $(this).attr('id');

            var data = new FormData();

            params.Subject = $('#' + actid + '_Subject').val();
            params.Feedback = $('#' + actid + '_Feedback').val();
            

            if (!validate({'id': '' + actid + '_Subject', 'type': 'TEXT', 'data': params.Subject, 'error': true, msg: $('#' + actid + '_Subject').attr('placeholder')})) {
                params.valid = false;
            }

            if (!validate({'id': '' + actid + '_Feedback', 'type': 'TEXT', 'data': params.Feedback, 'error': true, msg: $('#' + actid + '_Feedback').attr('placeholder')})) {
                params.valid = false;
            }


            if (params.valid == true) {
                $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                var la = $(this).ladda();
                la.ladda('start');

                data.append('Subject', params.Subject);
                data.append('Feedback', params.Feedback);

                $.ajax({
                    method: 'POST',
                    url: 'Dashboard/FeedbackPost',
                    data: data,
                    dataType: 'JSON',
                    cache: false,
                    processData: false, // Don't process the files
                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                }).done(function (response) {
                    if (response){
                        if (response.error == 1){
                            toastr.error(response.error_desc);
                        } else if (response.error == 2){
                            window.location.reload(true);
                        } else if (response.error == 0) {
                            $(".Subject, .Feedback").val('');
                            toastr.success(response.msg);
                        }
                        la.ladda('stop');
                    }
                });
            }
        });

    

        var validate = function (p){
            if (typeof p === 'undefined') {
                return false;
            }
            if (typeof p.id === 'undefined') {
                p.id = '';
            }
            if (typeof p.data === 'undefined') {
                p.data = '';
            }
            if (typeof p.type === 'undefined') {
                p.type = '';
            }
            if (typeof p.error === 'undefined') {
                p.error = false;
            }
            if (typeof p.msg === 'undefined') {
                p.msg = false;
            }

            if (p.type == 'TEXT') {
                var _identifier_regex = Regex.Text;
                var _mobile = new RegExp(_identifier_regex);
                if (_mobile.test(p.data)){
                    if (p.error == true && (p.data == ''))
                    {
                        helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                    } else
                    {
                        helpBlck({id: p.id, 'action': 'remove'});
                        return true;
                    }
                } else{
                    if (p.error == true) {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
                        }
                    }
                }
            }
            return false;
        }

       var helpBlck = function (h) {
            if (typeof h !== 'undefined')
            {
                if (h.action == 'remove')
                {
                    if (typeof h.id === 'undefined') {
                        $('span.help-block').html('').removeClass('text-info');
                        $('span.help-block').each(function () {
                            $(this).closest('.form-group').removeClass('text-danger');
                        })
                    } else {
                        if ($('span[data-for=' + h.id + ']').closest('.form-group').hasClass('text-danger')) {
                            $('span[data-for=' + h.id + ']').closest('.form-group').removeClass('text-danger');
                            $('span[data-for=' + h.id + ']').html('').removeClass('text-info');
                        }
                    }
                }

                if (typeof h.type === 'undefined')
                {
                    h.type = '';
                }

                if (typeof h.id !== 'undefined')
                {
                    if (typeof h.msg !== 'undefined')
                    {
                        if (h.type == 'error')
                        {
                            $('span[data-for=' + h.id + ']').closest('.form-group').addClass('text-danger');
                            $('span[data-for=' + h.id + ']').html(h.msg).removeClass('text-info');
                        }
                        else if (h.type == 'bulk')
                        {
                            $('span[data-bulk=' + h.id + ']').closest('.form-group').removeClass('text-danger');
                            $('span[data-bulk=' + h.id + ']').html(h.msg).addClass('text-info');
                        }
                        else
                        {
                            $('span[data-for=' + h.id + ']').closest('.form-group').removeClass('text-danger');
                            $('span[data-for=' + h.id + ']').html(h.msg).addClass('text-info');
                        }



                    }
                }
            }
        }

 return {
            init: function () {
                 // bankNameDefined();
                 // KeyPress_Validation();
            }
        };
    }();   
    $(document).ready(function () {
        FeedbackRequest.init();
    });
</script>
