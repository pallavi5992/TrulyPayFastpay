<!-- begin:: Content -->
<?php
$user_data = get_user_details();
?>
<div class="col-lg-9">
<div class="k-content   k-grid__item k-grid__item--fluid k-grid k-grid--hor" id="k_content">
    <div class="k-content__body k-grid__item k-grid__item--fluid" >
        <!--start of section-->
        <section class="mobile-recharge-section">
          
                              <div class="row manage-blnc-users">
                              
                              <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                <table  class="table table-striped- table-bordered table-hover table-checkable" id="transactiontable">
                               <thead>
                               <tr>
                               <th>Document</th>
                              <th></th>
                               <th></th>
                                
                             </tr>
                               </thead>
                               <tbody id="outlt_sts">
               <!--  <div class="form-top-header" id="outlt_sts" style="font-size: 23px;">Get in status</div> -->
                              </tbody>
                              </table>
                                <div id="active_buttn" style="text-align: center;"></div>
                              </div>
                              
                              </div>
                
            </div>    
        </section>   
        <!--end of section-->
    </div>

</div>
</div>
<!-- end:: Content -->
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC_muUtBOIDdqmHzdTov2-1K3dzCGSpyvI"></script>
<script> 


  var latitude = longitude = '';

    var geocoder = new google.maps.Geocoder();
    var address =<?php $pin = isset($user_data['business_pincode']) ? $user_data['business_pincode'] : 110019;
echo $pin;
?>;
    geocoder.geocode({'address': address.toString()}, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            latitude = results[0].geometry.location.lat();
            longitude = results[0].geometry.location.lng();
        } else {
            alert("Request failed.")
        }

    });
     $.ajax({

            url: 'ResisterOutlet/get_outlet_kycstats',
            dataType: "json",
            type: 'post',
            success: function (data) {
            console.log(data);
            if (data.error == 0) {
                var apprv='';
                var scrnn='';
                var requrd='';
                var mandtry=0;
                var activate_button='';
       

            if(data.response.APPROVED.length>0){
                
            $.each(data.response['APPROVED'], function (k1, v1) {
              //apprv += '<span> No action required(APPROVED)</span>';
                    apprv += '<tr>';
                    apprv += ' <td>'+v1['1']+'</td>';
                    apprv += '<td colspan="3">';
                    apprv +='Approved';
                    apprv += '</td>';
                    apprv += '</tr>';
                    apprv += '</tr> ';
                      
            });

                               
            }

             if(data.response.SCREENING.length>0){
                
              $.each(data.response['SCREENING'], function (k2, v2) {
                console.log(v2)
                  //scrnn += '<span>'+v2['1']+' pending for approval</span>';
                  scrnn += '<tr>';
                  scrnn += ' <td>'+v2['1']+'</td>';
                  scrnn += '<td colspan="3">';
                  scrnn +='pending for approval';
                  scrnn += '</td>';
                  scrnn += '</tr>';
                                   
              
            });


          }
           if(data.response.REQUIRED.length>0){
           
            $.each(data.response['REQUIRED'], function (k3, v3) {
                   
              if(v3['2'] == 'MANDATORY'){
                mandtry+=1;
              console.log(v3)
               /////// upload file field///
               
                                        requrd += '<tr>';
                                        requrd += ' <td>' + v3['1'] + '</td>';
                                        requrd += '<td>';
                                        requrd += '<form id=""><div class="form-group">';
                                        requrd += '<label for="doc">File Upload :</label>';
                                        requrd += '<div class="input-group mb-3 file">';
                                        requrd += '<div class="input-group-prepend">';
                                        requrd += '<span class="input-group-text">Upload</span>';
                                        requrd += '</div>';
                                        requrd += '<div class="form-control custom-file">';

                                        requrd += '<input type="file" class="custom-file-input" id="doc_' + v3['0'] + '" name="doc_' + v3['0'] + '">';
                                        requrd += '<label class="custom-file-label"></label>';
                                        requrd += '</div>';
                                       
                                        requrd += '</div>';
                                        requrd += '</div></form>';
                                           requrd += '</td>';
                                    requrd += ' <td><button type="submit" class="btn btn-space btn-primary btn-sm legitRipple ladda-button" data-style="zoom-in"  id="submit_' + v3['0'] + '" >Submit</button></td>';
                                    requrd += '</tr>';
                                   
                              
              }



            });


                               
                          
        }

              $('#outlt_sts').append(apprv+scrnn+requrd);
                                             $.each(data.response['REQUIRED'], function (k3, v3) {
                                            $('#submit_' + v3['0'] + '').click(function () {
                                                var file = $('#doc_' + v3['0'] + '')[0]['files'][0];
                                                
                                                    $('#submit_' + v3['0'] + '').addClass('btn-ladda ladda-button').attr('data-style', 'zoom-in');
                                                    var ga = $('#submit_' + v3['0'] + '').ladda();
                                                    ga.ladda('start');
                                                    var data = new FormData();
                                                    data.append('file', file);
                                                    data.append('DocId', v3['0']);
                                                    data.append('DocName', v3['1']);
                                                    $.ajax({
                                                        url: 'ResisterOutlet/DocUploadFile', 
                                                        type: "POST",
                                                        cache: false,
                                                        data: data,
                                                        dataType: 'json',
                                                        processData: false, // Don't process the files
                                                        contentType: false,
                                                        success: function (response) { 
                                                            if (response) {
                                                                if (response.error == 0) {
                                                                    
                                                                    toastr.success(response.msg);
                                                                    window.location.reload(true);// for reload page///
                                                                   
                                                                } else if (response.error == 2) {
                                                                    window.location.reload(true);
                                                                } else {
                                                                    
                                                                    toastr.error(response.error_desc);
                                                                    ga.ladda('stop');
                                                                }
                                                            }
                                                              ga.ladda('stop');
                                                        }, error: function (err) {
                                                            ga.ladda('stop');
                                                            throw err;
                                                        }
                                                    });
                                               

                                            });

                                          });

           if(data.response.SCREENING.length==0 && data.response.APPROVED.length>0 && mandtry==0 ){
            /// active button//
             activate_button+= '<button class="btn btn-primary" id="DocActivate" data-userId="' + data.user_id + '">Activate</button>';
         
              $('#active_buttn').html(activate_button);

              // $('#DocActivate').click(function(e){
              //   e.preventDefault()



              // })
              if(data.response.SCREENING.length==0 && data.response.APPROVED.length>0 && mandtry==0 ){
              $('#DocActivate').click(function (e) {
                        e.preventDefault();
                        var params = {'valid': true};
                        var actid = $(this).attr('id');
                        params.UserId = $(this).attr('data-userId');
                        if (params.valid == true) {
                            $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                            var la = $(this).ladda();
                            la.ladda('start');
                            $.ajax({
                                method: 'POST',
                                url: 'ResisterOutlet/UserAgentCdActivate',
                                data: params,
                                dataType: 'JSON'
                            }).done(function (response) {
                                if (response)
                                {
                                    if (response.error == 1)
                                    {
                                       
                                         toastr.error(response.error_desc);
                                    } else if (response.error == 2)
                                    {
                                        window.location.reload(true);
                                    } else if (response.error == 0) {
                                       
                                         toastr.success(response.msg);
                                           window.location.reload(true);// for reload page///
                                                                   
                                       
                                       
                                    }
                                    la.ladda('stop');
                                }
                                la.ladda('stop');
                            }).fail(function (err) {
                                la.ladda('stop');
                                throw err;
                            });
                        }
                    });
            }


           }
           
          
          //setTimeout(location.reload.bind(location), 1000);


            } else if (data.error == 2){

            window.location.reload(true);

                                
           } else {

              $('#outlt_sts').html(data.error_desc);
          

             }
           }     
        });

</script> 

