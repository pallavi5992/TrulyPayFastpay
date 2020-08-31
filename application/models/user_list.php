<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user_email = $this->session->userdata('accnt_id');
$view_info = get_user_details();
if (!$user_email) {

    redirect('Login');
}
?>
<!-- begin:: Content -->
<style>
	.switch-button {
    display: inline-block;
    line-height: 16px;
    border-radius: 50px;
    background-color: #ccc;
    width: 74px;
    height: 30px;
    padding: 2px;
    position: relative;
    overflow: hidden;
    vertical-align: middle;
}
.switch-button input#isdue {
    display: none;
}
.switch-button input#dwn {
    display: none;
}
.switch-button input#isdue:checked+span {
    background-color: #9674c8;
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 2px;
}
.switch-button input#dwn:checked+span {
    background-color: #9674c8;
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 2px;
}
.switch-button input#isdue:checked+span label {
    float: right;
}
.switch-button input#dwn:checked+span label {
    float: right;
}
.switch-button.switch-button-lg label {
    height: 26px;
    width: 26px;
}
.switch-button input#isdue:checked+span label:before {
    position: absolute;
    z-index: 0;
    content: "Yes";
    color: #fff;
    left: 0;
    text-align: left;
    padding-left: 8px;
}
.switch-button input#dwn:checked+span label:before {
    position: absolute;
    z-index: 0;
    content: "Up";
    color: #fff;
    left: 0;
    text-align: left;
    padding-left: 8px;
}
.switch-button label.dwnd:before {
    position: absolute;
    font-size: 11px;
    z-index: 0;
    content: "Down";
    right: 0;
    display: block;
    width: 100%;
    height: 100%;
    line-height: 27px;
    top: 0;
    text-align: right;
    padding-right: 8px;
    color: #fff;
}
.switch-button label:before {
    position: absolute;
    font-size: 11px;
    z-index: 0;
    content: "No";
    right: 0;
    display: block;
    width: 100%;
    height: 100%;
    line-height: 27px;
    top: 0;
    text-align: right;
    padding-right: 8px;
    color: #fff;
}
.switch-button label {
    border-radius: 50%;
    border: 1px solid transparent;
    background-color: #fff;
    margin: 0;
    height: 22px;
    width: 22px;
    display: inline-block;
    cursor: pointer;
    background-clip: padding-box;
}
</style>
<div class="k-content   k-grid__item k-grid__item--fluid k-grid k-grid--hor" id="k_content">

    <div class="k-content__body k-grid__item k-grid__item--fluid" >

        <section class="transaction-history-section">
            <div class="table-top-section">
                <h4>User List</h4>
                <div class="table-search-filter"><input type="text" id="transactionInput" placeholder="Search">
                    <span class="srch-icon-set"><i class="fas fa-search"></i></span>
                </div>
            </div>
            <table  class="table table-striped- table-bordered table-hover table-checkable" id="userlisttable">
                <tbody>
                </tbody>  
            </table>
        </section>   
    </div>
</div>
</div>
</div>
</div>

<!-- end:: Page -->

<script>
    $(document).ready(function () {
        
       
        $(".drop-trigger-btn").click(function () {
            $(".drop-menuset").toggleClass("drop-menuset-show");
        });

        var menuitems = document.getElementsByClassName("menuitems");
        $(".menuitems").click(function () {
            var menuname = this.innerText;
            $("#operator").text(menuname);
        });

        $("#myInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#operator-list li").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

    });

</script>    

<script>
    $(document).ready(function () {
        var prcss = false;
        var cnfrm = false;
        var userlist_tbl = $('#userlisttable').DataTable({
            "processing": true,
            "ajax": {
                url: "Manage/all_users_allowed_by_role",
                type: 'post',
                "dataSrc": function (json) {
                    if (json.error_data == 2)
                    {
                        window.location.reload(true);
                    } else if (json.error_data == 1)
                    {
                        toastr.error(json.error_desc, 'Oops!');
                    }
                    return json.data;
                }
            },
            responsive: true,
            processing: true,
                    order: [],
            columns: [
                {title: 'Full Name', data: 'full_name', class: 'all'},
                {title: 'Mobile', data: 'mobile', class: 'all'},
                {title: 'Email', data: 'email_id', class: 'all'},
                {title: 'Role Name', data: 'role_name', class: 'none'},
                {
                    title: 'Balance', data: 'rupee_balance',
                    render: function (data, type, full, meta) {

                        return "Rs. " + data;
                    }
                },
                {title: 'Account Id', data: 'accnt_id', class: 'all'},
                {title: 'Created On', data: 'created_on', class: 'none'},
                {title: 'Created By', data: 'fsnam', class: 'none'},
                {title: 'Shop Name', data: 'shop_name', class: 'none'},
                {title: 'Bussiness Address', class: 'none',
                    render: function (data, type, full, meta) {
                        return full.shop_addr + ' ' + full.shop_state + ' ' + full.shop_city + ' ' + full.shop_pincode;
                    }
                },
                {title: 'Permanent Address', data: 'permanent_addr', class: 'none'},
                {title: 'Status', data: 'is_active', class: 'all',
                    render: function (data, type, full, meta) {
                        var status = "";
                        if (data == 0) {
                            status = "Inactive";
                        } else if (data == 1) {
                            status = "Active";
                        } else {
                            status = "Undefined";
                        }
                        return status;
                    }

                },
                {
                    title: 'Action', class: 'all',
                    render: function (data, type, full, meta) {
                        return '<button data-users="' + full.accnt_id + '" type="button" class="btn btn-space btn-primary btn-sm" id="updt_usr_dt" data-toggle="modal" data-target="#mdl_usr_dsc">update</button>'
                    }
                },
            ],
            "createdRow": function (row, data, dataIndex) {
                if (data.is_active == 0 && data.is_blocked == 1) {
                    $('td', row).addClass('text-warning');
                } else if (data.is_active == 0) {
                    $('td', row).addClass('text-danger');
                }
            },
            buttons: [
                {extend: 'csv', className: 'btn-secondary'},
            ],
            "dom": 'Btrpl'
        });
        
        $('#mdl_usr_dsc').on('shown.bs.modal', function() {
    $(document).off('focusin.modal');
});

$('.sweet-alert').click(function() {
	swal({title:'Test', input: 'text'});
});
        
        $("#transactionInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            userlist_tbl.search(this.value).draw();
        });
        var valid_dedct_blnc = function () {
            $('#blnc_dect').on('keyup keypress blur', function (e) {

                var k = e.keyCode || e.which,
                        id = $(this)[0].id,
                        str = $(this)[0].value,
                        length = str.length;

                if (e.type == 'keypress')
                {
                    if (k != 8 && k != 9)

                    {
                        k = String.fromCharCode(k);
                        var price_regex = /^\s*(?=.*[0-9])\d*(?:\.\d{1,2})?\s*$/;
                        //console.log(price_regex.test(k));
                        var sw_regex = /[1-9]/;
                        if (length == 0 && !sw_regex.test(k))
                        {
                            return !1
                        }
                        if (!price_regex.test(k))
                        {
                            return !1
                        }


                    }

                    return !0
                } else if (e.type == 'blur')
                {
                    var min_price = /^\s*(?=.*[0-9])\d*(?:\.\d{1,2})?\s*$/;

                    if (!min_price.test(str))
                    {
                        $(this).val('')
                    }

                }
            });

            $('#remarks').on('keyup keypress blur', function (e) {
                var $this = $(this);
                if (this.value !== "") {
                    return !0
                }
            });
        }
        var valid_mng_blnc = function () {

            $('#blnc').on('keyup keypress blur', function (e) {

                var k = e.keyCode || e.which,
                        id = $(this)[0].id,
                        str = $(this)[0].value,
                        length = str.length;

                if (e.type == 'keypress')
                {
                    if (k != 8 && k != 9)

                    {
                        k = String.fromCharCode(k);
                        var price_regex = /^\s*(?=.*[0-9])\d*(?:\.\d{1,2})?\s*$/;
                        //console.log(price_regex.test(k));
                        var sw_regex = /[1-9]/;
                        if (length == 0 && !sw_regex.test(k))
                        {
                            return !1
                        }
                        if (!price_regex.test(k))
                        {
                            return !1
                        }


                    }

                    return !0
                } else if (e.type == 'blur')
                {
                    var min_price = /^\s*(?=.*[0-9])\d*(?:\.\d{1,2})?\s*$/;

                    if (!min_price.test(str))
                    {
                        $(this).val('')
                    }

                }
            });
            $('#bnk_ref').keypress(function (e) {

                var k = e.keyCode || e.which,
                        str = $(this)[0].value,
                        length = str.length;
                if (e.type == 'keypress') {
                    if (k != 8 && k != 9) {
                        k = String.fromCharCode(k);
                        var bsname_regex = /^[a-zA-Z0-9]+$/;
                        if (!bsname_regex.test(k)) {

                            return !1
                        }

                    }
                    return !0
                } else if (e.type == 'blur') {

                    var bs_name = /[0-9]/;
                    if (!bs_name.test(str)) {

                        $(this).val('')
                    }

                }
            });
            $('#bnk_nar').on('keyup keypress blur', function (e) {
                var $this = $(this);
                if (this.value !== "") {
                    return !0
                }
            });

        }
        var valid_usr_dtl = function () {

            
            $('#full_nam').keypress(function (e) {

        var k = e.keyCode || e.which,
                str = $(this)[0].value,
                length = str.length;
        if (e.type == 'keypress') {
            if (k != 8 && k != 9) {

                k = String.fromCharCode(k);
                var bsname_regex = /^[a-zA-Z ]+$/;
                if (!bsname_regex.test(k)) {
                    return !1
                }

            }
            return !0
        } else if (e.type == 'blur') {
            var bs_name = /^[a-zA-Z ]+$/;
            if (!bs_name.test(str)) {
                $(this).val('')
            }

        }
    });
            $('#bs_nam').keypress(function (e) {

                var k = e.keyCode || e.which,
                        str = $(this)[0].value,
                        length = str.length;
                if (e.type == 'keypress') {
                    if (k != 8 && k != 9) {
                        k = String.fromCharCode(k);
                        var bsname_regex = /^[a-zA-Z0-9\ \-\_\#\/]+$/;
                        if (!bsname_regex.test(k)) {

                            return !1
                        }

                    }
                    return !0
                } else if (e.type == 'blur') {

                    var bs_name = /[0-9]/;
                    if (!bs_name.test(str)) {

                        $(this).val('')
                    }

                }
            });

            $('input[type="tel"]').on('keyup keypress blur', function (e) {

                var k = e.keyCode || e.which,
                        id = $(this)[0].id,
                        str = $(this)[0].value,
                        length = str.length;
                if (id == "mob_num") {
                    if (e.type == 'keypress') {

                        if (k != 8 && k != 9) {

                            k = String.fromCharCode(k);
                            var mb_regex = /[0-9]/;
                            if (!mb_regex.test(k)) {

                                return !1
                            }
                            var sw_regex = /[6-9]/;
                            if (length == 0 && !sw_regex.test(k)) {

                                return !1
                            }
                            if (length == 10) {

                                return !1
                            }
                        }

                        return !0
                    } else if (e.type == 'blur') {

                        var _mobile = /^[6789][0-9]+$/;
                        if (!_mobile.test(str)) {

                            $(this).val('')
                        } else if (length != 10) {

                            $(this).val('')
                        }
                    }
                }


            });
            $('#pin_code').on('keyup keypress blur', function (e) {

                var k = e.keyCode || e.which;
                var str = $(this)[0].value;
                //console.log(str);
                length = str.length;

                if (e.type == 'keypress')
                {
                    if (k != 8 && k != 9)
                    {
                        k = String.fromCharCode(k);
                        var mb_regex = /[0-9]/;
                        //console.log(mb_regex.test(k));
                        if (!mb_regex.test(k))
                        {
                            return !1
                        }

                        if (length == 6)
                        {
                            return !1
                        }
                    }

                    return !0
                } else if (e.type == 'blur')
                {

                    var pincode = /^[0-9]+$/;

                    if (!pincode.test(str))

                    {
                        $(this).val('')
                    } else if (length > 6)
                    {
                        $(this).val('')
                    }
                }

            });

            $('#pan_num').on('keyup keypress blur', function (e) {

                var k = e.keyCode || e.which,
                        id = $(this)[0].id,
                        str = $(this)[0].value,
                        length = str.length;
                if (e.type == 'keypress')
                {
                    if (k != 8 && k != 9)
                    {
                        if (length == 10)
                        {
                            return !1
                        }
                    }

                    return !0
                } else if (e.type == 'blur')
                {
                    var _mobile = /^[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}$/
                    if (!_mobile.test(str))
                    {
                        $(this).val('')
                    } else if (length != 10)
                    {
                        $(this).val('')
                    }
                }



            });

            $('input[type="email"]').on('keypress keyup blur', function (e) {
                var k = e.keyCode || e.which,
                        id = $(this)[0].id,
                        str = $(this)[0].value,
                        length = str.length;


                if (e.type == 'blur' && str != null)
                {
                    var _email = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    if (!_email.test(str))
                    {
                        $(this).val('');
                        // toastr.error('Enter a valid Email Id');
                    }

                }




            });

        }
        userlist_tbl.on('click', '#updt_usr_dt', function () {
            var usrid = $(this).data('users');
            var row = $(this).closest('tr');
            var showtd1 = userlist_tbl.row(row).data();
            console.log(showtd1);
            if (showtd1['accnt_id'] == usrid) {
                var str = '<div class="modal fade" id="mdl_usr_dsc"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
                str += '<div class="modal-dialog" role="document" id="lrge_modal">';
                str += '<div class="modal-content">';
                str += '<div class="modal-header">';
                str += '<h5 class="modal-title" id="head_ttl">Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')</h5>';
                str += '<h5 class="modal-title" id="head_ttl2" style="display:none;"></h5>';
                str += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                str += '<span aria-hidden="true">&times;</span>';
                str += '</button>';
                str += '</div>';
                str += '<div class="modal-body">';
                str += '<div id="model_content">';
                str += '<div class="row manage-blnc-users" id="first_div" style="">';
                str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                str += '<div class="k-portlet">';
                str += '<div class="modal-rw"><h4>Add Balance</h4>';
                str += '<button type="button" class="btn btn-primary" data-add_blnc="' + showtd1.accnt_id + '" id="add_blnc">Add Balance</button>';
                str += '</div>';
               <?php if (@$view_info['role_id'] == 1) { ?>
                    str += '<div class="modal-rw"><h4>Deduct Balance</h4>';
                    str += '<button type="button" class="btn btn-primary" data-dedct_blnc="' + showtd1.accnt_id + '" id="dedct_blnc">Deduct Balance</button>';
                    str += '</div>';
                    str += '<div class="modal-rw"><h4>Update User Details</h4>';
                    str += '<button class="btn btn-primary" data-actvt_usr="' + showtd1.accnt_id + '" id="updt_usr_dtl">Update User Details</button>';
                    str += '</div>';
                    if ((showtd1.is_active == 0)) {
                        if (showtd1.is_blocked != 1) {
                            str += '<div class="modal-rw"><h4>Activate User</h4>';
                            str += '<button class="btn btn-primary" data-actvt_usr="' + showtd1.accnt_id + '" id="actvate_usr">Activate User</button>';
                            str += '</div>';
                        }


                    } else {
                        str += '<div class="modal-rw"><h4>Deactivate User</h4>';
                        str += '<button class="btn btn-primary" data-dcvt_usr="' + showtd1.accnt_id + '" id="dctvt_usr">Deactivate User</button>';
                        str += '</div>';
                    }
                    str += '<div class="modal-rw"><h4>KYC document</h4>';
                    str += '<button class="btn btn-primary" data-kyc_doc="' + showtd1.accnt_id + '" id="kyc_data">KYC Document</button>';
                    str += '</div>';
                    str += '<div class="modal-rw"><h4>Manage Plan</h4>';
                    str += '<button class="btn btn-primary" data-mng_pln="' + showtd1.accnt_id + '" id="mng_pln">Update Plan</button>';
                    str += '</div>';
                
                    str += '<div class="modal-rw"><h4>Reset Pass./PIN</h4>';
                    str += '<button class="btn btn-primary" data-rst_pin="' + showtd1.accnt_id + '" id="reset_pin" style="margin-left: 12px;">Reset Pin</button> <button class="btn btn-primary" data-rst_pswd="' + showtd1.accnt_id + '" id="reset_pss" >Reset Password</button>';
                    str += '</div>';
                    // str += '<div class="modal-rw"><h4>Reset Pin</h4>';
                    // str += '<button class="btn btn-primary" data-rst_pin="' + showtd1.accnt_id + '" id="reset_pin">Reset Pin</button>';
                    // str += '</div>';
                     <?php } ?>
                str += '</div> ';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                str += '<div id="usr_blnc_form" style="display:none;">';
                str += '</div>';
                str += '<div id="usr_deduct_blnc_form" style="display:none;">';
                str += '</div>';
                str += '<div id="usr_updt_dtl_form" style="display:none;">';
                str += '</div>';
                str += '<div id="kyc_dtl_documents_' + showtd1.accnt_id + '" style="display:none;">';
                str += '</div>';
                str += '<div id="mng_plan_form_' + showtd1.accnt_id + '" style="display:none;">';
                str += '</div>';
                 str += '<div id="reset_pswd_form_' + showtd1.accnt_id + '" style="display:none;">';
                str += '</div>';
                 str += '<div id="reset_pin_form_' + showtd1.accnt_id + '" style="display:none;">';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                $('body').append(str);
                $('#mdl_usr_dsc').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true,
                })
                $('#mdl_usr_dsc').on('hidden.bs.modal', function () {
                    $('#mdl_usr_dsc').remove();
                    userlist_tbl.ajax.reload(null, false);
                });
            }

            /*---add blnc*****---------*/
            var pyamnt_obj = {'Bank Transfer': 'Bank Transfer', 'Cash': 'Cash', 'Cheque/DD': 'Cheque/DD', 'Admin': 'Admin'};
            $('#add_blnc').click(function (e) {
                $("#head_ttl").hide();
                $('#head_ttl2').show().html('Manage ' + showtd1.full_name + ' Balance');
                e.preventDefault();
                $("#usr_blnc_form").show();
                $("#first_div").hide();
                var str = '<div class="row" id="mngblnc_form_div">';
                str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                str += '<div class="panel-body">';
                str += '<form action="#" id="updt_users_blnc_form">';
                str += '<div class="row">';

                str += '<div class="col-md-12">';
                str += '<div class="form-group has-error">';
                str += '<label>Amount</label>';
                str += '<div class="input-group">';
                str += '<input type="tel" placeholder="Credit Amount" class="form-control" name="blnc" id="blnc" >';
                str += '</div>';
                str += '</div>';
                str += ' </div>';
                str += ' <div class="col-md-12">';
                str += ' <div class="form-group has-error">';
                var sel_amnt_typ = '';
                str += ' <label>Payment Mode</label>';
                str += '<div class="input-group">';
                str += '<select class="form-control custom-select" name="py_amnt" id="py_amnt">';
                str += '<option value="default">Select Mode</option>';
                $.each(pyamnt_obj, function (k, v) {
                    str += '<option value="' + k + '" ' + sel_amnt_typ + '>' + v + '</option>';
                })
                str += '</select>';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                str += ' <div class="col-md-12">';
                str += ' <div class="form-group has-error">';

                str += ' <label>Bank Reference</label>';
                str += '<div class="input-group">';
                str += '  <input type="text" placeholder="Enter bank reference" class="form-control" name="bnk_ref" id="bnk_ref">';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                str += ' <div class="col-md-12">';
                str += ' <div class="form-group has-error">';
                str += '  <label>Bank Naration</label>';
                str += ' <div class="input-group">';
                str += ' <input type="text" placeholder="Enter bank naration" class="form-control" name="bnk_nar" id="bnk_nar">';
                str += ' </div>';
                str += ' </div>';
                str += ' </div>';

                str += ' <div class="col-md-12">';
                str += ' <div class="form-group"><label>Payment Due</label>';
                str += '<div class="input-group"><div class="switch-button switch-button-lg"><input type="checkbox" name="isdue" id="isdue" >';
                str += '<span><label for="isdue"></label></span>  </div></div></div>';
               
                 str += ' </div>';	

                str += '</div>';
                str += '<div class="modal-footer">';
                str += '<button type="submit" class="btn btn-secondary"  id="bck1_bnk">Back</button>';
                str += '<button type="submit" class="btn btn-brand legitRipple ladda-button"data-style="zoom-in" id="next_prc">Proceed</button>';
                str += '</div>';
                str += '</div>';
                str += '</form>';
                str += '</div>';
                str += '</div>';
                str += '<div id="prcd_blnc_form" style="display:none;">';
                str += '</div>';
                $('#usr_blnc_form').html(str).show();
                user_mng_balnc(showtd1);
                $('#bck1_bnk').click(function () {
                    $('#head_ttl2').hide();
                    $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');

                    $("#first_div").show();
                    $("#usr_blnc_form").hide().html('');
                });
            });
            var user_mng_balnc = function (showtd1) {

                $.validator.addMethod("numeric", function (value, element) {

                    return this.optional(element) || value == value.match(/^\s*(?=.*[0-9])\d*(?:\.\d{1,2})?\s*$/);

                }),
                        $.validator.addMethod("alpha_n", function (value, element) {

                            return this.optional(element) || value == value.match(/^[a-zA-Z0-9]+$/);
                            // --                                    or leave a space here ^^
                        }),
                        $.validator.addMethod("valueNotEqualsTo", function (value, element) {

                            return (this.optional(element) || value == 'Bank Transfer' || value == 'Cash' || value == 'Cheque/DD' || value == 'Admin');
                            // --                                    or leave a space here ^^
                        }),
                        valid_mng_blnc();
                $('#updt_users_blnc_form').validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block', // default input error message class

                    rules: {
                        blnc: {
                            required: true,
                            numeric: true,
                        },
                        bnk_ref: {
                            required: true,
                            alpha_n: true,
                        },
                        py_amnt: {
                            valueNotEqualsTo: true,
                        },
                        bnk_nar: {
                            required: true,
                        },
                    },
                    messages: {
                        blnc: {
                            required: "Amount is required.",
                            numeric: "Valid Amount is required.",
                        },
                        bnk_ref: {
                            required: "Bank Reference is required.",
                            alpha_n: "Valid Bank Reference is required.",
                        },
                        py_amnt: {
                            valueNotEqualsTo: "Please select Payment Amount"
                        },
                        bnk_nar: {
                            required: "Bank Naration is required.",
                        },
                    },
                    invalidHandler: function (event, validator) { //display error alert on form submit
                        $('.alert-danger', $('#updt_users_blnc_form')).show();
                    },
                    highlight: function (element) { // hightlight error inputs
                        $(element)
                                .closest('.form-group').addClass('has-error'); // set error class to the control group
                    },
                    success: function (label) {
                        label.closest('.form-group').removeClass('has-error');
                        label.remove();
                    },
                    errorPlacement: function (error, element) {

                        error.insertAfter(element.closest('.form-group').find('.input-group'));
                    },
                    submitHandler: function (form) {
                        // /***----payment proceed section-----***/
                        $("#first_div,#mngblnc_form_div").hide();

                        var params = {};
                        params.amnt = $('#blnc').val();
                        params.py_amnt = $('#py_amnt').val();
                        params.bnk_ref = $('#bnk_ref').val();
                        params.bnk_nar = $('#bnk_nar').val();
                        var dueinfo= $('#isdue').prop('checked');

                        var str = '<div class="row" id="prcd_form_div">';
                        str += '<div class="col-sm-12">';
                        str += '<div class="panel-body">';
                        str += '<form action="#" id="show_blnc_form">';
                        str += '<div class="row">';

                        str += '<div class="col-md-12">';
                        str += '<div class="form-group has-error">';
                        str += '<label>Amount</label>';
                        str += '<div class="input-group">';
                        str += '<input type="tel" class="form-control"value="' + params.amnt + '"  disabled>';
                        str += '</div>';
                        str += '</div>';
                        str += ' </div>';
                        str += ' <div class="col-md-12">';
                        str += ' <div class="form-group has-error">';
                        str += ' <label>Payment Mode</label>';
                        str += '<div class="input-group">';
                        str += '<input type="tel" class="form-control"value="' + params.py_amnt + '" disabled>';
                        str += '</div>';
                        str += '</div>';
                        str += '</div>';
                        str += ' <div class="col-md-12">';
                        str += ' <div class="form-group has-error">';

                        str += ' <label>Bank Reference</label>';
                        str += '<div class="input-group">';
                        str += '  <input type="text"class="form-control"value="' + params.bnk_ref + '" disabled>';
                        str += '</div>';
                        str += '</div>';
                        str += '</div>';
                        str += ' <div class="col-md-12">';
                        str += ' <div class="form-group has-error">';
                        str += '  <label>Bank Naration</label>';
                        str += ' <div class="input-group">';
                        str += ' <input type="text"class="form-control" value="' + params.bnk_nar + '" disabled>';
                        str += ' </div>';
                        str += ' </div>';
                        str += ' </div>';

                         var isdue=dueinfo===true?'Yes':'No';
						str+=' <div class="col-md-12">';
		                 str+=' <div class="form-group has-error">';
		                   str+=' <label>Payment Due</label>';
		                  str+='<div class="input-group">';
						  str+='<input type="text" class="form-control"value="'+isdue+'" disabled>';
		                  str+='</div>';
		                 str+='</div>';
		                str+='</div>';

                        str += '</div>';
                        str += '<div class="modal-footer">';
                        str += '<button type="submit" class="btn btn-secondary"  id="bck_bnk">Back</button>';
                        str += '<button type="submit" class="btn btn-brand legitRipple ladda-button" data-style="zoom-in" id="sucess_pymnt">Confirm</button>';
                        str += '</div>';

                        str += '</form>';
                        str += '</div>';
                        str += '</div>';
                        str += '</div>';
                        $('#prcd_blnc_form').html(str).show();

                        
                        $('#bck_bnk').click(function (e) {
                            e.preventDefault();
                            if (prcss === false) {

                                $("#mngblnc_form_div").show();
                                $("#show_blnc_form").hide().html('');
                            } else {

                                console.log('under process');
                            }
                        });
                        // /***----payment proceed section-----***/
                        $('#sucess_pymnt').click(function (e) {
                            e.preventDefault();
                            swal({
                                title: "Enter Transaction Pin",
                                text: "Transaction Pin",
                                type: "input",
                                inputType: "password",
                                showCancelButton: true,
                                closeOnConfirm: false,
                                inputPlaceholder: "Transaction Pin"
                            }, function (inputValue) {

                                if (inputValue === false)
                                    return false;
                                if (inputValue == "") {
                                    swal.showInputError("You Need To Write Transaction Pin!");
                                    return false
                                }

                                $.post('Manage/CheckTxnPin', {data: inputValue}, function (response) {
                                    response = JSON.parse(response);
                                    if (response) {

                                        if (response.error == 2)
                                        {
                                            window.location.reload(true);
                                        }
                                        else if (response.error == 0)
                                        {
                                            swal.close();

                                            if (prcss === false) {
                                                prcss = true;
                                                var la = $('#sucess_pymnt').ladda();
                                                la.ladda('start');

                                                $.post('Manage/add_usr_blnc_amnt', $(form).serialize() + "&showtd1=" + showtd1.accnt_id+ "&isdue=" + dueinfo, function (response) {

                                                    if (response) {
                                                        prcss = false;
                                                        if (response.error == 0)
                                                        {
                                                            toastr.info(response.msg);

                                                            $('#head_ttl2').hide();
                                                            $('#head_ttl').show().html('Action User Details of ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');

                                                            $('#first_div').show();
                                                            $('#usr_blnc_form').hide().html('');


                                                        } else if (response.error == 2) {


                                                            window.location.reload(true);


                                                        } else {
                                                            toastr.error(response.error_desc);

                                                        }
                                                        la.ladda('stop');

                                                    }

                                                }, 'json').fail(function (error) {

                                                    la.ladda('stop');
                                                    prcss = false;

                                                });
                                            } else {
                                                console.log('please wait');
                                            }

                                        } else {
                                            toastr.error(response.error_desc);
                                        }
                                    }
                                })
                            });
                        });

                        return false;
                    }
                });

            }
<?php if (@$view_info['role_id'] == 1) { ?>
                $('#dedct_blnc').click(function (e) {
                    $("#head_ttl").hide();
                    $('#head_ttl2').show().html('Manage ' + showtd1.full_name + ' Balance');
                    e.preventDefault();
                    $("#usr_blnc_form").hide();
                    $("#usr_deduct_blnc_form").show();
                    $("#first_div").hide();
                    var str = '<div class="row" id="dect_blnc_form_div">';
                    str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                    str += '<div class="panel-body">';
                    str += '<form action="#" id="deduct_users_blnc_form">';
                    str += '<div class="row">';

                    str += '<div class="col-md-12">';
                    str += '<div class="form-group has-error">';
                    str += '<label>Amount Deduction</label>';
                    str += '<div class="input-group">';
                    str += '<input type="tel" placeholder="Debit Amount" class="form-control" name="blnc_dect" id="blnc_dect" >';
                    str += '</div>';
                    str += '</div>';
                    str += ' </div>';
                    str += ' <div class="col-md-12">';
                    str += ' <div class="form-group has-error">';
                    str += ' <label>Payment Mode</label>';
                    str += '<div class="input-group">';
                    str += '<input type="text" placeholder="Enter your balance" class="form-control" name="pymnt_md_admin" id="pymnt_md_admin" value="ADMIN" disabled>';
                    str += '</div>';
                    str += '</div>';
                    str += '</div>';
                    str += ' <div class="col-md-12">';
                    str += ' <div class="form-group has-error">';
                    str += '  <label>Remarks</label>';
                    str += ' <div class="input-group">';
                    str += ' <input type="text" placeholder="Enter remarks" class="form-control" name="remarks" id="remarks">';
                    str += ' </div>';
                    str += ' </div>';
                    str += ' </div>';
                    str += '</div>';
                    str += '<div class="modal-footer">';
                    str += '<button type="submit" class="btn btn-secondary"  id="bckdebt_bnk">Back</button>';
                    str += '<button type="submit" class="btn btn-brand legitRipple ladda-button"data-style="zoom-in" id="debt_next_prc">Proceed</button>';
                    str += '</div>';
                    str += '</div>';
                    str += '</form>';
                    str += '</div>';
                    str += '</div>';
                    str += '<div id="prcd_deduct_blnc_form" style="display:none;">';
                    str += '</div>';
                    $('#usr_deduct_blnc_form').html(str).show();
                    deduct_user_balnc(showtd1);
                    $('#bckdebt_bnk').click(function () {
                        $('#head_ttl2').hide();
                        $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');

                        $("#first_div").show();
                        $("#usr_blnc_form").hide();
                        $("#usr_deduct_blnc_form").hide().html('');
                        ;
                    });
                });
                var deduct_user_balnc = function (showtd1) {
                    $.validator.addMethod("numeric", function (value, element) {

                        return this.optional(element) || value == value.match(/^\s*(?=.*[0-9])\d*(?:\.\d{1,2})?\s*$/);

                    }),
                            $.validator.addMethod("alpha_n", function (value, element) {

                                return this.optional(element) || value == value.match(/^[a-zA-Z0-9]+$/);
                                // --                                    or leave a space here ^^
                            }),
                            $.validator.addMethod("valueNotEqualsTo", function (value, element) {

                                return (this.optional(element) || value == 'Bank Transfer' || value == 'Cash' || value == 'Cheque/DD' || value == 'Admin');
                                // --                                    or leave a space here ^^
                            }),
                            valid_dedct_blnc();
                    $('#deduct_users_blnc_form').validate({
                        errorElement: 'span', //default input error message container
                        errorClass: 'help-block', // default input error message class

                        rules: {
                            blnc_dect: {
                                required: true,
                                numeric: true,
                            },
                            remarks: {
                                required: true,
                            },
                        },
                        messages: {
                            blnc_dect: {
                                required: "Amount is required.",
                                numeric: "Valid Amount is required.",
                            },
                            remarks: {
                                required: "Remarks is required.",
                            },
                        },
                        invalidHandler: function (event, validator) { //display error alert on form submit
                            $('.alert-danger', $('#deduct_users_blnc_form')).show();
                        },
                        highlight: function (element) { // hightlight error inputs
                            $(element)
                                    .closest('.form-group').addClass('has-error'); // set error class to the control group
                        },
                        success: function (label) {
                            label.closest('.form-group').removeClass('has-error');
                            label.remove();
                        },
                        errorPlacement: function (error, element) {

                            error.insertAfter(element.closest('.form-group').find('.input-group'));
                        },
                        submitHandler: function (form) {
                            // /***----payment proceed section-----***/
                            $("#first_div,#mngblnc_form_div,#dect_blnc_form_div").hide();

                            var params = {};
                            params.dect_blnc = $('#blnc_dect').val();
                            params.remrks = $('#remarks').val();
                            var str = '<div class="row" id="prcd_dect_blnc_form_div">';
                            str += '<div class="col-sm-12">';
                            str += '<div class="panel-body">';
                            str += '<form action="#" id="show_deduct_blnc_form">';
                            str += '<div class="row">';

                            str += '<div class="col-md-12">';
                            str += '<div class="form-group has-error">';
                            str += '<label>Amount</label>';
                            str += '<div class="input-group">';
                            str += '<input type="tel" class="form-control"value="' + params.dect_blnc + '"  disabled>';
                            str += '</div>';
                            str += '</div>';
                            str += ' </div>';
                            str += ' <div class="col-md-12">';
                            str += ' <div class="form-group has-error">';
                            str += ' <label>Payment Mode</label>';
                            str += '<div class="input-group">';
                            str += '<input type="tel" class="form-control"value="ADMIN" disabled>';
                            str += '</div>';
                            str += '</div>';
                            str += '</div>';

                            str += ' <div class="col-md-12">';
                            str += ' <div class="form-group has-error">';
                            str += '  <label>Remarks</label>';
                            str += ' <div class="input-group">';
                            str += ' <input type="text"class="form-control" value="' + params.remrks + '" disabled>';
                            str += ' </div>';
                            str += ' </div>';
                            str += ' </div>';
                            str += '</div>';
                            str += '<div class="modal-footer">';
                            str += '<button type="submit" class="btn btn-secondary"  id="deduct_bck_bnk">Back</button>';
                            str += '<button type="submit" class="btn btn-brand legitRipple ladda-button" data-style="zoom-in" id="sucess_pymnt_deduction">Confirm</button>';
                            str += '</div>';

                            str += '</form>';
                            str += '</div>';
                            str += '</div>';
                            str += '</div>';
                            $('#prcd_deduct_blnc_form').html(str).show();
                            $('#deduct_bck_bnk').click(function (e) {
                                e.preventDefault();
                                if (cnfrm === false) {

                                    $('#head_ttl2').hide();
                                    $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');
                                    $("#first_div").show();
                                    $("#usr_blnc_form").hide();
                                    $("#deduct_users_blnc_form").hide();
                                    $("#show_deduct_blnc_form").hide().html('');
                                } else {

                                    console.log('under process');
                                }

                            });
                            // /***----payment deduction proceed section-----***/
                            $('#sucess_pymnt_deduction').click(function (e) {
                                e.preventDefault();
                                swal({
                                    title: "Enter Transaction Pin",
                                    text: "Transaction Pin",
                                    type: "input",
                                    inputType: "password",
                                    showCancelButton: true,
                                    closeOnConfirm: false,
                                    inputPlaceholder: "Transaction Pin"
                                }, function (inputValue) {

                                    if (inputValue === false)
                                        return false;
                                    if (inputValue == "") {
                                        swal.showInputError("You Need To Write Transaction Pin!");
                                        return false
                                    }

                                    $.post('Manage/CheckTxnPin', {data: inputValue}, function (response) {
                                        response = JSON.parse(response);
                                        if (response) {

                                            if (response.error == 2)
                                            {
                                                window.location.reload(true);
                                            }
                                            else if (response.error == 0)
                                            {
                                                swal.close();


                                                if (cnfrm === false) {
                                                    cnfrm = true;
                                                    var la = $('#sucess_pymnt_deduction').ladda();
                                                    la.ladda('start');

                                                    $.post('Manage/deduct_usr_blnc_amnt', $(form).serialize() + "&showtd1=" + showtd1.accnt_id, function (response) {

                                                        if (response) {
                                                            cnfrm = false;
                                                            if (response.error == 0)
                                                            {
                                                                toastr.info(response.msg);

                                                                $('#head_ttl2').hide();
                                                                $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');

                                                                $('#first_div').show();
                                                                $('#prcd_deduct_blnc_form').hide();
                                                                $('#deduct_users_blnc_form').hide().html('');


                                                            } else if (response.error == 2) {


                                                                window.location.reload(true);


                                                            } else {
                                                                toastr.error(response.error_desc);

                                                            }
                                                            la.ladda('stop');

                                                        }

                                                    }, 'json').fail(function (error) {

                                                        la.ladda('stop');
                                                        cnfrm = false;

                                                    });

                                                } else {
                                                    console.log('please wait');
                                                }
                                            } else {
                                                toastr.error(response.error_desc);
                                            }
                                        }
                                    })
                                });


                            });

                            return false;
                        }
                    });
                }
<?php } ?>
            /*---end add blnc*****---------*/
            /*-----update user details *----*/
            var gen_obj = {'MALE': 'MALE', 'FEMALE': 'FEMALE'};
            <?php if (@$view_info['role_id'] == 1) { ?>
                $('#updt_usr_dtl').click(function (e) {
                    e.preventDefault();
                    $('#head_ttl').show().html('Update ' + showtd1.full_name + ' (Account: ' + showtd1.accnt_id + ')');
                    $("#usr_updt_dtl_form").show();
                    $("#first_div").hide();
                    // console.log(usr_dtl);
                    var str = '<div class="row" id="sec_form_div">';
                    str += '<div class="col-sm-12">';
                    str += '<div class="panel-body">';
                    str += '<form action="#" id="updt_users_form">';
                    str += '<div class="row">';
                    str += '<div class="col-md-6">';
                    str += '<div class="form-group">';
                    str += '<label>User</label>';
                    str += '<div class="input-group">';
                    str += '<input type="text" placeholder="Enter user role name" class="form-control" value="' + showtd1.role_name + '" disabled>';
                    str += '</div>';
                    str += '</div>';
                    str += ' </div>';
                    str += ' <div class="col-md-6">';
                    str += ' <div class="form-group has-error">';

                    str += ' <label>Full Name</label>';
                    str += '<div class="input-group">';
                    str += '<input type="text" placeholder="Enter user full name" class="form-control" name="full_nam" id="full_nam" value="' + showtd1.full_name + '">';
                    str += '</div>';
                    str += '</div>';
                    str += '</div>';
                    str += ' <div class="col-md-6">';
                    str += ' <div class="form-group has-error">';
                    var selgen = '';
                    str += ' <label>Select Gender</label>';
                    str += '<div class="input-group">';
                    str += '<select class="form-control custom-select" name="sel_gen" id="sel_gen">';
                    str += '  <option value="default">Select Gender</option>';
                    $.each(gen_obj, function (k, v) {
                        selgen = showtd1.gender == k ? 'selected' : '';
                        str += '<option value="' + k + '" ' + selgen + '>' + v + '</option>';
                    })
                    str += '</select>';
                    str += '</div>';
                    str += '</div>';
                    str += '</div>';
                    str += ' <div class="col-md-6">';
                    str += ' <div class="form-group has-error">';
                    str += '<label>Shop Name</label>';
                    str += ' <div class="input-group">';
                    str += '<input type="text" placeholder="Enter business name" class="form-control" name="bs_nam" id="bs_nam" value="' + showtd1.shop_name + '">';
                    str += '  </div>';
                    str += ' </div>';
                    str += ' </div>';
                    str += ' <div class="col-md-6">';
                    str += '  <div class="form-group has-error">';

                    str += '   <label>Mobile Number</label>';
                    str += ' <div class="input-group">';
                    str += '   <input type="tel" placeholder="Enter mobile number" class="form-control" name="mob_num" id="mob_num" value="' + showtd1.mobile + '">';
                    str += ' </div>';
                    str += ' </div>';
                    str += '</div>';
                    str += ' <div class="col-md-6">';
                    str += '<div class="form-group has-error">';

                    str += ' <label>Email Id</label>';
                    str += '<div class="input-group">';
                    str += '<input type="email" placeholder="Enter email id" class="form-control" name="em_id" id="em_id" value="' + showtd1.email_id + '">';
                    str += '</div>';
                    str += '</div>';
                    str += '</div>';
                    str += '<div class="col-md-6">';
                    str += '<div class="form-group has-error">';

                    str += ' <label>Pan Number</label>';
                    str += '<div class="input-group">';
                    str += '<input type="text" placeholder="Enter pan number" class="form-control" name="pan_num" id="pan_num" value="' + showtd1.pan_num + '">';
                    str += '</div>';
                    str += ' </div>';
                    str += ' </div>';
                    str += ' <div class="col-md-6">';
                    str += '<div class="form-group has-error">';
                    str += '<label>Shop Pincode</label>';
                    str += '<div class="input-group">';
                    str += ' <input type="tel" placeholder="Enter shop pincode" class="form-control" name="pin_code" id="pin_code" value="' + showtd1.shop_pincode + '">';
                    str += '</div>';
                    str += '</div>';
                    str += '</div>';
                    str += '<div class="col-md-6">';
                    str += '<div class="form-group has-error">';

                    str += ' <label>Shop State</label>';
                    str += ' <div class="input-group">';
                    str += '<input type="text" placeholder="Enter shop state" class="form-control" name="shpstate_num" id="shpstate_num" value="' + showtd1.shop_state + '">';
                    str += ' </div>';
                    str += '</div>';
                    str += '</div>';
                    str += ' <div class="col-md-6">';
                    str += '<div class="form-group has-error">';

                    str += ' <label>Shop City</label>';
                    str += '<div class="input-group">';
                    str += ' <input type="text" placeholder="Enter City" class="form-control" name="shpcity_num" id="shpcity_num" value="' + showtd1.shop_city + '">';
                    str += '</div>';
                    str += '</div>';
                    str += ' </div>';
                    str += ' <div class="col-md-12">';
                    str += ' <div class="form-group has-error">';

                    str += ' <label>Shop Address</label>';
                    str += '<div class="input-group">';
                    str += ' <textarea row="5" placeholder="Enter shop address" class="form-control" name="shpaddress" id="shpaddress">' + showtd1.shop_addr + '</textarea>';
                    str += '</div>';
                    str += ' </div>';
                    str += ' </div>';
                    str += '</div>';
                    str += '<div class="modal-footer">';
                    str += '<button type="submit" class="btn btn-secondary"  id="updt_back_us">Back</button>';
                    str += '<button type="submit" class="btn btn-brand legitRipple ladda-button" data-style="zoom-in" id="updt_us">Update User</button>';
                    str += '</div>';
                    str += '</form>';
                    str += '</div>';
                    str += '</div>';
                    str += '</div>';
                    $('#usr_updt_dtl_form').html(str).show();
                    profile_update();

                    $('#updt_back_us').click(function () {
                        $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');
                        $("#first_div").show();
                        $("#usr_updt_dtl_form").hide().html('');
                    });

                });
                var profile_update = function () {
                    $.validator.methods.digits = function (value, element) {

                        return this.optional(element) || /^[6789][0-9]+$/.test(value);

                    }
                    $.validator.methods.pindigits = function (value, element) {

                        return this.optional(element) || /^[0-9]+$/.test(value);

                    }
                    // $.validator.addMethod("alpha", function (value, element) {

                    //     return this.optional(element) || value == value.match(/^[a-zA-Z\ \s]+$/);
                    //     // --or leave a space here ^^

                    // }),
                     $.validator.addMethod("alpha", function (value, element) {

                        return this.optional(element) || value == value.match(/^[a-zA-Z\ \/]+$/);
                        // --or leave a space here ^^

                    }),
                            $.validator.addMethod("alpha_n", function (value, element) {

                                return this.optional(element) || value == value.match(/^[a-zA-Z0-9\ \-\_\#\/]+$/);
                                // --                                    or leave a space here ^^
                            }),
                            $.validator.addMethod("exactlength", function (value, element, param) {

                                return this.optional(element) || value.length == param;

                            },
                                    $.validator.format("Enter exactly {0} digits."));

                    $.validator.methods.email = function (value, element) {

                        return this.optional(element) || /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z]{2,6}$/.test(value);

                    },
                            $.validator.addMethod("valueNotEqualsTo", function (value, element) {

                                return (this.optional(element) || value == 'MALE' || value == 'FEMALE');
                                // --                                    or leave a space here ^^
                            }),
                            valid_usr_dtl();
                    $('#updt_users_form').validate({
                        errorElement: 'span', //default input error message container
                        errorClass: 'help-block', // default input error message class

                        rules: {
                            full_nam: {
                                required: true,
                                
                            },
                            sel_gen: {
                                valueNotEqualsTo: true,
                            },
                            bs_nam: {
                                required: true,
                                alpha_n: true,
                            },
                            mob_num: {
                                required: true,
                                digits: true,
                                exactlength: 10

                            },
                            em_id: {
                                required: true,
                                email: true
                            },
                            pan_num: {
                                required: true,
                                exactlength: 10
                            },
                            pin_code: {
                                required: true,
                                pindigits: true,
                                exactlength: 6
                            },
                            shpstate_num: {
                                required: true,
                            },
                            shpcity_num: {
                                required: true,
                            },
                            shpaddress: {
                                required: true,
                            },
                        },
                        messages: {
                            full_nam: {
                                required: "Full Name is required.",
                            },
                            sel_gen: {
                                valueNotEqualsTo: "Please select Gender Type"
                            },
                            bs_nam: {
                                required: "Shop nameis required.",
                                alpha_n: "Valid Business Name is required.",
                            },
                            mob_num: {
                                required: "Mobile Number is required.",
                                exactlength: "Please enter 10 digits for a valid Mobile number."

                            },
                            em_id: {
                                required: "Email is required.",
                            },
                            pan_num: {
                                required: "PAN number is required.",
                                exactlength: "Please enter 10 digits for a valid PAN number."
                            },
                            pin_code: {
                                required: "Shop Pincode is required.",
                            },
                            shpstate_num: {
                                required: "Shop State Name is required.",
                            },
                            shpcity_num: {
                                required: "Shop City Name is required.",
                            },
                            shpaddress: {
                                required: "Shop Address is required.",
                            },
                        },
                        invalidHandler: function (event, validator) { //display error alert on form submit
                            $('.alert-danger', $('#add_users_form')).show();
                        },
                        highlight: function (element) { // hightlight error inputs
                            $(element)
                                    .closest('.form-group').addClass('has-error'); // set error class to the control group
                        },
                        success: function (label) {
                            label.closest('.form-group').removeClass('has-error');
                            label.remove();
                        },
                        errorPlacement: function (error, element) {

                            error.insertAfter(element.closest('.form-group').find('.input-group'));
                        },
                        submitHandler: function (form) {

                            var la = $('#updt_us').ladda();
                            console.log(form);
                            la.ladda('start');
                            // console.log($(form).serialize()+ "&showtd1=" + showtd1.accnt_id);
                            $.post('Manage/update_add_users', $(form).serialize() + "&showtd1=" + showtd1.accnt_id, function (response) {

                                if (response) {

                                    if (response.error == 0)
                                    {
                                        toastr.info(response.msg);
                                        $('#mdl_usr_dsc').modal('hide');

                                        //window.location.reload(true);
                                        // $('#head_ttl2').hide();
                                        // $('#head_ttl').show().html('Manage: '+showtd1.full_name+' (Account:'+showtd1.accnt_id+')');

                                        // $('#first_div').show();
                                        // $('#usr_updt_dtl_form').hide();
                                        // $('#updt_users_form').hide().html('');

                                    } else if (response.error == 2) {


                                        window.location.reload(true);


                                    } else {
                                        toastr.error(response.error_desc);
                                        la.ladda('stop');
                                    }

                                }

                            }, 'json').fail(function (error) {

                                la.ladda('stop');

                            });

                            return false;
                        }

                    });
                }
<?php } ?>
            /****activate user ***/
<?php if (@$view_info['role_id'] == 1) { ?>
                $('#actvate_usr').click(function (e) {
                    e.preventDefault();
                    $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account: ' + showtd1.accnt_id + ')');
                    $("#first_div").show();
                    var acid = $(this).data('actvt_usr');
                    if (acid == showtd1.accnt_id) {
                        var dataString = {'usr_anctid': acid};
                        $.ajax({
                            url: 'Manage/actvate_inactive_usr',
                            dataType: "json",
                            data: dataString,
                            type: 'post',
                            success: function (data) {
                                console.log(data);
                                if (data.error == 0) {

                                    toastr.success(data.msg);
                                    $('#mdl_usr_dsc').modal('hide');

                                } else {

                                    toastr.error(data.error_desc);

                                }
                            }///success function close       
                        });
                    }
                });
                $('#dctvt_usr').click(function (e) {
                    e.preventDefault();
                    $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account: ' + showtd1.accnt_id + ')');
                    $("#first_div").show();
                    var acid = $(this).data('dcvt_usr');
                    if (acid == showtd1.accnt_id) {
                        var dataString = {'usr_anctid': acid};
                        $.ajax({
                            url: 'Manage/inactvate_active_usr',
                            dataType: "json",
                            data: dataString,
                            type: 'post',
                            success: function (data) {
                                console.log(data);
                                if (data.error == 0) {

                                    toastr.success(data.msg);
                                    $('#mdl_usr_dsc').modal('hide');

                                } else {

                                    toastr.error(data.error_desc);

                                }
                            }///success function close       
                        });
                    }
                });
                /*** end activate user section ****/
                /****KYC documents******/
                $('#kyc_data').click(function (e) {
                    $('#lrge_modal').addClass('modal-lg');
                    $("#head_ttl").hide();
                    $('#head_ttl2').show().html('Manage ' + showtd1.full_name + ' KYC Documents');
                    e.preventDefault();
                    $('#kyc_dtl_documents_' + showtd1.accnt_id + '').show();
                    $("#first_div").hide();
                    e.preventDefault();
                    var acid = $(this).data('kyc_doc');
                    if (acid == showtd1.accnt_id) {
                        var str = '<div id="model_content_' + showtd1.accnt_id + '">';
                        str += ' <div class="timeline-wrapper m-t-md m-b-xxl " id="timeline-wrapper-pro"><div class="timeline-item m-b-xl" ><div class="animated-background"><div class="background-masker content-top"></div><div class="background-masker content-first-end"></div><div class="background-masker content-second-line"></div><div class="background-masker content-second-end"></div><div class="background-masker content-third-line"></div><div class="background-masker content-third-end"></div><div class="background-masker content-top-1"></div><div class="background-masker content-first-end-1"></div><div class="background-masker content-second-line-1"></div><div class="background-masker content-second-end-1"></div><div class="background-masker content-third-line-1"></div><div class="background-masker content-third-end-1"></div><div class="background-masker content-top-2"></div><div class="background-masker content-first-end-2"></div><div class="background-masker content-second-line-2"></div><div class="background-masker content-second-end-2"></div><div class="background-masker content-third-line-2"></div><div class="background-masker content-third-end-2"></div></div></div></div>';
                        str += '</div>';
                        $('#kyc_dtl_documents_' + showtd1.accnt_id + '').html(str).show();
                        user_kyc_docs(showtd1);
                    }

                });
                var user_kyc_docs = function (showtd1) {
                    var document = {};
                    var documnt_array = {}
                    var loader = ' <div class="timeline-wrapper m-t-md m-b-xxl " id="timeline-wrapper-pro"><div class="timeline-item m-b-xl" ><div class="animated-background"><div class="background-masker content-top"></div><div class="background-masker content-first-end"></div><div class="background-masker content-second-line"></div><div class="background-masker content-second-end"></div><div class="background-masker content-third-line"></div><div class="background-masker content-third-end"></div><div class="background-masker content-top-1"></div><div class="background-masker content-first-end-1"></div><div class="background-masker content-second-line-1"></div><div class="background-masker content-second-end-1"></div><div class="background-masker content-third-line-1"></div><div class="background-masker content-third-end-1"></div><div class="background-masker content-top-2"></div><div class="background-masker content-first-end-2"></div><div class="background-masker content-second-line-2"></div><div class="background-masker content-second-end-2"></div><div class="background-masker content-third-line-2"></div><div class="background-masker content-third-end-2"></div></div></div></div>';
                    $('#kyc_dtl_documents_' + showtd1.accnt_id + '').html(loader);
                    $.ajax({
                        url: 'Manage/get_user_docs',
                        dataType: "json",
                        type: 'post',
                        data: {id: showtd1.accnt_id},
                        success: function (data) {
                            if (data.error == 0) {
                                document = data.msg;
                                console.log(document);
                                var str = '<div class="row manage-blnc-users" style="">';
                                str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                                str += '<table  class="table table-striped- table-bordered table-hover table-checkable" id="transactiontable">';
                                str += ' <thead>';
                                str += '<tr>';
                                str += '<th>DocumentType</th>';
                                str += ' <th>Document</th>';
                                str += ' <th>Action</th>';
                                str += '</tr>';
                                str += '</thead>';
                                str += '<tbody>';
                                var i = 0;
                                $.each(document, function (k, v) {
                                    documnt_array[i] = v;
                                    console.log(v);
                                    //console.log($.isEmptyObject(v));
                                    str += '<tr>';
                                    str += ' <td>' + k + '</td>';
                                    var docdta;
                                    var dta = 'kyc_' + k + '';
                                    docdta = dta.replace(" ", "");
                                    console.log(docdta);
                                    if ($.isEmptyObject(v) === true) {
                                        str += ' <td>Not Available</td>';
                                        str += '<td>';
                                        str += '<form id="' + docdta + '_' + showtd1.accnt_id + '_form"><div class="form-group">';
                                        str += '<label for="doc">' + k + ' :</label>';
                                        str += '<div class="input-group mb-3 file">';
                                        str += '<div class="input-group-prepend">';
                                        str += '<span class="input-group-text">Upload</span>';
                                        str += '</div>';
                                        str += '<div class="form-control custom-file">';

                                        str += '<input type="file" class="custom-file-input" id="' + docdta + '_' + showtd1.accnt_id + '" name="' + docdta + '_' + showtd1.accnt_id + '">';
                                        str += '<label class="custom-file-label"></label>';
                                        str += '</div><button type="submit" class="btn btn-space btn-primary btn-sm legitRipple ladda-button" data-style="zoom-in"  id="' + docdta + '_' + showtd1.accnt_id + '_sbmit" >Submit</button>';
                                        str += '</div>';
                                        str += '</div></form></td>';
                                    } else {
                                        str += ' <td><a href="' + v.doc_path + '" class="success-btn" target="_blank">View</a></td>';
                                        if (v.status == "PENDING") {
                                            str += '<td><button class="success-btn apprv_doc legitRipple ladda-button" data-style="zoom-in" data-apprv="' + v.id + '" >Approve</button><button class="failed-btn reject_doc legitRipple ladda-button" data-style="zoom-in" data-reject="' + v.id + '" >Reject</button></td>';
                                        } else {
                                            str += ' <td><div class="chng_doc_apvrject">' + v.status + '  <button class="success-btn aprvd_rjctd_chng_doc legitRipple ladda-button" data-style="zoom-in" data-chng_doc="' + v.id + '" id="aprvd_rjctd_chng_' + docdta + '_' + showtd1.accnt_id + '" >Change</button></div></td>';
                                        }
                                    }
                                    str += '</tr> ';
                                    i++;
                                });
                                console.log(documnt_array);
                                str += ' </tbody>';
                                str += ' </table>';
                                str += '</div>';
                                str += '</div>';
                                str += '<div class="modal-footer"><button type="submit" class="btn btn-secondary" id="bckkyc_doc">Back</button></div>';

                                $('#kyc_dtl_documents_' + showtd1.accnt_id + '').html(str);
                                /***validate file****/
                                $('.aprvd_rjctd_chng_doc').click(function (e) {
                                    e.preventDefault();
                                    $(this).closest('.chng_doc_apvrject').hide();
                                    var chng_fl = $(this).attr('id');
                                    console.log(chng_fl);
                                    var doc_id = $(this).data('chng_doc');
                                    var row = $(this).closest('tr').index();
                                    if (documnt_array[row] != undefined) {
                                        if (documnt_array[row].id == doc_id) {
                                            var fl = '<form id="' + chng_fl + '_form"><div class="form-group">';
                                            //fl+='<label for="doc">file :</label>';
                                            fl += '<div class="input-group mb-3 file">';
                                            fl += '<div class="input-group-prepend">';
                                            fl += '<span class="input-group-text">Upload</span>';
                                            fl += '</div>';
                                            fl += '<div class="form-control custom-file">';

                                            fl += '<input type="file" class="custom-file-input" id="' + chng_fl + '_file" name="' + chng_fl + '_file">';
                                            fl += '<label class="custom-file-label"></label>';
                                            fl += '</div><button type="submit" class="btn btn-space btn-primary btn-sm legitRipple ladda-button" data-style="zoom-in" data-upd_chng_dc="' + doc_id + '"  id="' + chng_fl + '_btn" >Submit</button>   <button class="btn btn-secondary chng_aprvdoc" id="">Back</button>';
                                            fl += '</div>';
                                            fl += '</div></form>';
                                            if ($('#' + chng_fl + '_form').length > 0) {
                                                $('#' + chng_fl + '_form').remove();
                                            }
                                            $(this).closest('td').append(fl);
                                            $('.chng_aprvdoc').click(function (e) {
                                                e.preventDefault();
                                                $(this).closest('td').find('.chng_doc_apvrject').show();
                                                $(this).closest('#' + chng_fl + '_form').remove();

                                            });
                                            valid_chmg_doc(chng_fl, documnt_array, showtd1);
                                        } else {
                                            toastr.error("Invalid document");
                                        }
                                    } else {
                                        toastr.error("Unable to find document details");
                                    }
                                });

                                $.each(document, function (k, v) {

                                    if ($.isEmptyObject(v) === true) {
                                        var docdta;
                                        var dta = 'kyc_' + k + '';
                                        docdta = dta.replace(" ", "");
                                        console.log(docdta);

                                        $('#' + docdta + '_' + showtd1.accnt_id + '').change(function () {
                                            var file = $('#' + docdta + '_' + showtd1.accnt_id + '')[0]['files'][0];
                                            console.log(file);
                                            if (file == undefined) {
                                                $(this).closest('.form-control').find('.custom-file-label').html('');
                                            } else {
                                                $(this).closest('.form-control').find('.custom-file-label').html("C:\\fakepath\\");
                                            }
                                        });
                                        var valid_obj = {
                                            errorElement: 'span', //default input error message container
                                            errorClass: 'help-block', // default input error message class
                                        }
                                        valid_obj['rules'] = {};
                                        valid_obj['rules']['' + docdta + '_' + showtd1.accnt_id + ''] = {
                                            required: true,
                                            accept: "jpg,png,jpeg,pdf",
                                        },
                                                valid_obj['messages'] = {};
                                        valid_obj['messages']['' + docdta + '_' + showtd1.accnt_id + ''] = {
                                            required: "Document is required",
                                            accept: "Only valid format is accepted",
                                        },
                                                valid_obj['invalidHandler'] = function (event, validator) { //display error alert on form submit
                                            $('.alert-danger', $('#' + docdta + '_' + showtd1.accnt_id + '_form')).show();
                                        }
                                        valid_obj['highlight'] = function (element) { // hightlight error inputs

                                            $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                                        }
                                        valid_obj['success'] = function (label) {
                                            label.closest('.form-group').removeClass('has-error');
                                            console.log(label);
                                            label.remove();
                                        }

                                        valid_obj['errorPlacement'] = function (error, element) {

                                            error.insertAfter(element.closest('.form-group').find('.input-group'));
                                        }
                                        console.log(valid_obj);

                                        valid_obj['submitHandler'] = function (form) {
                                            var row = $('#' + docdta + '_' + showtd1.accnt_id + '_sbmit').closest('tr').index();
                                            console.log(row);
                                            if (documnt_array[row] != undefined) {
                                                if (documnt_array[row].length == 0) {

                                                    var la = $('#' + docdta + '_' + showtd1.accnt_id + '_sbmit').ladda();
                                                    la.ladda('start');
                                                    console.log(la);
                                                    var file = $('#' + docdta + '_' + showtd1.accnt_id + '')[0]['files'][0];

                                                    console.log(file);

                                                    var data = new FormData();

                                                    data.append('file', file);
                                                    data.append('accntid', showtd1.accnt_id);
                                                    data.append('doctyp', k);


                                                    $.ajax({
                                                        url: 'Manage/submit_dcmnt_by_typ',
                                                        type: 'POST',
                                                        data: data,
                                                        cache: false,
                                                        dataType: 'json',
                                                        processData: false, // Don't process the files
                                                        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                                                        success: function (response)
                                                        {
                                                            if (response) {

                                                                if (response.error == 0)
                                                                {
                                                                    toastr.info(response.msg);
                                                                    $(form)[0].reset();

                                                                    $('#' + docdta + '_' + showtd1.accnt_id + '').hide().html('');
                                                                    user_kyc_docs(showtd1);
                                                                } else if (response.error == 2) {


                                                                    window.location.reload(true);


                                                                } else {
                                                                    toastr.error(response.error_desc);
                                                                    la.ladda('stop');
                                                                }

                                                            }

                                                        }, error: function (error) {
                                                            la.ladda('stop');
                                                            throw error;
                                                        }
                                                    });


                                                    return false;
                                                } else {
                                                    toastr.error("Invalid document");
                                                }
                                            } else {
                                                toastr.error("Unable to find document details");
                                            }
                                        }
                                        var val_form = $('#' + docdta + '_' + showtd1.accnt_id + '_form').validate(valid_obj);
                                    }
                                });
                                /***section validate file ***/
                                $('#bckkyc_doc').click(function () {
                                    $('#head_ttl2').hide();
                                    $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');
                                    $("#first_div").show();
                                    $('#kyc_dtl_documents_' + showtd1.accnt_id + '').hide();
                                    $('#kyc_dtl_documents_' + showtd1.accnt_id + '').hide().html('');
                                    $('#lrge_modal').removeClass('modal-lg');
                                    $('#kyc_dtl_documents_' + showtd1.accnt_id + '').hide();
                                });
                                $('.apprv_doc').click(function (e) {
                                    e.preventDefault();
                                    var doc = $(this).data('apprv');
                                    var row = $(this).closest('tr').index();
                                    console.log(row);
                                    console.log(documnt_array);
                                    console.log(documnt_array[row].id);
                                    console.log(doc);
                                    if (documnt_array[row] != undefined) {
                                        if (documnt_array[row].id == doc) {

                                            var dataString = {'apprv_id': doc, 'acntid': showtd1.accnt_id};
                                            $.ajax({
                                                url: 'Manage/approve_usr_document',
                                                dataType: "json",
                                                data: dataString,
                                                type: 'post',
                                                success: function (data) {
                                                    console.log(data);
                                                    if (data.error == 0) {

                                                        toastr.success(data.msg);
                                                        //pendg_actvtn_tbl.ajax.reload(null,false);
                                                        user_kyc_docs(showtd1);
                                                    } else {

                                                        toastr.error(data.error_desc);

                                                    }
                                                }///success function close       
                                            });
                                        } else {
                                            toastr.error("Invalid document");
                                        }
                                    } else {
                                        toastr.error("Unable to find document details");
                                    }
                                });
                                /*****reject document ********/
                                $('.reject_doc').click(function (e) {
                                    e.preventDefault();
                                    var rjct_doc = $(this).data('reject');
                                    var row = $(this).closest('tr').index();
                                    console.log(row);
                                    if (documnt_array[row] != undefined) {
                                        if (documnt_array[row].id == rjct_doc) {
                                            var dataString = {'rjct_doc_id': rjct_doc, 'acntid': showtd1.accnt_id};
                                            $.ajax({
                                                url: 'Manage/reject_usr_document',
                                                dataType: "json",
                                                data: dataString,
                                                type: 'post',
                                                success: function (data) {
                                                    console.log(data);
                                                    if (data.error == 0) {

                                                        toastr.success(data.msg);

                                                        user_kyc_docs(showtd1);
                                                    } else {

                                                        toastr.error(data.error_desc);

                                                    }
                                                }///success function close       
                                            });
                                        } else {
                                            toastr.error("Invalid document");
                                        }
                                    } else {
                                        toastr.error("Unable to find document details");
                                    }
                                });
                                /*****end reject document section*********/

                            }
                        }
                    });
                    var valid_chmg_doc = function (btnid, doc_array, acntid) {

                        var valid_obj = {
                            errorElement: 'span', //default input error message container
                            errorClass: 'help-block', // default input error message class
                        }
                        valid_obj['rules'] = {};
                        valid_obj['rules']['' + btnid + '_file'] = {
                            required: true,
                            accept: "jpg,png,jpeg,pdf",
                        },
                                valid_obj['messages'] = {};
                        valid_obj['messages']['' + btnid + '_file'] = {
                            required: "Document is required",
                            accept: "Only valid format is accepted",
                        },
                                valid_obj['invalidHandler'] = function (event, validator) { //display error alert on form submit
                            $('.alert-danger', $('#' + btnid + '_form')).show();
                        }
                        valid_obj['highlight'] = function (element) { // hightlight error inputs

                            $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                        }
                        valid_obj['success'] = function (label) {
                            label.closest('.form-group').removeClass('has-error');
                            console.log(label);
                            label.remove();
                        }
                        valid_obj['errorPlacement'] = function (error, element) {

                            error.insertAfter(element.closest('.form-group').find('.input-group'));
                        }
                        console.log(valid_obj);
                        valid_obj['submitHandler'] = function (form) {
                            var row = $('#' + btnid + '_file').closest('tr').index();
                            var data_row = $('#' + btnid + '_btn').data('upd_chng_dc');
                            // console.log(row);
                            // console.log(doc_array);
                            // console.log(doc_array[row].id);
                            // console.log(data_row);
                            // console.log(documnt_array[row]);
                            if (doc_array[row].id != undefined) {
                                if (doc_array[row].id = data_row) {
                                    var la = $('#' + btnid + '_btn').ladda();
                                    la.ladda('start');

                                    var file = $('#' + btnid + '_file')[0]['files'][0];
                                    var data = new FormData();

                                    data.append('file', file);
                                    data.append('id', doc_array[row].id);
                                    data.append('accntid', showtd1.accnt_id);
                                    data.append('doctyp', doc_array[row].doc_name);


                                    $.ajax({
                                        url: 'Manage/chng_aprv_rjctd_doc',
                                        type: 'POST',
                                        data: data,
                                        cache: false,
                                        dataType: 'json',
                                        processData: false, // Don't process the files
                                        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                                        success: function (response)
                                        {
                                            if (response) {

                                                if (response.error == 0)
                                                {
                                                    toastr.info(response.msg);
                                                    $(form)[0].reset();

                                                 
                                                    user_kyc_docs(showtd1);
                                                } else if (response.error == 2) {


                                                    window.location.reload(true);


                                                } else {
                                                    toastr.error(response.error_desc);
                                                    la.ladda('stop');
                                                }

                                            }

                                        }, error: function (error) {
                                            la.ladda('stop');
                                            throw error;
                                        }
                                    });


                                    return false;
                                } else {
                                    toastr.error("Invalid document");
                                }
                            } else {
                                toastr.error("Unable to find document details");
                            }
                        }
                        var val_form = $('#' + btnid + '_form').validate(valid_obj);
                    }

                }

                /****KYC documents section end******/
                /******manage plan ************/
                $('#mng_pln').click(function (e) {
                    e.preventDefault();
                    var acid = $(this).data('mng_pln');
                    console.log(acid);

                    if (acid == showtd1.accnt_id) {
                        $("#head_ttl").hide();
                        $('#head_ttl2').show().html('Manage ' + showtd1.full_name + ' Plan');

                        //  $('#mng_plan_form_'+showtd1.accnt_id+'').show();
                        $("#first_div").hide();
                        var plan_array = {}
                        $.ajax({
                            url: 'Manage/get_allplans',
                            dataType: "json",
                            type: 'post',
                            data: {role_id: showtd1.role_id},
                            beforeSend: function (data) {

                                var loader = ' <div class="timeline-wrapper m-t-md m-b-xxl " id="timeline-wrapper-pro"><div class="timeline-item m-b-xl" ><div class="animated-background"><div class="background-masker content-top"></div><div class="background-masker content-first-end"></div><div class="background-masker content-second-line"></div><div class="background-masker content-second-end"></div><div class="background-masker content-third-line"></div><div class="background-masker content-third-end"></div><div class="background-masker content-top-1"></div><div class="background-masker content-first-end-1"></div><div class="background-masker content-second-line-1"></div><div class="background-masker content-second-end-1"></div><div class="background-masker content-third-line-1"></div><div class="background-masker content-third-end-1"></div><div class="background-masker content-top-2"></div><div class="background-masker content-first-end-2"></div><div class="background-masker content-second-line-2"></div><div class="background-masker content-second-end-2"></div><div class="background-masker content-third-line-2"></div><div class="background-masker content-third-end-2"></div></div></div></div>';
                                $('#mng_plan_form_' + showtd1.accnt_id + '').html(loader).show();
                            },
                            success: function (data) {
                                if (data.error == 0) {

                                    var str = '<div class="row" id="mngplnrt_form_div">';
                                    str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                                    str += '<div class="panel-body">';
                                    str += '<form action="#" id="updt_rate_form">';
                                    str += '<div class="row">';
                                    str += ' <div class="col-md-12">';
                                    str += ' <div class="form-group">';

                                    console.log(plan_array);
                                    str += ' <label>Plan</label>';
                                    str += '<div class="input-group">';
                                    str += '<select class="form-control custom-select" name="plan_name" id="plan_name">';
                                    str += '<option value="default">Select Plan</option>';
                                    $.each(data.msg, function (k, v) {
                                        plan_array[v.id] = v
                                        var sel_rt = (showtd1.plan_id == v.id) ? 'selected' : ''
                                        str += '<option value="' + v.id + '" ' + sel_rt + '>' + v.plan_name + '</option>'
                                    })
                                    str += '</select>';
                                    str += '</div>';
                                    str += '</div>';
                                    str += '</div>';
                                    str += '</div>';
                                    str += '<div class="modal-footer">';
                                    str += '<button type="submit" class="btn btn-secondary"  id="bcl_rate">Back</button>';
                                    str += '<button type="submit" class="btn btn-brand legitRipple ladda-button"data-style="zoom-in" id="rt_update">Update</button>';
                                    str += '</div>';
                                    str += '</div>';
                                    str += '</form>';
                                    str += '</div>';
                                    str += '</div>';

                                    $('#mng_plan_form_' + showtd1.accnt_id + '').html(str).show();

                                    $('#bcl_rate').click(function (e) {
                                        e.preventDefault();
                                        $('#head_ttl2').hide();
                                        $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');

                                        $("#first_div").show();
                                        $('#mng_plan_form_' + showtd1.accnt_id + '').hide().html('');
                                    })
                                    update_plan_view(showtd1, plan_array);
                                } else if (data.error == 2) {

                                    window.location.reload(true);

                                } else {
                                    $('#head_ttl2').hide();
                                    $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');
                                    $("#first_div").show();
                                    $('#mng_plan_form_' + showtd1.accnt_id + '').hide().html('');
                                    toastr.error(data.error_desc);
                                }
                            }
                        });

                    }



                });
                var update_plan_view = function (showtd1, plan_array) {

                    $.validator.addMethod("valueNotEquals", function (value, element, arg) {
                        return (value in plan_array);
                    }, "Value must not equal arg.");

                    $('#updt_rate_form').validate({
                        errorElement: 'span', //default input error message container
                        errorClass: 'help-block', // default input error message class

                        rules: {
                            plan_name: {
                                valueNotEquals: true,
                            },
                        },
                        messages: {
                            plan_name: {
                                valueNotEquals: "Please select defined Plan Name"
                            },
                        },
                        invalidHandler: function (event, validator) { //display error alert on form submit
                            $('.alert-danger', $('#updt_btn_rtlr')).show();
                        },
                        highlight: function (element) { // hightlight error inputs
                            $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                        },
                        success: function (label) {
                            label.closest('.form-group').removeClass('has-error');
                            label.remove();
                        },
                        errorPlacement: function (error, element) {

                            error.insertAfter(element.closest('.form-group').find('.input-group'));
                        },
                        submitHandler: function (form) {

                            var la = $('#rt_update').ladda();
                            la.ladda('start');

                            $.post('Manage/updt_rate_pln', $(form).serialize() + "&showtd1=" + showtd1.accnt_id + "&role_id=" + showtd1.role_id, function (response) {

                                if (response) {

                                    if (response.error == 0)
                                    {
                                        toastr.info(response.msg);

                                        $('#head_ttl2').hide();
                                        $('#head_ttl').show().html('Action User Details of ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');


                                        $('#updt_rate_form').hide().html('');
                                        $('#mdl_usr_dsc').modal('hide');

                                    } else if (response.error == 2) {


                                        window.location.reload(true);


                                    } else {
                                        toastr.error(response.error_desc);

                                    }
                                    la.ladda('stop');
                                }

                            }, 'json').fail(function (error) {

                                la.ladda('stop');

                            });

                            return false;
                        }

                    });

                }
               
                /******end manage plan*******/

                /******** reset paasword ******/
                //  $('#reset_pss').click(function (e) {
                //     e.preventDefault();
                //     var acid = $(this).data('rst_pswd');
                //     console.log(acid);

                // if (acid == showtd1.accnt_id) {
                // $("#head_ttl").hide();
                // $('#head_ttl2').show().html('Reset ' + showtd1.full_name + ' Password');
                // $('#reset_pswd_form_' + showtd1.accnt_id + '').show();
                // $("#first_div").hide();
                // var str = '<div class="row" id="mngblnc_form_div">';
                // str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                // str += '<div class="panel-body">';
                // str += '<form action="#" id="updt_users_blnc_form">';
                // str += '<div class="row">';

                // str += '<div class="col-md-12">';
                // str += '<div class="form-group has-error">';
                // str += '<label>Amount</label>';
                // str += '<div class="input-group">';
                // str += '<input type="tel" placeholder="Credit Amount" class="form-control" name="blnc" id="blnc" >';
                // str += '</div>';
                // str += '</div>';
                // str += ' </div>';
                // str += ' <div class="col-md-12">';
                // str += ' <div class="form-group has-error">';
                // var sel_amnt_typ = '';
                // str += ' <label>Payment Mode</label>';
                // str += '<div class="input-group">';
                // str += '<select class="form-control custom-select" name="py_amnt" id="py_amnt">';
                // str += '<option value="default">Select Mode</option>';
                // $.each(pyamnt_obj, function (k, v) {
                //     str += '<option value="' + k + '" ' + sel_amnt_typ + '>' + v + '</option>';
                // })
                // str += '</select>';
                // str += '</div>';
                // str += '</div>';
                // str += '</div>';
                // str += ' <div class="col-md-12">';
                // str += ' <div class="form-group has-error">';

                // str += ' <label>Bank Reference</label>';
                // str += '<div class="input-group">';
                // str += '  <input type="text" placeholder="Enter bank reference" class="form-control" name="bnk_ref" id="bnk_ref">';
                // str += '</div>';
                // str += '</div>';
                // str += '</div>';
                // str += ' <div class="col-md-12">';
                // str += ' <div class="form-group has-error">';
                // str += '  <label>Bank Naration</label>';
                // str += ' <div class="input-group">';
                // str += ' <input type="text" placeholder="Enter bank naration" class="form-control" name="bnk_nar" id="bnk_nar">';
                // str += ' </div>';
                // str += ' </div>';
                // str += ' </div>';

                // str += ' <div class="col-md-12">';
                // str += ' <div class="form-group"><label>Payment Due</label>';
                // str += '<div class="input-group"><div class="switch-button switch-button-lg"><input type="checkbox" name="isdue" id="isdue" >';
                // str += '<span><label for="isdue"></label></span>  </div></div></div>';
               
                //  str += ' </div>';  

                // str += '</div>';
                // str += '<div class="modal-footer">';
                // str += '<button type="submit" class="btn btn-secondary"  id="bck1_bnk9">Back</button>';
                // str += '<button type="submit" class="btn btn-brand legitRipple ladda-button"data-style="zoom-in" id="rst_usr_pswd">Reset</button>';
                // str += '</div>';
                // str += '</div>';
                // str += '</form>';
                // str += '</div>';
                // str += '</div>';
                // str += '<div id="prcd_blnc_form" style="display:none;">';
                // str += '</div>';
                // $('#reset_pswd_form_' + showtd1.accnt_id + '').html(str).show();
                // user_mng_balnc(showtd1);
                // $('#bck1_bnk9').click(function () {
                //     $('#head_ttl2').hide();
                //     $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');

                //     $("#first_div").show();
                //     $('#reset_pswd_form_' + showtd1.accnt_id + '').hide().html('');
                // });
                        

                //     }



                // });

                $('#reset_pss').click(function (e) {
                    e.preventDefault();
                    var acid = $(this).data('rst_pswd');
                    console.log(acid);

                if (acid == showtd1.accnt_id) {
                    
                    $('#reset_pss').addClass('btn-ladda ladda-button').attr('data-style', 'zoom-in');
                    $('#reset_pss').attr('data-spinner-color', '#000000');
                    var la = $('#reset_pss').ladda();
                    la.ladda('start');
                    $.ajax({
                                    url: 'Manage/reset_usr_passwrd',
                                    dataType: "json",
                                    type: 'post',
                                    data: { acntid: acid },

                                    success: function (response) {
                                         
                                        if (response.error == 0) {
                                            // create swal
                                            toastr.success(response.msg);
                                             
                                        } else if (response.error == 2) {
                                            window.location.reload(true);
                                        } else {
                                            toastr.error(response.error_desc);
                                        }
                                        la.ladda('stop');
                                    }
                            });  

                    }



                });
         
         
                /********end reset paasword ******/
                /******reset pin******/
                 $('#reset_pin').click(function (e) {
                    e.preventDefault();
                    var acid = $(this).data('rst_pin');
                    console.log(acid);

                if (acid == showtd1.accnt_id) {
                    
                    $('#reset_pin').addClass('btn-ladda ladda-button').attr('data-style', 'zoom-in');
                    $('#reset_pin').attr('data-spinner-color', '#000000');
                    var la = $('#reset_pin').ladda();
                    la.ladda('start');
                    $.ajax({
                                    url: 'Manage/reset_usr_pin',
                                    dataType: "json",
                                    type: 'post',
                                    data: { acntid: acid },

                                    success: function (response) {
                                         
                                        if (response.error == 0) {
                                            // create swal
                                            toastr.success(response.msg);
                                             
                                        } else if (response.error == 2) {
                                            window.location.reload(true);
                                        } else {
                                            toastr.error(response.error_desc);
                                        }
                                        la.ladda('stop');
                                    }
                            });  

                    }



                });
         
         
                /********end reset pin ******/
                 <?php } ?>
        });
    });
</script>

