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
    <div class="width-100 section-top-subheading mb-3"><h6 class="dark-txt fontbold"><?php echo $link; ?> List</h6></div> 
    <!-- Content area -->
    <div class="content">
        <!--start of section-->
        <div class="row mt-15">
            <div class="col-lg-12">
                <section class="transaction-history-wrp">
                    <div class="panel-body">
                        <div class="col-lg-12">
                            <div class="col-lg-12 transaction-history-wrp">
                                <section>
                                    <table class="table datatable-basic"  id="FeedbackPostList"></table>   
                                </section>
                            </div>
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
 var FeedbackPost = function () {
    
    var Regex = <?php echo json_encode($Regex); ?>

    
        

        var FeedbackPostUserList = function () {

        var Datatable = $('#FeedbackPostList').DataTable({
                "processing": true,
                "ajax": {
                    url: "Dashboard/SuggestionPostList",
                    type: 'post',
                    "dataSrc": function (json) {
                        if (json.error_data == 2){
                            window.location.reload(true);
                        } else if (json.error_data == 1){
                            toastr.error(json.error_desc, 'Oops!');
                        }
                        return json.data;
                    }
                },
                // responsive: true,
                // order: [],
                columns: [
                    {title: 'UserID', class: '',
                        render: function (data, type, full, meta) {
                            return full.user_id;
                        }
                    },

                    {title: 'User Name', class: '',
                        render: function (data, type, full, meta) {
                            return full.first_name+' '+full.last_name + ' (' + full.role_name + ')';
                        }
                    },

                    {title : "Subject",data: "subject"},

                    {title : "Feedback",data: "feedback"},

                    {title : "Posted On",data: "posted_on"},

                ],
              
            });
             $('.datatables tbody').on('click', 'td.details-control', function () {
             var tr  = $(this).closest('tr'),
                 row = table.row(tr);
            
             if (row.child.isShown()) {
               tr.next('tr').removeClass('details-row');
               row.child.hide();
               tr.removeClass('details');
             }
             else {
               row.child(format(row.data())).show();
               tr.next('tr').addClass('details-row');
               tr.addClass('details');
             }
          }); 

        }
       

        return {
            init: function () {
                FeedbackPostUserList();
            }
        };
    }();   
    $(document).ready(function () {
        FeedbackPost.init();
    });
</script>
