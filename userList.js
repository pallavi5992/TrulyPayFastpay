$(document).ready(function()
{       
    
        var opr = {};
        var opr1 = {};
        var urole_id = "";
        var change;
        var twofasettings;

        $.ajax({
            url: 'Manage/getConfigurationList',
            dataType: 'json',
            type: 'POST',
            success:function(response){
                
                if(response.Resp_code == 'RCS')
                { 
                    if(response.data)
                    {
                        twofasettings = response.data;
                    }    
                }
                else if(response.Resp_code == 'ERR')
                {
                   console.log(response.Resp_desc);
                }
                else if(response.Resp_code == 'UAC')
                {
                  window.location.reload(true);
                }

            }
        });

       $.ajax({
            url: 'Dashboard/activeUser',
            dataType: "json",
            type: 'post',
            async: false,
            success: function (response) {
                if (response.Resp_code == 'RCS') 
                {
                    urole_id = response.data.role_id;
                } 
                else if(response.Resp_code == 'UAC')
                {
                    window.location.reload();

                }
            }///success function close       
        });

       //console.log(urole_id);
    
        $('#crb').on('change', function (e) {
            var id = $(this).val();

            $.post('Manage/listAdminOrSubadmin', {role_id: id}, function (response) {
                if (response) {
                    if (response.Resp_code == 'UAC')
                    {
                        window.location.reload(true);
                    }
                    else if (response.Resp_code == 'RCS')
                    {
                        change = 1;
                        if (response.data) {

                            $('#tableData').html(' <table  class="table  table-bordered" id="user_list_tbl"></table>');
                            if (response.data.length > 0) {
                                $('#newselect').html('');
                                $('#showlist').html('');
                                $('#newshowlist').html('');
                                var str = '';
                                str += '<div class="form-group m-form__group">';
                                str += '  <div class="col-xs-6">';
                                str += '  <select class="form-control" id="newparent" name="newparent" data-placeholder="Select User">';
                                str += '<option value="">Select User</option>';
                                $.each(response.data, function (k, v) {
                                    opr[v.accnt_id] = v;
                                    str += '<option value=' + v.accnt_id + '>' + v.full_name + '</option>'
                                });
                                str += ' </select>';
                                str += '</div>';
                                str += '</div>';

                                $('#newselect').html(str);
                                $('#newparent').select2();
                            } 
                            else 
                            {
                                console.log('No data');
                                $('#newselect').html('');
                                $('#showlist').html('');
                                $('#newshowlist').html('');
                            }

                            var datatable = $('#user_list_tbl').DataTable({
                                "processing": true,
                                "serverSide": false,
                                order: [],
                                data: response.data,
                                language: {
                                    aria: {
                                        "sortAscending": ": activate to sort column ascending",
                                        "sortDescending": ": activate to sort column descending"
                                    },
                                    search: '<span>Filter:</span> _INPUT_',
                                    searchPlaceholder: 'Type to filter...',
                                    lengthMenu: '<span>Show:</span> _MENU_',
                                    paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
                                    processing: '<span>&nbsp;&nbsp;<i class="icon-spinner4 spinner position-left"></i> LOADING...</span>'
                                },
                                select: {
                                    style: 'multi'
                                },
                                responsive: true,
                                  columns: [
                                      {title: 'JLX India Id', data: 'accnt_id', class: 'all compact', width: '10%'},
                                      {title: 'Full Name', data: 'full_name', class: 'all compact', width: '1%'},
                                      {title: 'Role Name', data: 'role_name', class: 'all compact', width: '10%'},
                                      {title: 'Mobile', data: 'mobile', class: 'all compact', width: '10%'},
                                      {title: 'Email', data: 'email_id', class: 'none compact', width: '10%'},
                                      {
                                          title: 'Balance', data: 'rupee_balance', width: '10%',
                                          render: function (data, type, full, meta) {

                                              return "Rs. " + data;
                                          }
                                          , class: 'all compact'
                                      },
                                      {title: 'KYC Status', data: 'kyc_status', class: 'all compact', width: '10%'},
                                      {title: 'Created On', data: 'created_on', class: 'none compact'},
                                      {title: 'Created By', data: 'fname', class: 'none compact'},
                                      {title: 'Shop Name', data: 'shop_name', class: 'none compact'},
                                      {title: 'Bussiness Address', class: 'none compact',
                                          render: function (data, type, full, meta) {
                                              return full.shop_addr + ' ' + full.shop_state + ' ' + full.shop_city + ' ' + full.shop_pincode;
                                          }
                                      },
                                      {title: 'Permanent Address', data: 'permanent_addr', class: 'none compact'},
                                      {title: 'Plan Name', data: 'slab_name', class: 'all compact', width: '5%'},
                                      {title: 'Status', class: 'all compact', width: '10%'},
                                      { title: 'Action', class: 'all compact', "orderable": false, width: '10%',
                                          render: function (data, type, full, meta) {
                                              return '<button data-users="' + full.accnt_id + '" type="button" class="btn btn-space btn-primary btn-sm" id="updt_usr_dt" data-toggle="modal" data-target="#mdl_usr_dsc">update</button>'

                                          }
                                      },
                                  ],                              
                                "columnDefs": [
                                {
                                    "targets": 13,
                                    "defaultContent": " ",
                                    render: function(td, cellData, rowData, row, col) {

                                                if(rowData.login_active == 0) 
                                                {
                                                    return '<span class="failed-bg">Blocked</span>';
                                                }
                                                else if(rowData.txn_active == 0)
                                                {
                                                    return '<span class="pending-bg" style="width: auto">Transaction Inactive</span>';
                                                }
                                                else
                                                {
                                                    return '<span class="success-bg">Active User</span>';
                                                }
                                    }
                              }],
                                "lengthMenu": [
                                    [10, 20, 50, 100],
                                    [10, 20, 50, 100] // change per page values here
                                ],
                                buttons: [
                                    {extend: 'csv', className: 'btn-secondary'},
                                ],
                                dom: '<"datatable-header header-my search-data"Bfl>r<"table-scrollable"t><"datatable-footer"ip>',
                            });
                            userdataupdate(datatable);

                            

                            $('#newparent').on('change', function (e) {
                                var nid = $(this).val();

                                $.post('Manage/listChilds', {user_id: nid}, function (response) {
                                    if (response) 
                                    {
                                        if (response.Resp_code == 'UAC')
                                        {
                                            window.location.reload(true);
                                        }
                                        else if (response.Resp_code == 'RCS')
                                        {
                                            if (response.data) {
                                                change = 2;
                                                //console.log(response.data)
                                                $('#tableData').html(' <table  class="table  table-bordered" id="user_list_tbl"></table>');
                                                if (response.data.length > 0) {
                                                    if (id != 2) {
                                                        $('#showlist').html('');
                                                        var str = '';
                                                        str += '<div class="form-group m-form__group">';
                                                        str += '  <div class="col-xs-6">';
                                                        str += '  <select class="form-control" id="ChildData" name="ChildData" data-placeholder="Select Parent">';
                                                        str += '<option value="">Select User</option>';
                                                        $.each(response.data, function (k, v) {
                                                            opr1[v.accnt_id] = v;
                                                            str += '<option value=' + v.accnt_id + '>' + v.full_name + '</option>'
                                                        });
                                                        str += ' </select>';
                                                        str += '</div>';
                                                        str += '</div>';

                                                        $('#showlist').html(str);
                                                        $('#ChildData').select2();
                                                    } else {
                                                        //console.log('No Data');
                                                        $('#showlist').html('');
                                                        $('#newshowlist').html('');
                                                    }
                                                } else {
                                                    //console.log('No Data');
                                                    $('#showlist').html('');
                                                    $('#newshowlist').html('');
                                                }

                                                var datatable1 = $('#user_list_tbl').DataTable({
                                                    "processing": true,
                                                    "serverSide": false,
                                                    order: [],
                                                    data: response.data,
                                                    language: {
                                                        aria: {
                                                            "sortAscending": ": activate to sort column ascending",
                                                            "sortDescending": ": activate to sort column descending"
                                                        },
                                                        search: '<span>Filter:</span> _INPUT_',
                                                        searchPlaceholder: 'Type to filter...',
                                                        lengthMenu: '<span>Show:</span> _MENU_',
                                                        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
                                                        processing: '<span>&nbsp;&nbsp;<i class="icon-spinner4 spinner position-left"></i> LOADING...</span>'
                                                    },
                                                    select: {
                                                        style: 'multi'
                                                    },
                                                    responsive: true,
                                                      columns: [
                                                          {title: 'JLX India Id', data: 'accnt_id', class: 'all compact', width: '10%'},
                                                          {title: 'Full Name', data: 'full_name', class: 'all compact', width: '1%'},
                                                          {title: 'Role Name', data: 'role_name', class: 'all compact', width: '10%'},
                                                          {title: 'Mobile', data: 'mobile', class: 'all compact', width: '10%'},
                                                          {title: 'Email', data: 'email_id', class: 'none compact', width: '10%'},
                                                          {
                                                              title: 'Balance', data: 'rupee_balance', width: '10%',
                                                              render: function (data, type, full, meta) {

                                                                  return "Rs. " + data;
                                                              }
                                                              , class: 'all compact'
                                                          },
                                                          {title: 'KYC Status', data: 'kyc_status', class: 'all compact', width: '10%'},
                                                          {title: 'Created On', data: 'created_on', class: 'none compact'},
                                                          {title: 'Created By', data: 'fname', class: 'none compact'},
                                                          {title: 'Shop Name', data: 'shop_name', class: 'none compact'},
                                                          {title: 'Bussiness Address', class: 'none compact',
                                                              render: function (data, type, full, meta) {
                                                                  return full.shop_addr + ' ' + full.shop_state + ' ' + full.shop_city + ' ' + full.shop_pincode;
                                                              }
                                                          },
                                                          {title: 'Permanent Address', data: 'permanent_addr', class: 'none compact'},
                                                          {title: 'Plan Name', data: 'slab_name', class: 'all compact', width: '5%'},
                                                          {title: 'Status', class: 'all compact', width: '10%'},
                                                          { title: 'Action', class: 'all compact', "orderable": false, width: '10%',
                                                              render: function (data, type, full, meta) {
                                                                  return '<button data-users="' + full.accnt_id + '" type="button" class="btn btn-space btn-primary btn-sm" id="updt_usr_dt" data-toggle="modal" data-target="#mdl_usr_dsc">update</button>'

                                                              }
                                                          },
                                                      ],
                                                    "columnDefs": [
                                                    {
                                                        "targets": 13,
                                                        "defaultContent": " ",
                                                        render: function(td, cellData, rowData, row, col) {

                                                                    if(rowData.login_active == 0) 
                                                                    {
                                                                        return '<span class="failed-bg">Blocked</span>';
                                                                    }
                                                                    else if(rowData.txn_active == 0)
                                                                    {
                                                                        return '<span class="pending-bg" style="width: auto">Transaction Inactive</span>';
                                                                    }
                                                                    else
                                                                    {
                                                                        return '<span class="success-bg">Active User</span>';
                                                                    }
                                                        }
                                                  }],                                                      
                                                    "lengthMenu": [
                                                        [10, 20, 50, 100],
                                                        [10, 20, 50, 100] // change per page values here
                                                    ],
                                                    buttons: [
                                                        {extend: 'csv', className: 'btn-secondary'},
                                                    ],
                                                    dom: '<"datatable-header header-my search-data"Bfl>r<"table-scrollable"t><"datatable-footer"ip>',
                                                });
                                                userdataupdate(datatable1);

                                                $('#ChildData').on('change', function (e) {
                                                    var vid = $(this).val();

                                                    $.post('Manage/listChilds', {user_id: vid}, function (response) {
                                                        if (response) {
                                                            if (response.Resp_code == 'UAC')
                                                            {
                                                                window.location.reload(true);
                                                            }
                                                            else if (response.Resp_code == 'RCS')
                                                            {
                                                                change = 3;
                                                                if (response.data) {

                                                                    $('#tableData').html(' <table  class="table  table-bordered" id="user_list_tbl"></table>');
                                                            

                                                                    var datatable2 = $('#user_list_tbl').DataTable({
                                                                        "processing": true,
                                                                        "serverSide": false,
                                                                        order: [],
                                                                        data: response.data,
                                                                        language: {
                                                                            aria: {
                                                                                "sortAscending": ": activate to sort column ascending",
                                                                                "sortDescending": ": activate to sort column descending"
                                                                            },
                                                                            search: '<span>Filter:</span> _INPUT_',
                                                                            searchPlaceholder: 'Type to filter...',
                                                                            lengthMenu: '<span>Show:</span> _MENU_',
                                                                            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
                                                                            processing: '<span>&nbsp;&nbsp;<i class="icon-spinner4 spinner position-left"></i> LOADING...</span>'
                                                                        },
                                                                        select: {
                                                                            style: 'multi'
                                                                        },
                                                                        responsive: true,
                                                                      columns: [
                                                                          {title: 'JLX India Id', data: 'accnt_id', class: 'all compact', width: '10%'},
                                                                          {title: 'Full Name', data: 'full_name', class: 'all compact', width: '1%'},
                                                                          {title: 'Role Name', data: 'role_name', class: 'all compact', width: '10%'},
                                                                          {title: 'Mobile', data: 'mobile', class: 'all compact', width: '10%'},
                                                                          {title: 'Email', data: 'email_id', class: 'none compact', width: '10%'},
                                                                          {
                                                                              title: 'Balance', data: 'rupee_balance', width: '10%',
                                                                              render: function (data, type, full, meta) {

                                                                                  return "Rs. " + data;
                                                                              }
                                                                              , class: 'all compact'
                                                                          },
                                                                          {title: 'KYC Status', data: 'kyc_status', class: 'all compact', width: '10%'},
                                                                          {title: 'Created On', data: 'created_on', class: 'none compact'},
                                                                          {title: 'Created By', data: 'fname', class: 'none compact'},
                                                                          {title: 'Shop Name', data: 'shop_name', class: 'none compact'},
                                                                          {title: 'Bussiness Address', class: 'none compact',
                                                                              render: function (data, type, full, meta) {
                                                                                  return full.shop_addr + ' ' + full.shop_state + ' ' + full.shop_city + ' ' + full.shop_pincode;
                                                                              }
                                                                          },
                                                                          {title: 'Permanent Address', data: 'permanent_addr', class: 'none compact'},
                                                                          {title: 'Plan Name', data: 'slab_name', class: 'all compact', width: '5%'},
                                                                          {title: 'Status', class: 'all compact', width: '10%'},
                                                                          { title: 'Action', class: 'all compact', "orderable": false, width: '10%',
                                                                              render: function (data, type, full, meta) {
                                                                                  return '<button data-users="' + full.accnt_id + '" type="button" class="btn btn-space btn-primary btn-sm" id="updt_usr_dt" data-toggle="modal" data-target="#mdl_usr_dsc">update</button>'

                                                                              }
                                                                          },
                                                                      ],
                                                                        "columnDefs": [
                                                                        {
                                                                            "targets": 13,
                                                                            "defaultContent": " ",
                                                                            render: function(td, cellData, rowData, row, col) {

                                                                                        if(rowData.login_active == 0) 
                                                                                        {
                                                                                            return '<span class="failed-bg">Blocked</span>';
                                                                                        }
                                                                                        else if(rowData.txn_active == 0)
                                                                                        {
                                                                                            return '<span class="pending-bg" style="width: auto">Transaction Inactive</span>';
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            return '<span class="success-bg">Active User</span>';
                                                                                        }
                                                                            }
                                                                      }],
                                                                        "lengthMenu": [
                                                                            [10, 20, 50, 100],
                                                                            [10, 20, 50, 100] // change per page values here
                                                                        ],
                                                                        buttons: [
                                                                            {extend: 'csv', className: 'btn-secondary'},
                                                                        ],
                                                                        dom: '<"datatable-header header-my search-data"Bfl>r<"table-scrollable"t><"datatable-footer"ip>',
                                                                    });
                                                                    userdataupdate(datatable2);
                                                                }
                                                            } else 
                                                            {

                                                                toastr.error(response.Resp_desc);
                                                            }
                                                        }
                                                    }, 'json').fail(function (err) {
                                                        throw err;
                                                    })
                                                });

                                            }
                                        } 
                                        else 
                                        {
                                            toastr.error(response.Resp_desc);
                                        }
                                    }
                                }, 'json').fail(function (err) {
                                    throw err;
                                })
                            });

                        }
                    } 
                    else if(response.Resp_code == 'ERR') 
                    {
                        toastr.error(response.Resp_desc);
                    }
                }
            }, 'json').fail(function (err) {
                throw err;
            })
        });
    
        var usr_list_tbl = $('#user_list_tbl').DataTable({
            "serverSide": true,
            "ajax": {
                url: "Manage/loadUsers",
                type: 'post',
                "dataSrc": function (json) 
                {
                    if (json.Resp_code == 'UAC')
                    {
                        window.location.reload(true);
                    } 
                    else if (json.Resp_code == 'ERR')
                    {
                        toastr.error(json.Resp_desc, 'Oops!');
                    }
                    else if(json.Resp_code == 'RCS')
                    {
                        return json.data;                        
                    }
                }
            },
            "responsive": true,
            language: {
                aria: {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "sZeroRecords": "No records to displays",
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
                processing: '<span>&nbsp;&nbsp;<i class="icon-spinner4 spinner position-left"></i> LOADING...</span>'
            },
            columns: [
                {title: 'JLX India Id', data: 'accnt_id', class: 'all compact', width: '10%'},
                {title: 'Full Name', data: 'full_name', class: 'all compact', width: '1%'},
                {title: 'Role Name', data: 'role_name', class: 'all compact', width: '10%'},
                {title: 'Mobile', data: 'mobile', class: 'all compact', width: '10%'},
                {title: 'Email', data: 'email_id', class: 'none compact', width: '10%'},
                {
                    title: 'Balance', data: 'rupee_balance', width: '10%',
                    render: function (data, type, full, meta) {

                        return "Rs. " + data;
                    }
                    , class: 'all compact'
                },
                {title: 'KYC Status', data: 'kyc_status', class: 'all compact', width: '10%'},
                {title: 'Created On', data: 'created_on', class: 'none compact'},
                {title: 'Created By', data: 'fname', class: 'none compact'},
                {title: 'Shop Name', data: 'shop_name', class: 'none compact'},
                {title: 'Bussiness Address', class: 'none compact',
                    render: function (data, type, full, meta) {
                        return full.shop_addr + ' ' + full.shop_state + ' ' + full.shop_city + ' ' + full.shop_pincode;
                    }
                },
                {title: 'Permanent Address', data: 'permanent_addr', class: 'none compact'},
                {title: 'Plan Name', data: 'slab_name', class: 'all compact', width: '5%'},
                {title: 'Status', class: 'all compact', width: '10%'},
                { title: 'Action', class: 'all compact', "orderable": false, width: '10%',
                    render: function (data, type, full, meta) {
                        return '<button data-users="' + full.accnt_id + '" type="button" class="btn btn-space btn-primary btn-sm" id="updt_usr_dt" data-toggle="modal" data-target="#mdl_usr_dsc">update</button>'

                    }
                },
            ],
            "columnDefs": [
            {
                "targets": 13,
                "defaultContent": " ",
                render: function(td, cellData, rowData, row, col) {

                            if(rowData.login_active == 0) 
                            {
                                return '<span class="failed-bg">Blocked</span>';
                            }
                            else if(rowData.txn_active == 0)
                            {
                                return '<span class="pending-bg" style="width: auto">Transaction Inactive</span>';
                            }
                            else
                            {
                                return '<span class="success-bg">Active User</span>';
                            }
                }
          }],
           buttons: [
               {extend: 'csv', className: 'btn-secondary'},
           ],
           dom: '<"datatable-header header-my search-data"Bfl>r<"table-scrollable"t><"datatable-footer"ip>',
        });

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
                        ////console.log(price_regex.test(k));
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

            
            $('#bnk_nar,#bnk_ref').on('keyup keypress blur', function (e) {
                var $this = $(this);
                if (this.value !== "") {
                    return !0
                }
            });

        }

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
                        ////console.log(price_regex.test(k));
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

        var valid_usr_dtl = function () {

            $('#full_nam').keypress(function (e) {
                var k = e.keyCode || e.which,
                        str = $(this)[0].value,
                        length = str.length;
                if (e.type == 'keypress') {
                    if (k != 8 && k != 9) {

                        k = String.fromCharCode(k);
                        var bsname_regex = /^[a-zA-Z]+$/;
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
                ////console.log(str);
                length = str.length;

                if (e.type == 'keypress')
                {
                    if (k != 8 && k != 9)
                    {
                        k = String.fromCharCode(k);
                        var mb_regex = /[0-9]/;
                        ////console.log(mb_regex.test(k));
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

        usr_list_tbl.on('click', '#updt_usr_dt', function () {
            var usrid = $(this).data('users');
            var row = $(this).closest('tr');
            var showtd1 = usr_list_tbl.row(row).data();
            //console.log(showtd1);
            if (showtd1['accnt_id'] == usrid) {

                var str = '<div class="modal fade" id="mdl_usr_dsc_' + showtd1.accnt_id + '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
                str += '<div class="modal-dialog modal-dialog-centered" role="document" id="lrge_modal">';
                str += '<div class="modal-content">';
                str += '<div class="modal-header">';
                str += '<h5 class="modal-title" id="head_ttl">Manage ' + showtd1.shop_name + ' KYC Documents';

                str += '</h5>';
                str += '<h5 class="modal-title" id="head_ttl2" style="display:none;">';

                str += '</h5>';
                str += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                str += '<span aria-hidden="true">&times;';

                str += '</span>';
                str += '</button>';
                str += '</div>';
                str += '<div class="modal-body modal-lg">';
                str += '<div  id="first_div" style="">';
                str += '<div class="m-portlet__body">';
                //-----------------------------------------
                str += '<div class="m-widget13">';
                str += '<div class="m-widget13__item">';
                str += '<span class="m-widget13__desc m--align-center">';
                str += '<label class="mr-3">Transaction</label>';
                if(showtd1.txn_active == 1)
                {
                    str +='<input type="checkbox" id="change_txnActive" checked data-toggle="toggle" data-txnActive ="' + showtd1.accnt_id + '" >';                    
                }
                else
                {
                    str +='<input type="checkbox" id="change_txnActive" data-toggle="toggle" data-txnActive ="' + showtd1.accnt_id + '" >';
                }
                str += '</span>';
                str += '<span class="m-widget13__desc m--align-center">';
                str += '<label class="mr-3">login</label>';
                if(showtd1.login_active == 1)
                {
                    str +='<input type="checkbox" id="change_lgnActive" checked data-toggle="toggle" data-lgnActive ="' + showtd1.accnt_id + '">';                  
                }
                else
                {
                    str +='<input type="checkbox" id="change_lgnActive" data-toggle="toggle" data-lgnActive ="' + showtd1.accnt_id + '">';
                }                
                str += '</span>';
                str += '</div>';
                str += '</div>';
                //--------------------------------------------
                str += '<div class="m-widget13">';
                str += '<div class="m-widget13__item">';
                str += '<span class="m-widget13__desc m--align-right">Add Balance';
                str += '</span>';
                str += '<span class="m-widget13__text m-widget13__text-bolder">';
                str += '<button type="button" class="btn btn-space btn-primary btn-sm" data-add_blnc="' + showtd1.accnt_id + '" id="add_blnc">Add Balance</button>';
                str += '</span>';
                str += '</div>';
                    if(urole_id == 1)
                    {
                        str += '<div class="m-widget13__item">';
                        str += '<span class="m-widget13__desc m--align-right">Deduct Balance';
                        str += '</span>';
                        str += '<span class="m-widget13__text m-widget13__text-bolder">';
                        str += '<button type="button" class="btn btn-space btn-primary btn-sm" data-dedct_blnc="' + showtd1.accnt_id + '" id="dedct_blnc">Deduct Balance</button>';
                        str += '</span>';
                        str += '</div>';

                        str += '<div class="m-widget13__item">';
                        str += '<span class="m-widget13__desc m--align-right">Update User Details';
                        str += '</span>';
                        str += '<span class="m-widget13__text m-widget13__text-bolder">';
                        str += '<button class="btn btn-space btn-primary btn-sm" data-actvt_usr="' + showtd1.accnt_id + '" id="updt_usr_dtl">Update User Details</button>';
                        str += '</span>';
                        str += '</div>';

                        str += '<div class="m-widget13__item">';
                        str += '<span class="m-widget13__desc m--align-right">Change 2FA';
                        str += '</span>';
                        str += '<span class="m-widget13__text m-widget13__text-bolder">';
                        str += '<button type="button" class="btn btn-space btn-primary btn-sm"  data-chg2fa="' + showtd1.accnt_id + '" id="change2fa">Change 2FA Settings</button>';
                        str += '</span>';
                        str += '</div>';

                        str += '<div class="m-widget13__item">';
                        str += '<span class="m-widget13__desc m--align-right">KYC document';
                        str += '</span>';
                        str += '<span class="m-widget13__text m-widget13__text-bolder">';
                        str += '<button type="button" class="btn btn-space btn-primary btn-sm"  data-kyc_doc="' + showtd1.accnt_id + '" id="kyc_data">KYC document</button>';
                        str += '</span>';
                        str += '</div>';

                        str += '<div class="m-widget13__item">';
                        str += '<span class="m-widget13__desc m--align-right">Parent Mapping';
                        str += '</span>';
                        str += '<span class="m-widget13__text m-widget13__text-bolder">';
                        str += '<button type="button" class="btn btn-space btn-primary btn-sm"  data-p_mapping="' + showtd1.accnt_id + '" data-pid="' + showtd1.parent_id + '" id="parent_mapping">Parent Mapping</button>';
                        str += '</span>';
                        str += '</div>';

                        str += '<div class="m-widget13__item">';
                        str += '<span class="m-widget13__desc m--align-right">Manage Plan';
                        str += '</span>';
                        str += '<span class="m-widget13__text m-widget13__text-bolder">';
                        str += '<button type="button" class="btn btn-space btn-primary btn-sm"  data-mng_pln="' + showtd1.accnt_id + '" id="mng_pln">Update Plan</button>';
                        str += '</span>';
                        str += '</div>';

                    }

                    str += '</div>';
                    str += '</div>';
                    str += '</div>';
                    str += '<div id="usr_blnc_form" style="display:none;">';
                    str += '</div>';
                    str += '<div id="usr_deduct_blnc_form" style="display:none;">';
                    str += '</div>';
                    str += '<div id="update2fadiv" style="display:none;">';
                    str += '</div>';
                    str += '<div id="usr_updt_dtl_form" style="display:none;">';
                    str += '</div>';
                    str += '<div id="kyc_dtl_documents_' + showtd1.accnt_id + '" style="display:none;">';
                    str += '</div>';
                    str += '<div id="mng_plan_form_' + showtd1.accnt_id + '" style="display:none;">';
                    str += '</div>';
                    str += '</div>';
                    str += '</div>';
                    str += '</div>';
                    str += '</div>';


                $('body').append(str);

                $('#change_txnActive, #change_lgnActive').bootstrapToggle();

                $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true,
                });


                $('#mdl_usr_dsc_' + showtd1.accnt_id + '').on('hidden.bs.modal', function () {
                    $('#mdl_usr_dsc_' + showtd1.accnt_id + '').remove();
                    usr_list_tbl.ajax.reload(null, false);
                });


                /****activate user ***/
                if(urole_id == 1)
                {


                    /********update 2fa************/

                    $('#change2fa').click(function(e){
                        e.preventDefault();
                        
                        var checked = "";
                        var style = "";

                        if(showtd1['2fa_status'] == 1)
                        {
                            checked = "checked:checked";
                        }
                        else
                        {
                            style = 'style = "display:none"';
                        }

                        var acid = $(this).data('chg2fa');
                        $('#head_ttl').show().html('Update 2FA Setting of '+showtd1.full_name);

                        $("#first_div").hide();

                        var str = '<div class="row" id="sec_form_div">';
                        str += '<div class="col-sm-12">';
                        str += '<div class="panel-body">';

                         //------------------------------------------
                         str += '<form id="twofaform">';

                         str += '<div class="form-group m-form__group row m-0-set">'; 

                        str += '<div class="col-lg-6">';
                        str += '<div class="col m--align-left">';
                        str += '<label class="m-checkbox m-checkbox--focus">';                
                        str += '<input type="checkbox" name="2fachangecheck" id="2fachangecheck" '+checked+'>';
                        str += 'Activate 2fa';
                        str += '<span></span>';
                        str += '</label>';
                        str += '</div>';

                        str += '<div id="2fatoggle" class="form-group" '+style+'>';                
                        
                        
                        str += '<select class="form-control" name = "twofaselect" id="twofaselect">';
                        str += '<option value="" disabled>Please Select 2FA Setting</option>';
                        if(twofasettings != undefined)
                        {
                           var i = 0
                            $.each(twofasettings, function(key, value){
                                if(value.twofa_configid == showtd1.twofa_configid)
                                {
                                    str += '<option value = "'+value.twofa_configid+'" selected = "selected">'+value.name+'</option>';
                                }
                                else
                                {
                                    str += '<option value = "'+value.twofa_configid+'">'+value.name+'</option>';
                                }
                            });
                        }
                         str += '</select>';
                         str += '<span data-for="2fatoggle"></span>';
                         str += '</div>';
                         str += '</div>';
                         str += '</div>';
                         //--------------------------------------------
                         str += '<div class="modal-footer">';
                         str += '<button type="submit" class="btn btn-secondary"  id="2fa_bck">Back</button>';
                         str += '<button type="submit" class="btn btn-space btn-primary legitRipple ladda-button" data-style="zoom-in" id="twofa_upt">Update</button>';
                         str += '</div>';
                         str += '</form>';
                         str += '</div>';
                         str += '</div>';
                         str += '</div>';

                        $('#update2fadiv').html(str).show();

                        var config = $('#twofaselect').val();
                        var configcheck;

                        $('#2fachangecheck').change(function(){
                            if ($(this).prop('checked'))
                            {
                                $('#2fatoggle').css('display','block');
                                configcheck = true;
                            } 
                            else
                            {
                                $('#2fatoggle').css('display','none');
                                config = 0;
                                configcheck = false;
                            }
                        });

                        $('#2fa_bck').click(function(){
                            $("#update2fadiv").hide().html('');
                            $("#first_div").show();
                        }); 

                        $('#twofa_upt').click(function(){
                            e.preventDefault();
                           la = $('#twofa_upt').ladda();


                           $.ajax({
                               url: 'Manage/update2fa',
                               dataType: 'json',
                               data: {
                                id: showtd1.accnt_id,
                                configid: config,
                                configcheck: configcheck
                               },
                               type: 'POST',
                               beforeSend: function(){
                                la.ladda('start');
                               },
                               success:function(response){
                                   
                                   if(response.Resp_code == 'RCS')
                                   { 
                                       toastr.info(response.Resp_desc);
                                       $("#update2fadiv").hide().html('');
                                       $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal('hide');
                                       $("#first_div").hide();

                                   }
                                   else if(response.Resp_code == 'ERR')
                                   {
                                      toastr.error(response.Resp_desc);
                                   }
                                   else if(response.Resp_code == 'UAC')
                                   {
                                     window.location.reload(true);
                                   }

                                   la.ladda('stop');
                               },
                               error: function() {
                                la.ladda('stop');
                               }
                           });
                        });

                    });


                    /********End Update 2fa*******/

                    /******manage plan ************/

                    $('#mng_pln').click(function (e) {
                        e.preventDefault();
                        var acid = $(this).data('mng_pln');
                        //console.log(acid);

                        if (acid == showtd1.accnt_id) {
                            $("#head_ttl").hide();
                            $('#head_ttl2').show().html('Manage ' + showtd1.full_name + ' Plan');

                            //  $('#mng_plan_form_'+showtd1.accnt_id+'').show();
                            $("#first_div").hide();
                            var plan_array = {}
                            $.ajax({
                                url: 'Manage/getPlans',
                                dataType: "json",
                                type: 'post',
                                data: {role_id: showtd1.role_id},
                                beforeSend: function (data) {

                                    var loader = ' <div class="timeline-wrapper m-t-md m-b-xxl " id="timeline-wrapper-pro"><div class="timeline-item m-b-xl" ><div class="animated-background"><div class="background-masker content-top"></div><div class="background-masker content-first-end"></div><div class="background-masker content-second-line"></div><div class="background-masker content-second-end"></div><div class="background-masker content-third-line"></div><div class="background-masker content-third-end"></div><div class="background-masker content-top-1"></div><div class="background-masker content-first-end-1"></div><div class="background-masker content-second-line-1"></div><div class="background-masker content-second-end-1"></div><div class="background-masker content-third-line-1"></div><div class="background-masker content-third-end-1"></div><div class="background-masker content-top-2"></div><div class="background-masker content-first-end-2"></div><div class="background-masker content-second-line-2"></div><div class="background-masker content-second-end-2"></div><div class="background-masker content-third-line-2"></div><div class="background-masker content-third-end-2"></div></div></div></div>';
                                    $('#mng_plan_form_' + showtd1.accnt_id).html(loader).show();
                                },
                                success: function (data) {
                                    if (data.Resp_code == 'RCS') 
                                    {

                                        var str = '<div class="row" id="mngplnrt_form_div">';
                                        str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                                        str += '<div class="panel-body">';
                                        str += '<form action="#" id="updt_rate_form">';
                                        str += '<div class="row">';
                                        str += ' <div class="col-md-12">';
                                        str += ' <div class="form-group">';

                                        //console.log(plan_array);
                                        str += ' <label>Plan</label>';
                                        str += '<div class="input-group">';
                                        str += '<select class="form-control custom-select" name="plan_name" id="plan_name">';
                                        str += '<option value="default">Select Plan</option>';
                                        $.each(data.data, function (k, v) {
                                            plan_array[v.id] = v
                                            var sel_rt = (showtd1.plan_id == v.id) ? 'selected' : ''
                                            str += '<option value="' + v.id + '" ' + sel_rt + '>' + v.slab_name + '</option>';
                                        })
                                        str += '</select>';
                                        str += '</div>';
                                        str += '</div>';
                                        str += '</div>';
                                        str += '</div>';
                                        str += '<div class="modal-footer">';
                                        str += '<button type="submit" class="btn btn-secondary"  id="bcl_rate">Back</button>';
                                        str += '<button type="submit" class="btn btn-space btn-primary legitRipple ladda-button"data-style="zoom-in" id="rt_update">Update</button>';
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
                                    } 
                                    else if (data.Resp_code == 'UAC') 
                                    {
                                        window.location.reload(true);

                                    } 
                                    else if(data.Resp_code == "ERR") 
                                    {
                                        $('#head_ttl2').hide();
                                        $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');
                                        $("#first_div").show();
                                        $('#mng_plan_form_' + showtd1.accnt_id + '').hide().html('');
                                        toastr.error(data.Resp_desc);
                                    }
                                }
                            });

                        }



                    });
                    var update_plan_view = function (showtd1, plan_array) {
                        var la = $('#rt_update').ladda();
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

                                la.ladda('start');

                                $.post('Manage/updatePlan', $(form).serialize() + "&showtd1=" + showtd1.accnt_id + "&role_id=" + showtd1.role_id, function (response) {

                                    if (response) {

                                        if (response.Resp_code == 'RCS')
                                        {
                                            toastr.info(response.Resp_desc);

                                            $('#head_ttl2').hide();
                                            $('#head_ttl').show().html('Action User Details of ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');


                                            $('#updt_rate_form').hide().html('');
                                            $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal('hide');

                                        } 
                                        else if (response.Resp_code == 'UAC') 
                                        {
                                            window.location.reload(true);

                                        } 
                                        else if(response.Resp_code == 'ERR') 
                                        {
                                            toastr.error(response.Resp_desc);
                                        }
                                      
                                    }


                                }, 'json').fail(function (error) {

                                });

                                return false;
                            }

                        });

                    }
                    /******end manage plan*******/


                    ///***update user details******////
                    var gen_obj = {'MALE': 'MALE', 'FEMALE': 'FEMALE'};
                    $('#updt_usr_dtl').click(function (e) {
                        e.preventDefault();
                        $('#head_ttl').show().html('Update ' + showtd1.full_name + ' (Account: ' + showtd1.accnt_id + ')');
                        $("#usr_updt_dtl_form").show();
                        $("#first_div").hide();
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
                        str += '<label>Date</label>';
                        str += ' <div class="input-group">';
                        str += '<input type="text" class="form-control m-input" data-provide="datepicker" value ="'+showtd1.dob+'"data-provide="datepicker-inline" id="dob" name="dob" readonly="true" placeholder="Enter Date of Birth">';
                        str += '  </div>';
                        str += ' </div>';
                        str += ' </div>';

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
                        str += '<button type="submit" class="btn btn-space btn-primary legitRipple ladda-button" data-style="zoom-in" id="updt_us">Update User</button>';
                        str += '</div>';
                        str += '</form>';
                        str += '</div>';
                        str += '</div>';
                        str += '</div>';
                        $('#usr_updt_dtl_form').html(str).show();
                        profile_update();   

                         $('#dob').datepicker({
                            format: 'dd-mm-yyyy',
                            startDate: '01-01-1970',
                            endDate: "today"
                        });

                        $('#updt_back_us').click(function () {
                            $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');
                            $("#first_div").show();
                            $("#usr_updt_dtl_form").hide().html('');
                        });

                    });
                    var profile_update = function () {
                        $.validator.methods.digits = function (value, element) {

                            return this.optional(element) || /^[6789][0-9]{9}$/.test(value);

                        }
                        $.validator.methods.pannumber = function (value, element) {

                            return this.optional(element) || /^[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}$/.test(value);

                        }
                        $.validator.methods.pindigits = function (value, element) {

                            return this.optional(element) || /^[1-9][0-9]{5}$/.test(value);

                        }
                        $.validator.addMethod("alpha", function (value, element) {

                            return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
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

                            return this.optional(element) || /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);

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
                                    alpha: true,
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
                                    exactlength: 10,
                                    pannumber: true
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
                                dob: {
                                    required: true
                                }
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
                                    exactlength: "Please enter 10 digits for a valid PAN number.",
                                    pannumber: 'Please enter a valid PAN'
                                },
                                pin_code: {
                                    required: "Shop Pincode is required.",
                                    pindigits: 'Please enter a valid PAN'
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
                                dob: {
                                    required: 'Date of Birth is Required'
                                }
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
                                la.ladda('start');
                                $.post('Manage/updateUser', $(form).serialize() + "&showtd1=" + showtd1.accnt_id, function (response) {

                                    if (response) 
                                    {

                                        if (response.Resp_code == 'RCS')
                                        {
                                            toastr.info(response.Resp_desc);
                                            $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal('hide');

                                        } 
                                        else if (response.Resp_code == 'UAC')
                                        {
                                            window.location.reload(true);

                                        } 
                                        else if(response.Resp_code == 'ERR') 
                                        {
                                            $('#lrge_modal').find('.error-alert').remove();
                                            if(response.Resp_desc instanceof Object)
                                            {
                                                $.each(response.Resp_desc, function(key, value) {
                                                $('#'+key).parent().parent().append('<div class="text-danger error-alert">'+value+'<div>');
                                                });
                                            }
                                            else
                                            {
                                                toastr.error(response.Resp_desc);                                                
                                            }
                                        }
                                    }
                                        la.ladda('stop');

                                }, 'json').fail(function (error) {

                                    la.ladda('stop');

                                });

                                return false;
                            }

                        });
                    }

                    //**************Change Txn Status****************//
                    $('#change_txnActive').change(function(e)
                    {
                        e.preventDefault();
                        if($(this).prop('checked'))
                        {
                            var acid = $(this).data('txnactive');
                            if (acid == showtd1.accnt_id) {
                                var dataString = {'userID': acid};
                                $.ajax({
                                    url: 'Manage/activateDeactivateUserTxn',
                                    dataType: "json",
                                    data: dataString,
                                    type: 'post',
                                 success: function (data) {
                                     
                                     if (data.Resp_code == 'RCS') 
                                     {
                                         toastr.success(data.Resp_desc);
                                         $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal('hide');
                                     } 
                                     else if(data.Resp_code == 'ERR') 
                                     {
                                         toastr.error(data.Resp_desc);
                                     }
                                     else if(data.Resp_code == 'UAC')
                                     {
                                        window.location.reload(true);
                                     }
                                    }///success function close       
                                });
                            }
                        }
                        else
                        {
                            var acid = $(this).data('txnactive');
                            console.log(acid);
                            if (acid == showtd1.accnt_id) {
                                var dataString = {'userID': acid};
                                $.ajax({
                                    url: 'Manage/activateDeactivateUserTxn',
                                    dataType: "json",
                                    data: dataString,
                                    type: 'post',
                                 success: function (data) {
                                     
                                     if (data.Resp_code == 'RCS') 
                                     {
                                         toastr.success(data.Resp_desc);
                                         $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal('hide');
                                     } 
                                     else if(data.Resp_code == 'ERR') 
                                     {
                                         toastr.error(data.Resp_desc);
                                     }
                                     else if(data.Resp_code == 'UAC')
                                     {
                                        window.location.reload(true);
                                     }
                                    }///success function close       
                                });
                            }
                        }
                    })

                ///*************End Change Txn Status*****************///

                 ///****update user details section end*****////
                 
                 $('#change_lgnActive').change(function(e)
                 {
                     e.preventDefault();
                     if($(this).prop('checked'))
                     {
                        console.log('here');
                         var acid = $(this).data('lgnactive');
                         if (acid == showtd1.accnt_id) 
                         {
                             var dataString = {'userID': acid};
                             $.ajax({
                                 url: 'Manage/activateDeactivateUser',
                                 dataType: "json",
                                 data: dataString,
                                 type: 'post',               
                                 success: function (data) {
                                     
                                     if (data.Resp_code == 'RCS') 
                                     {
                                         toastr.success(data.Resp_desc);
                                         $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal('hide');
                                     } 
                                     else if(data.Resp_code == 'ERR') 
                                     {
                                         toastr.error(data.Resp_desc);
                                     }
                                     else if(data.Resp_code == 'UAC')
                                     {
                                        window.location.reload(true);
                                     }
                                 }///success function close       
                             });
                         }
                     }
                     else
                     {
                        var acid = $(this).data('lgnactive');
                        if (acid == showtd1.accnt_id) {
                            var dataString = {'userID': acid};
                            $.ajax({
                                url: 'Manage/activateDeactivateUser',
                                dataType: "json",
                                data: dataString,
                                type: 'post',
                             success: function (data) {
                                 
                                 if (data.Resp_code == 'RCS') 
                                 {
                                     toastr.success(data.Resp_desc);
                                     $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal('hide');
                                 } 
                                 else if(data.Resp_code == 'ERR') 
                                 {
                                     toastr.error(data.Resp_desc);
                                 }
                                 else if(data.Resp_code == 'UAC')
                                 {
                                    window.location.reload(true);
                                 }
                                }///success function close       
                            });
                        }
                     }
                 })

                 //******************Change Login Update***************//

                    var update_parent = function (showtd1, parent_array) {

                            var la = $('#rt_update_parent').ladda();
                            $.validator.addMethod("valueNotEquals", function (value, element, arg) {
                                return (value in parent_array);
                            }, "Value must not equal arg.");

                            $('#updt_parent_form').validate({
                                errorElement: 'span', //default input error message container
                                errorClass: 'help-block', // default input error message class

                                rules: {
                                    parent_name: {
                                        valueNotEquals: true,
                                    },
                                },
                                messages: {
                                    parent_name: {
                                        valueNotEquals: "Please select parent "
                                    },
                                },
                                invalidHandler: function (event, validator) { //display error alert on form submit
                                    $('.alert-danger', $('#updt_btn_rtlr')).show();
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

                                    la.ladda('start');

                                    $.post('Manage/updateParent', $(form).serialize() + "&showtd1=" + showtd1.accnt_id + "&role_id=" + showtd1.role_id, function (response) {

                                        if (response) {

                                            if (response.Resp_code == 'RCS')
                                            {
                                                toastr.info(response.Resp_desc);

                                                $('#head_ttl2').hide();
                                                $('#head_ttl').show().html('Action User Details of ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');


                                                $('#updt_rate_form').hide().html('');
                                                $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal('hide');

                                            } else if (response.Resp_code == 'UAC') {


                                                window.location.reload(true);


                                            } 
                                            else if (response.Resp_code == 'ERR') 
                                            {
                                                toastr.error(response.Resp_desc);

                                            }
                                           
                                        }

                                        la.ladda('stop');

                                    }, 'json').fail(function (error) {
                                        la.ladda('stop');
                                    });

                                    return false;
                                }

                            });

                        }

                    $('#parent_mapping').click(function (e) {
                        e.preventDefault();
                        var acid = $(this).data('p_mapping');
                        var parent = $(this).data('pid');

                        if (acid == showtd1.accnt_id) {
                            $("#head_ttl").hide();
                            $('#head_ttl2').show().html('Manage ' + showtd1.full_name + ' Parent Mapping');

                            //  $('#mng_plan_form_'+showtd1.accnt_id+'').show();
                            $("#first_div").hide();
                            var parent_array = {}
                            $.ajax({
                                url: 'Manage/GetParentListData',
                                dataType: "json",
                                type: 'post',
                                data: {role_id: showtd1.role_id},
                                beforeSend: function (data) {

                                    var loader = ' <div class="timeline-wrapper m-t-md m-b-xxl " id="timeline-wrapper-pro"><div class="timeline-item m-b-xl" ><div class="animated-background"><div class="background-masker content-top"></div><div class="background-masker content-first-end"></div><div class="background-masker content-second-line"></div><div class="background-masker content-second-end"></div><div class="background-masker content-third-line"></div><div class="background-masker content-third-end"></div><div class="background-masker content-top-1"></div><div class="background-masker content-first-end-1"></div><div class="background-masker content-second-line-1"></div><div class="background-masker content-second-end-1"></div><div class="background-masker content-third-line-1"></div><div class="background-masker content-third-end-1"></div><div class="background-masker content-top-2"></div><div class="background-masker content-first-end-2"></div><div class="background-masker content-second-line-2"></div><div class="background-masker content-second-end-2"></div><div class="background-masker content-third-line-2"></div><div class="background-masker content-third-end-2"></div></div></div></div>';
                                    $('#mng_plan_form_' + showtd1.accnt_id + '').html(loader).show();
                                },
                                success: function (data) {
                                    if (data.Resp_code == 'RCS') {
                                        //console.log(parent)
                                        //console.log(data.msg)
                                        var str = '<div class="row" id="mngplnrt_form_div">';
                                        str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                                        str += '<div class="panel-body">';
                                        str += '<form action="#" id="updt_parent_form">';
                                        str += '<div class="row">';
                                        str += ' <div class="col-md-12">';
                                        str += ' <div class="form-group">';

                                        str += ' <label>Parent</label>';
                                        str += '<div class="input-group">';
                                        str += '<select class="form-control custom-select" name="parent_name" id="parent_name">';
                                        str += '<option value="default">Select Parent</option>';
                                        $.each(data.data, function (k, v) {
                                            parent_array[v.accnt_id] = v;
                                            if (parent != 0) {
                                                var sel_rt = (parent == v.accnt_id) ? 'selected' : ''
                                            } else {
                                                var sel_rt = '';
                                            }
                                            str += '<option value="' + v.accnt_id + '" ' + sel_rt + '>' + v.full_name + '</option>';
                                        })
                                        str += '</select>';
                                        str += '</div>';
                                        str += '</div>';
                                        str += '</div>';
                                        str += '</div>';
                                        str += '<div class="modal-footer">';
                                        str += '<button type="submit" class="btn btn-secondary"  id="bcl_parent">Back</button>';
                                        str += '<button type="submit" class="btn btn-space btn-primary legitRipple ladda-button"data-style="zoom-in" id="rt_update_parent">Update</button>';
                                        str += '</div>';
                                        str += '</div>';
                                        str += '</form>';
                                        str += '</div>';
                                        str += '</div>';

                                        $('#mng_plan_form_' + showtd1.accnt_id + '').html(str).show();

                                        $('#bcl_parent').click(function (e) {
                                            e.preventDefault();
                                            $('#head_ttl2').hide();
                                            $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');

                                            $("#first_div").show();
                                            $('#mng_plan_form_' + showtd1.accnt_id + '').hide().html('');
                                        })

                                        update_parent(showtd1, parent_array)

                                    } else if (data.Resp_code == 'UAC') {

                                        window.location.reload(true);

                                    } else if(data.Resp_code == 'ERR') {
                                        $('#head_ttl2').hide();
                                        $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');
                                        $("#first_div").show();
                                        $('#mng_plan_form_' + showtd1.accnt_id + '').hide().html('');
                                        toastr.error(data.Resp_desc);
                                    }
                                }
                            });

                        }
                    });

                }
                /*---add blnc*****---------*/
                var pyamnt_obj = {'Bank Transfer': 'Bank Transfer', 'Cash': 'Cash', 'Cheque/DD': 'Cheque/DD', 'Admin': 'Admin'};
                $('#add_blnc').click(function (e) {
                    e.preventDefault();
                    $("#head_ttl").hide();
                    $('#head_ttl2').show().html('Manage ' + showtd1.full_name + ' Balance');
                    $("#usr_blnc_form").show();
                    $("#first_div").hide();
                    var str = '<div class="row" id="mngblnc_form_div">';
                    str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                    str += '<div class="panel-body">';
                    str += '<form action="#" id="updt_users_blnc_form">';
                    str += '<div class="row">';

                    str += '<div class="col-md-12">';
                    str += '<div class="form-group">';
                    str += '<label>Amount</label>';
                    str += '<div class="input-group">';
                    str += '<input type="tel" placeholder="Credit Amount" class="form-control" name="blnc" id="blnc" >';
                    str += '</div>';
                    str += '</div>';
                    str += ' </div>';

                    if(urole_id == 1)
                    {
                        str += ' <div class="col-md-12">';
                        str += ' <div class="form-group">';
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
                    }
                    str += ' <div class="col-md-12">';
                    str += ' <div class="form-group has-error">';

                    str += ' <label>Bank Reference</label>';
                    str += '<div class="input-group">';
                    str += '  <input type="text" placeholder="Enter bank reference" class="form-control" name="bnk_ref" id="bnk_ref">';
                    str += '</div>';
                    str += '</div>';
                    str += '</div>';
                    if(urole_id == 1)
                    {
                        str += ' <div class="col-md-12">';
                        str += ' <div class="form-group">';
                        str += '  <label>Bank Naration</label>';
                        str += ' <div class="input-group">';
                        str += ' <input type="text" placeholder="Enter bank naration" class="form-control" name="bnk_nar" id="bnk_nar">';
                        str += ' </div>';
                        str += ' </div>';
                        str += ' </div>';
                    }
                    str += '</div>';
                    str += '<div class="modal-footer">';
                    str += '<button type="submit" class="btn btn-secondary"  id="bck1_bnk">Back</button>';
                    str += '<button type="submit" class="btn btn-space btn-primary legitRipple ladda-button"data-style="zoom-in" id="next_prc">Proceed</button>';
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
                var process_add = false;
                var user_mng_balnc = function (showtd1) {

                    $.validator.addMethod("numeric", function (value, element) {

                        return this.optional(element) || value == value.match(/^\s*(?=.*[0-9])\d*(?:\.\d{1,2})?\s*$/);

                    }),
                            $.validator.addMethod("alpha_n", function (value, element) {

                                return this.optional(element) || value == value.match(/^[a-zA-Z0-9 ]+$/);
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
                            params.blnc = $('#blnc').val();
                            params.py_amnt = $('#py_amnt').val();
                            params.bnk_ref = $('#bnk_ref').val();
                            params.bnk_nar = $('#bnk_nar').val();
                            params.showtd1 = showtd1.accnt_id;


                            params.py_amnt = typeof (params.py_amnt) != 'undefined' ? params.py_amnt : 'Cash';
                            params.bnk_nar = typeof (params.bnk_nar) != 'undefined' ? params.bnk_nar : 'Add Balance';
                            var str = '<div class="row" id="prcd_form_div">';
                            str += '<div class="col-sm-12">';
                            str += '<div class="panel-body">';
                            str += '<form action="#" id="show_blnc_form">';
                            str += '<div class="row">';
                            str += '<div class="col-md-12">';
                            str += '<div class="form-group">';
                            str += '<label>Amount</label>';
                            str += '<div class="input-group">';
                            str += '<input type="tel" class="form-control"value="' + params.amnt + '"  disabled>';
                            str += '</div>';
                            str += '</div>';
                            str += ' </div>';

                            if(urole_id == 1)
                            {
                                str += ' <div class="col-md-12">';
                                str += ' <div class="form-group">';
                                str += ' <label>Payment Mode</label>';
                                str += '<div class="input-group">';
                                str += '<input type="tel" class="form-control"value="' + params.py_amnt + '" disabled>';
                                str += '</div>';
                                str += '</div>';
                                str += '</div>';



                            }
                            str += ' <div class="col-md-12">';
                            str += ' <div class="form-group">';
                            str += ' <label>Bank Reference</label>';
                            str += '<div class="input-group">';
                            str += '  <input type="text"class="form-control"value="' + params.bnk_ref + '" disabled>';
                            str += '</div>';
                            str += '</div>';
                            str += '</div>';

                            if(urole_id == 1)
                            {
                                str += ' <div class="col-md-12">';
                                str += ' <div class="form-group">';
                                str += '  <label>Bank Naration</label>';
                                str += ' <div class="input-group">';
                                str += ' <input type="text"class="form-control" value="' + params.bnk_nar + '" disabled>';
                                str += ' </div>';
                                str += ' </div>';
                                str += ' </div>';
                            }

                            str += '</div>';
                            str += '<div class="modal-footer">';
                            str += '<button type="submit" class="btn btn-secondary"  id="bck_bnk">Back</button>';
                            str += '<button type="submit" class="btn btn-space btn-primary legitRipple ladda-button" data-style="zoom-in" id="sucess_pymnt">Confirm</button>';
                            str += '</div>';
                            str += '</form>';
                            str += '</div>';
                            str += '</div>';
                            str += '</div>';
                            $('#prcd_blnc_form').html(str).show();
                            $('#bck_bnk').click(function () {
                                $("#mngblnc_form_div").show();
                                $("#show_blnc_form").hide().html('');
                            });
                            // /***----payment proceed section-----***/
                            if (process_add === false) {
                                $('#sucess_pymnt').click(function (e) {
                                    process_add = true;
                                    e.preventDefault();
                                    var la = $('#sucess_pymnt').ladda();
                                    la.ladda('start');

                                    $.post('Manage/AddBalance', params, function (response) {
                                       
                                        if (response) 
                                        {
                                            process_add = false;
                                            if (response.Resp_code == 'RCS')
                                            {
                                                toastr.info(response.Resp_desc);

                                                $('#head_ttl2').hide();
                                                $('#head_ttl').show().html('Action User Details of ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');

                                                $('#first_div').show();
                                                $('#usr_blnc_form').hide().html('');


                                            } else if (response.Resp_code == 'UAC') {


                                                window.location.reload(true);


                                            } 
                                            else if(response.Resp_code == 'ERR')
                                            {
                                                $("#mngblnc_form_div").show();
                                                $("#show_blnc_form").hide().html('');

                                                $('#lrge_modal').find('.error-alert').remove();
                                                if(response.Resp_desc instanceof Object)
                                                {
                                                    $.each(response.Resp_desc, function(key, value) {
                                                    $('#'+key).parent().parent().append('<div class="text-danger error-alert">'+value+'<div>');
                                                    });
                                                }
                                                else
                                                {
                                                    toastr.error(response.Resp_desc);                                                    
                                                }

                                            }
                                        }

                                         la.ladda('stop');

                                    }, 'json').fail(function (error) {

                                        la.ladda('stop');

                                    });
                                });
                            }

                            return false;

                        }
                    });

                }

                if(urole_id == 1)
                {
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
                        str += '<button type="submit" class="btn btn-space btn-primary legitRipple ladda-button"data-style="zoom-in" id="debt_next_prc">Proceed</button>';
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

                                    return (this.optional(element) || value == 'Bank Trandfer' || value == 'Cash' || value == 'Cheque/DD' || value == 'Admin');
                                    // --                                    or leave a space here ^^
                                }),
                                valid_dedct_blnc();
                        $('#deduct_users_blnc_form').validate({
                            errorElement: 'span', //default input error message container
                            errorClass: 'help-block', // default input error message class

                            /*rules: {
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
                            },*/
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
                                str += '<button type="submit" class="btn btn-space btn-primary legitRipple ladda-button" data-style="zoom-in" id="sucess_pymnt_deduction">Confirm</button>';
                                str += '</div>';

                                str += '</form>';
                                str += '</div>';
                                str += '</div>';
                                str += '</div>';
                                $('#prcd_deduct_blnc_form').html(str).show();
                                $('#deduct_bck_bnk').click(function () {

                                    $('#head_ttl2').hide();
                                    $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');
                                    $("#first_div").show();
                                    $("#usr_blnc_form").hide();
                                    $("#deduct_users_blnc_form").hide();
                                    $("#show_deduct_blnc_form").hide().html('');

                                });
                                // /***----payment deduction proceed section-----***/
                                $('#sucess_pymnt_deduction').click(function (e) {
                                    e.preventDefault();                                    
                                    var la = $('#sucess_pymnt_deduction').ladda();
                                    la.ladda('start');
                                    $.post('Manage/DeductBalance', $(form).serialize() + "&showtd1=" + showtd1.accnt_id, function (response) {

                                        if (response) 
                                        {
                                            if (response.Resp_code == 'RCS')
                                            {
                                                toastr.info(response.Resp_desc);

                                                $('#head_ttl2').hide();
                                                $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');

                                                $('#first_div').show();
                                                $('#prcd_deduct_blnc_form').hide();
                                                $('#deduct_users_blnc_form').hide().html('');


                                            } 
                                            else if (response.Resp_code == 'UAC') 
                                            {
                                                window.location.reload(true);

                                            } 
                                            else if(response.Resp_code == 'ERR') 
                                            {
                                                $('#lrge_modal').find('.error-alert').remove();
                                                if(response.Resp_desc instanceof Object)
                                                {
                                                    $('#prcd_deduct_blnc_form').hide();
                                                    $('#dect_blnc_form_div').show();
                                                    $.each(response.Resp_desc, function(key, value) {
                                                    $('#'+key).parent().parent().append('<div class="text-danger error-alert">'+value+'<div>');
                                                    });
                                                }
                                                else
                                                {
                                                    toastr.error(response.Resp_desc);                                                    
                                                }

                                            }
                                        }

                                        la.ladda('stop');

                                    }, 'json').fail(function (error) {
                                       
                                        la.ladda('stop');
                                    });
                                });

                                return false;
                            }
                        });
                    }
                }
                $('#kyc_data').click(function (e) {
                    e.preventDefault();
                    $('#lrge_modal').addClass('modal-lg');
                    $("#head_ttl").hide();
                    $('#head_ttl2').show().html('Manage ' + showtd1.full_name + ' KYC Documents');

                    var acid = $(this).data('kyc_doc');
                    if (acid == showtd1.accnt_id) {
                        $("#first_div").hide();
                        var str = '<div id="model_content_' + showtd1.accnt_id + '">';
                        str += '<div class="timeline-wrapper m-t-md m-b-xxl " id="timeline-wrapper-pro"><div class="timeline-item m-b-xl" ><div class="animated-background"><div class="background-masker content-top"></div><div class="background-masker content-first-end"></div><div class="background-masker content-second-line"></div><div class="background-masker content-second-end"></div><div class="background-masker content-third-line"></div><div class="background-masker content-third-end"></div><div class="background-masker content-top-1"></div><div class="background-masker content-first-end-1"></div><div class="background-masker content-second-line-1"></div><div class="background-masker content-second-end-1"></div><div class="background-masker content-third-line-1"></div><div class="background-masker content-third-end-1"></div><div class="background-masker content-top-2"></div><div class="background-masker content-first-end-2"></div><div class="background-masker content-second-line-2"></div><div class="background-masker content-second-end-2"></div><div class="background-masker content-third-line-2"></div><div class="background-masker content-third-end-2"></div></div></div></div>';
                        str += '</div>';
                        $('#kyc_dtl_documents_' + showtd1.accnt_id + '').html(str).show();
                        user_kyc_docs(showtd1);

                    }

                });
                var user_kyc_docs = function (showtd1) {
                    var document = {};
                    var documnt_array = {}
                    var loader = '<div class="timeline-wrapper m-t-md m-b-xxl " id="timeline-wrapper-pro"><div class="timeline-item m-b-xl" ><div class="animated-background"><div class="background-masker content-top"></div><div class="background-masker content-first-end"></div><div class="background-masker content-second-line"></div><div class="background-masker content-second-end"></div><div class="background-masker content-third-line"></div><div class="background-masker content-third-end"></div><div class="background-masker content-top-1"></div><div class="background-masker content-first-end-1"></div><div class="background-masker content-second-line-1"></div><div class="background-masker content-second-end-1"></div><div class="background-masker content-third-line-1"></div><div class="background-masker content-third-end-1"></div><div class="background-masker content-top-2"></div><div class="background-masker content-first-end-2"></div><div class="background-masker content-second-line-2"></div><div class="background-masker content-second-end-2"></div><div class="background-masker content-third-line-2"></div><div class="background-masker content-third-end-2"></div></div></div></div>';
                    $('#kyc_dtl_documents_' + showtd1.accnt_id + '').html(loader);
                    $.ajax({
                        url: 'Manage/userDocuments',
                        dataType: "json",
                        type: 'post',
                        data: {userID: showtd1.accnt_id},
                        success: function (data) {
                            if (data.Resp_code == 'RCS') 
                            {
                                
                                document = data.msg;                                
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
                                    
                                    ////console.log($.isEmptyObject(v));
                                    str += '<tr>';
                                    str += ' <td>' + k + '</td>';
                                    var docdta;
                                    var dta = 'kyc_' + k + '';
                                    docdta = dta.replace(" ", "");
                                    //console.log(docdta);
                                    if ($.isEmptyObject(v) === true) 
                                    {
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
                                    } 
                                    else 
                                    {

                                        str += ' <td><a href="' + v.doc_path + '" class="success-btn" target="_blank">View</a></td>';

                                        if (v.status == "PENDING") {

                                            str += '<td><button class="btn btn-primary btn-sm apprv_doc legitRipple ladda-button" data-style="zoom-in" data-apprv="' + v.id + '" >Approve</button> <button class="btn btn-danger btn-sm reject_doc legitRipple ladda-button" data-style="zoom-in" data-reject="' + v.id + '" >Reject</button></td>';

                                        } else {

                                            str += ' <td><div class="chng_doc_apvrject">' + v.status + '  <button class="btn btn-space btn-primary aprvd_rjctd_chng_doc legitRipple ladda-button" data-style="zoom-in" data-chng_doc="' + v.id + '" id="aprvd_rjctd_chng_' + docdta + '_' + showtd1.accnt_id + '" >Change</button></div></td>';

                                        }

                                    }
                                    str += '</tr> ';
                                    i++;
                                });
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
                                    var doc_id = $(this).data('chng_doc');
                                    var row = $(this).closest('tr').index();
                                    if (documnt_array[row] != undefined) {
                                        if (documnt_array[row].id == doc_id) {
                                            var fl = '<form id="' + chng_fl + '_form"><div class="form-group">';
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
                                            //$('#'+ chng_fl + '_file').file
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
                                        //console.log(docdta);

                                        $('#' + docdta + '_' + showtd1.accnt_id + '').change(function () {
                                            var file = $('#' + docdta + '_' + showtd1.accnt_id + '')[0]['files'][0];
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
                                            label.remove();
                                        }

                                        valid_obj['errorPlacement'] = function (error, element) {

                                            error.insertAfter(element.closest('.form-group').find('.input-group'));
                                        }

                                        valid_obj['submitHandler'] = function (form) {
                                            var row = $('#' + docdta + '_' + showtd1.accnt_id + '_sbmit').closest('tr').index();
                                            //console.log(row);
                                            if (documnt_array[row] != undefined) {
                                                if (documnt_array[row].length == 0) {

                                                    var la = $('#' + docdta + '_' + showtd1.accnt_id + '_sbmit').ladda();
                                                    la.ladda('start');
                                                    //console.log(la);
                                                    var file = $('#' + docdta + '_' + showtd1.accnt_id + '')[0]['files'][0];
                                                    var data = new FormData();

                                                    data.append('file', file);
                                                    data.append('accntid', showtd1.accnt_id);
                                                    data.append('doctyp', k);


                                                    $.ajax({
                                                        url: 'Manage/uploadUserDocs',
                                                        type: 'POST',
                                                        data: data,
                                                        cache: false,
                                                        dataType: 'json',
                                                        processData: false, // Don't process the files
                                                        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                                                        success: function (response)
                                                        {
                                                            if (response) {

                                                                if (response.Resp_code == 'RCS')
                                                                {
                                                                    toastr.info(response.Resp_desc);
                                                                    $(form)[0].reset();

                                                                    // $('#'+docdta+'_'+showtd1.accnt_id+'').hide().html('');
                                                                    user_kyc_docs(showtd1);
                                                                } 
                                                                else if (response.Resp_code == 'UAC') 
                                                                {
                                                                    window.location.reload(true);

                                                                } 
                                                                else if(response.Resp_code == 'ERR') 
                                                                {
                                                                    toastr.error(response.Resp_desc);
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
                                    var la = $(this).ladda();
                                    var doc = $(this).data('apprv');
                                    var row = $(this).closest('tr').index();
                                    //console.log(row);
                                    //console.log(documnt_array);
                                    //console.log(documnt_array[row].id);
                                    //console.log(doc);
                                    if (documnt_array[row] != undefined) {
                                        if (documnt_array[row].id == doc) {

                                            var dataString = {'docID': doc, 'userID': showtd1.accnt_id};
                                            $.ajax({
                                                url: 'Manage/approveUserDoc',
                                                dataType: "json",
                                                data: dataString,
                                                type: 'post',
                                                beforeSend: function(){
                                                    la.ladda('start');
                                                },
                                                success: function (data) {
                                                    //console.log(data);
                                                    if (data.Resp_code == 'RCS') 
                                                    {
                                                        toastr.success(data.Resp_desc);
                                                        user_kyc_docs(showtd1);
                                                    } 
                                                    else if(data.Resp_code == 'ERR')
                                                    {
                                                        toastr.error(data.error_desc);
                                                    }
                                                    else if(data.Resp_code == 'UAC')
                                                    {
                                                        window.location.reload(true);
                                                    }
                                                    la.ladda('stop');
                                                },
                                                error:function(){
                                                    la.ladda('stop');
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
                                    var la = $(this).ladda();
                                    var rjct_doc = $(this).data('reject');
                                    var row = $(this).closest('tr').index();
                                    //console.log(row);
                                    if (documnt_array[row] != undefined) 
                                    {
                                        if (documnt_array[row].id == rjct_doc) 
                                        {
                                            var dataString = {'docID': rjct_doc, 'userID': showtd1.accnt_id};
                                            $.ajax({
                                                url: 'Manage/rejectUserDoc',
                                                dataType: "json",
                                                data: dataString,
                                                type: 'post',
                                                beforeSend: function(){
                                                    la.ladda('start');
                                                },
                                                success: function (data) 
                                                {
                                                    //console.log(data);
                                                    if (data.Resp_code == 'RCS') {

                                                        toastr.success(data.Resp_desc);

                                                        user_kyc_docs(showtd1);
                                                    } 
                                                    else if(data.Resp_code == 'ERR')
                                                    {

                                                        toastr.error(data.Resp_desc);

                                                    }
                                                    else if(data.Resp_code == 'UAC')
                                                    {
                                                        window.location.reload(true);
                                                    }

                                                    la.ladda('stop');
                                                },
                                                error:function(){
                                                    la.ladda('stop');
                                                } ///success function close       
                                            });
                                        } 
                                        else 
                                        {
                                            toastr.error("Invalid document");
                                        }
                                    } 
                                    else 
                                    {
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
                            label.remove();
                        }
                        valid_obj['errorPlacement'] = function (error, element) {

                            error.insertAfter(element.closest('.form-group').find('.input-group'));
                        }
                        valid_obj['submitHandler'] = function (form) {
                            var row = $('#' + btnid + '_file').closest('tr').index();
                            var data_row = $('#' + btnid + '_btn').data('upd_chng_dc');

                            if (doc_array[row].id != undefined) {
                                if (doc_array[row].id = data_row) {
                                    

                                    var file = $('#' + btnid + '_file')[0]['files'][0];
                                    var data = new FormData();

                                    data.append('file', file);
                                    data.append('id', doc_array[row].id);
                                    data.append('accntid', showtd1.accnt_id);
                                    data.append('doctyp', doc_array[row].doc_name);


                                    $.ajax({
                                        url: 'Manage/uploadUserDocs',
                                        type: 'POST',
                                        data: data,
                                        cache: false,
                                        dataType: 'json',
                                        processData: false, // Don't process the files
                                        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                                        success: function (response)
                                        {
                                            
                                            if (response) {

                                                if (response.Resp_code == 'RCS')
                                                {
                                                    toastr.info(response.Resp_desc);
                                                    $(form)[0].reset();

                                                    // $('#'+docdta+'_'+showtd1.accnt_id+'').hide().html('');
                                                    user_kyc_docs(showtd1);
                                                } 
                                                else if (response.Resp_code == 'UAC') 
                                                {
                                                    window.location.reload(true);

                                                } 
                                                else if(response.Resp_code == 'ERR') 
                                                {
                                                    toastr.error(response.Resp_desc);
                                                }

                                            }
                                                la.ladda('stop');

                                        }, error: function (error) {
                                            la.ladda('stop');                                            
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



            }
        });

        var userdataupdate = function (datatable) {
            datatable.on('click', '#updt_usr_dt', function () {
                var usrid = $(this).data('users');
                var row = $(this).closest('tr');
                var showtd1 = datatable.row(row).data();
                //console.log(showtd1);
                if (showtd1['accnt_id'] == usrid) {

                    var str = '<div class="modal fade" id="mdl_usr_dsc_' + showtd1.accnt_id + '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
                    str += '<div class="modal-dialog modal-dialog-centered" role="document" id="lrge_modal">';
                    str += '<div class="modal-content">';
                    str += '<div class="modal-header">';
                    str += '<h5 class="modal-title" id="head_ttl">Manage ' + showtd1.shop_name + ' KYC Documents';

                    str += '</h5>';
                    str += '<h5 class="modal-title" id="head_ttl2" style="display:none;">';

                    str += '</h5>';
                    str += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    str += '<span aria-hidden="true">&times;';

                    str += '</span>';
                    str += '</button>';
                    str += '</div>';
                    str += '<div class="modal-body modal-lg">';
                    str += '<div  id="first_div" style="">';
                    str += '<div class="m-portlet__body">';
                    //-----------------------------------------
                    str += '<div class="m-widget13">';
                    str += '<div class="m-widget13__item">';
                    str += '<span class="m-widget13__desc m--align-center">';
                    str += '<label class="mr-3">Transaction</label>';
                    if(showtd1.txn_active == 1)
                    {
                        str +='<input type="checkbox" id="change_txnActive" checked data-toggle="toggle" data-txnActive ="' + showtd1.accnt_id + '" >';                    
                    }
                    else
                    {
                        str +='<input type="checkbox" id="change_txnActive" data-toggle="toggle" data-txnActive ="' + showtd1.accnt_id + '" >';
                    }
                    str += '</span>';
                    str += '<span class="m-widget13__desc m--align-center">';
                    str += '<label class="mr-3">login</label>';
                    if(showtd1.login_active == 1)
                    {
                        str +='<input type="checkbox" id="change_lgnActive" checked data-toggle="toggle" data-lgnActive ="' + showtd1.accnt_id + '">';                  
                    }
                    else
                    {
                        str +='<input type="checkbox" id="change_lgnActive" data-toggle="toggle" data-lgnActive ="' + showtd1.accnt_id + '">';
                    }                
                    str += '</span>';
                    str += '</div>';
                    str += '</div>';
                    //--------------------------------------------
                    str += '<div class="m-widget13">';
                    str += '<div class="m-widget13__item">';
                    str += '<span class="m-widget13__desc m--align-right">Add Balance';
                    str += '</span>';
                    str += '<span class="m-widget13__text m-widget13__text-bolder">';
                    str += '<button type="button" class="btn btn-space btn-primary btn-sm" data-add_blnc="' + showtd1.accnt_id + '" id="add_blnc">Add Balance</button>';
                    str += '</span>';
                    str += '</div>';
                        if(urole_id == 1)
                        {
                            str += '<div class="m-widget13__item">';
                            str += '<span class="m-widget13__desc m--align-right">Deduct Balance';
                            str += '</span>';
                            str += '<span class="m-widget13__text m-widget13__text-bolder">';
                            str += '<button type="button" class="btn btn-space btn-primary btn-sm" data-dedct_blnc="' + showtd1.accnt_id + '" id="dedct_blnc">Deduct Balance</button>';
                            str += '</span>';
                            str += '</div>';

                            str += '<div class="m-widget13__item">';
                            str += '<span class="m-widget13__desc m--align-right">Update User Details';
                            str += '</span>';
                            str += '<span class="m-widget13__text m-widget13__text-bolder">';
                            str += '<button class="btn btn-space btn-primary btn-sm" data-actvt_usr="' + showtd1.accnt_id + '" id="updt_usr_dtl">Update User Details</button>';
                            str += '</span>';
                            str += '</div>';

                            str += '<div class="m-widget13__item">';
                            str += '<span class="m-widget13__desc m--align-right">Change 2FA';
                            str += '</span>';
                            str += '<span class="m-widget13__text m-widget13__text-bolder">';
                            str += '<button type="button" class="btn btn-space btn-primary btn-sm"  data-chg2fa="' + showtd1.accnt_id + '" id="change2fa">Change 2FA Settings</button>';
                            str += '</span>';
                            str += '</div>';

                            str += '<div class="m-widget13__item">';
                            str += '<span class="m-widget13__desc m--align-right">KYC document';
                            str += '</span>';
                            str += '<span class="m-widget13__text m-widget13__text-bolder">';
                            str += '<button type="button" class="btn btn-space btn-primary btn-sm"  data-kyc_doc="' + showtd1.accnt_id + '" id="kyc_data">KYC document</button>';
                            str += '</span>';
                            str += '</div>';

                            str += '<div class="m-widget13__item">';
                            str += '<span class="m-widget13__desc m--align-right">Parent Mapping';
                            str += '</span>';
                            str += '<span class="m-widget13__text m-widget13__text-bolder">';
                            str += '<button type="button" class="btn btn-space btn-primary btn-sm"  data-p_mapping="' + showtd1.accnt_id + '" data-pid="' + showtd1.parent_id + '" id="parent_mapping">Parent Mapping</button>';
                            str += '</span>';
                            str += '</div>';

                            str += '<div class="m-widget13__item">';
                            str += '<span class="m-widget13__desc m--align-right">Manage Plan';
                            str += '</span>';
                            str += '<span class="m-widget13__text m-widget13__text-bolder">';
                            str += '<button type="button" class="btn btn-space btn-primary btn-sm"  data-mng_pln="' + showtd1.accnt_id + '" id="mng_pln">Update Plan</button>';
                            str += '</span>';
                            str += '</div>';

                        }

                        str += '</div>';
                        str += '</div>';
                        str += '</div>';
                        str += '<div id="usr_blnc_form" style="display:none;">';
                        str += '</div>';
                        str += '<div id="usr_deduct_blnc_form" style="display:none;">';
                        str += '</div>';                        
                        str += '<div id="update2fadiv" style="display:none;">';
                        str += '</div>';
                        str += '<div id="usr_updt_dtl_form" style="display:none;">';
                        str += '</div>';
                        str += '<div id="kyc_dtl_documents_' + showtd1.accnt_id + '" style="display:none;">';
                        str += '</div>';
                        str += '<div id="mng_plan_form_' + showtd1.accnt_id + '" style="display:none;">';
                        str += '</div>';
                        str += '</div>';
                        str += '</div>';
                        str += '</div>';
                        str += '</div>';


                    $('body').append(str);

                    $('#change_txnActive, #change_lgnActive').bootstrapToggle();

                    $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal({
                        backdrop: 'static',
                        keyboard: false,
                        show: true,
                    });


                    $('#mdl_usr_dsc_' + showtd1.accnt_id + '').on('hidden.bs.modal', function () {
                        $('#mdl_usr_dsc_' + showtd1.accnt_id + '').remove();
                        if(change == 1)
                        {
                            $('#crb').trigger('change');
                        } 
                        else if(change == 2)
                        {
                            $('#newparent').trigger('change');
                        }
                        else if(change == 3)
                        {
                            $('#ChildData').trigger('change');
                        }
                       /* usr_list_tbl.ajax.reload(null, false);*/
                    });


                    /****activate user ***/
                    if(urole_id == 1)
                    {

                        /********update 2fa************/

                        $('#change2fa').click(function(e){
                            e.preventDefault();
                            
                            var checked = "";
                            var style = "";

                            if(showtd1['2fa_status'] == 1)
                            {
                                checked = "checked:checked";
                            }
                            else
                            {
                                style = 'style = "display:none"';
                            }

                            var acid = $(this).data('chg2fa');
                            $('#head_ttl').show().html('Update 2FA Setting of '+showtd1.full_name);

                            $("#first_div").hide();

                            var str = '<div class="row" id="sec_form_div">';
                            str += '<div class="col-sm-12">';
                            str += '<div class="panel-body">';

                             //------------------------------------------
                             str += '<form id="twofaform">';

                             str += '<div class="form-group m-form__group row m-0-set">'; 

                            str += '<div class="col-lg-6">';
                            str += '<div class="col m--align-left">';
                            str += '<label class="m-checkbox m-checkbox--focus">';                
                            str += '<input type="checkbox" name="2fachangecheck" id="2fachangecheck" '+checked+'>';
                            str += 'Activate 2fa';
                            str += '<span></span>';
                            str += '</label>';
                            str += '</div>';

                            str += '<div id="2fatoggle" class="form-group" '+style+'>';                
                            
                            
                            str += '<select class="form-control" name = "twofaselect" id="twofaselect">';
                            str += '<option value="" disabled>Please Select 2FA Setting</option>';
                            if(twofasettings != undefined)
                            {
                               var i = 0
                                $.each(twofasettings, function(key, value){
                                    if(value.twofa_configid == showtd1.twofa_configid)
                                    {
                                        str += '<option value = "'+value.twofa_configid+'" selected = "selected">'+value.name+'</option>';
                                    }
                                    else
                                    {
                                        str += '<option value = "'+value.twofa_configid+'">'+value.name+'</option>';
                                    }
                                });
                            }
                             str += '</select>';
                             str += '<span data-for="2fatoggle"></span>';
                             str += '</div>';
                             str += '</div>';
                             str += '</div>';
                             //--------------------------------------------
                             str += '<div class="modal-footer">';
                             str += '<button type="submit" class="btn btn-secondary"  id="2fa_bck">Back</button>';
                             str += '<button type="submit" class="btn btn-space btn-primary legitRipple ladda-button"data-style="zoom-in" id="twofa_upt">Update</button>';
                             str += '</div>';
                             str += '</form>';
                             str += '</div>';
                             str += '</div>';
                             str += '</div>';

                            $('#update2fadiv').html(str).show();

                            var config = $('#twofaselect').val();
                            var configcheck;

                            $('#2fachangecheck').change(function(){
                                if ($(this).prop('checked'))
                                {
                                    $('#2fatoggle').css('display','block');
                                    configcheck = true;
                                } 
                                else
                                {
                                    $('#2fatoggle').css('display','none');
                                    config = 0;
                                    configcheck = false;
                                }
                            });

                            $('#2fa_bck').click(function(){
                                $("#update2fadiv").hide().html('');
                                $("#first_div").show();
                            }); 

                            $('#twofa_upt').click(function(){
                                e.preventDefault();
                               la = $('#twofa_upt').ladda();


                               $.ajax({
                                   url: 'Manage/update2fa',
                                   dataType: 'json',
                                   data: {
                                    id: showtd1.accnt_id,
                                    configid: config,
                                    configcheck: configcheck
                                   },
                                   type: 'POST',
                                   beforeSend: function(){
                                    la.ladda('start');
                                   },
                                   success:function(response){
                                       
                                       if(response.Resp_code == 'RCS')
                                       { 
                                           toastr.info(response.Resp_desc);
                                           $("#update2fadiv").hide().html('');
                                           $("#first_div").hide();
                                           $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal('hide')
                                            
                                       }
                                       else if(response.Resp_code == 'ERR')
                                       {
                                          toastr.error(response.Resp_desc);
                                       }
                                       else if(response.Resp_code == 'UAC')
                                       {
                                         window.location.reload(true);
                                       }

                                       la.ladda('stop');
                                   },
                                   error: function() {
                                    la.ladda('stop');
                                   }
                               });
                            });

                        });


                        /********End Update 2fa*******/

                        /******manage plan ************/

                        $('#mng_pln').click(function (e) {
                            e.preventDefault();
                            var acid = $(this).data('mng_pln');
                            //console.log(acid);

                            if (acid == showtd1.accnt_id) {
                                $("#head_ttl").hide();
                                $('#head_ttl2').show().html('Manage ' + showtd1.full_name + ' Plan');

                                //  $('#mng_plan_form_'+showtd1.accnt_id+'').show();
                                $("#first_div").hide();
                                var plan_array = {}
                                $.ajax({
                                    url: 'Manage/getPlans',
                                    dataType: "json",
                                    type: 'post',
                                    data: {role_id: showtd1.role_id},
                                    beforeSend: function (data) {

                                        var loader = ' <div class="timeline-wrapper m-t-md m-b-xxl " id="timeline-wrapper-pro"><div class="timeline-item m-b-xl" ><div class="animated-background"><div class="background-masker content-top"></div><div class="background-masker content-first-end"></div><div class="background-masker content-second-line"></div><div class="background-masker content-second-end"></div><div class="background-masker content-third-line"></div><div class="background-masker content-third-end"></div><div class="background-masker content-top-1"></div><div class="background-masker content-first-end-1"></div><div class="background-masker content-second-line-1"></div><div class="background-masker content-second-end-1"></div><div class="background-masker content-third-line-1"></div><div class="background-masker content-third-end-1"></div><div class="background-masker content-top-2"></div><div class="background-masker content-first-end-2"></div><div class="background-masker content-second-line-2"></div><div class="background-masker content-second-end-2"></div><div class="background-masker content-third-line-2"></div><div class="background-masker content-third-end-2"></div></div></div></div>';
                                        $('#mng_plan_form_' + showtd1.accnt_id).html(loader).show();
                                    },
                                    success: function (data) {
                                        if (data.Resp_code == 'RCS') 
                                        {

                                            var str = '<div class="row" id="mngplnrt_form_div">';
                                            str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                                            str += '<div class="panel-body">';
                                            str += '<form action="#" id="updt_rate_form">';
                                            str += '<div class="row">';
                                            str += ' <div class="col-md-12">';
                                            str += ' <div class="form-group">';

                                            //console.log(plan_array);
                                            str += ' <label>Plan</label>';
                                            str += '<div class="input-group">';
                                            str += '<select class="form-control custom-select" name="plan_name" id="plan_name">';
                                            str += '<option value="default">Select Plan</option>';
                                            $.each(data.data, function (k, v) {
                                                plan_array[v.id] = v
                                                var sel_rt = (showtd1.plan_id == v.id) ? 'selected' : ''
                                                str += '<option value="' + v.id + '" ' + sel_rt + '>' + v.slab_name + '</option>';
                                            })
                                            str += '</select>';
                                            str += '</div>';
                                            str += '</div>';
                                            str += '</div>';
                                            str += '</div>';
                                            str += '<div class="modal-footer">';
                                            str += '<button type="submit" class="btn btn-secondary"  id="bcl_rate">Back</button>';
                                            str += '<button type="submit" class="btn btn-space btn-primary legitRipple ladda-button"data-style="zoom-in" id="rt_update">Update</button>';
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
                                        } 
                                        else if (data.Resp_code == 'UAC') 
                                        {
                                            window.location.reload(true);

                                        } 
                                        else if(data.Resp_code == "ERR") 
                                        {
                                            $('#head_ttl2').hide();
                                            $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');
                                            $("#first_div").show();
                                            $('#mng_plan_form_' + showtd1.accnt_id + '').hide().html('');
                                            toastr.error(data.Resp_desc);
                                        }
                                    }
                                });

                            }



                        });
                        var update_plan_view = function (showtd1, plan_array) {
                            var la = $('#rt_update').ladda();
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

                                    la.ladda('start');

                                    $.post('Manage/updatePlan', $(form).serialize() + "&showtd1=" + showtd1.accnt_id + "&role_id=" + showtd1.role_id, function (response) {

                                        if (response) {

                                            if (response.Resp_code == 'RCS')
                                            {
                                                toastr.info(response.Resp_desc);

                                                $('#head_ttl2').hide();
                                                $('#head_ttl').show().html('Action User Details of ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');


                                                $('#updt_rate_form').hide().html('');
                                                $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal('hide');

                                            } 
                                            else if (response.Resp_code == 'UAC') 
                                            {
                                                window.location.reload(true);

                                            } 
                                            else if(response.Resp_code == 'ERR') 
                                            {
                                                toastr.error(response.Resp_desc);
                                            }
                                          
                                        }


                                    }, 'json').fail(function (error) {

                                    });

                                    return false;
                                }

                            });

                        }
                        /******end manage plan*******/


                        ///***update user details******////
                        var gen_obj = {'MALE': 'MALE', 'FEMALE': 'FEMALE'};
                        $('#updt_usr_dtl').click(function (e) {
                            e.preventDefault();
                            $('#head_ttl').show().html('Update ' + showtd1.full_name + ' (Account: ' + showtd1.accnt_id + ')');
                            $("#usr_updt_dtl_form").show();
                            $("#first_div").hide();
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
                            str += '<label>Date</label>';
                            str += ' <div class="input-group">';
                            str += '<input type="text" class="form-control m-input" data-provide="datepicker" value ="'+showtd1.dob+'"data-provide="datepicker-inline" id="dob" name="dob" readonly="true" placeholder="Enter Date of Birth">';
                            str += '  </div>';
                            str += ' </div>';
                            str += ' </div>';

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
                            str += '<button type="submit" class="btn btn-space btn-primary legitRipple ladda-button" data-style="zoom-in" id="updt_us">Update User</button>';
                            str += '</div>';
                            str += '</form>';
                            str += '</div>';
                            str += '</div>';
                            str += '</div>';
                            $('#usr_updt_dtl_form').html(str).show();
                            profile_update();

                             $('#dob').datepicker({
                                format: 'dd-mm-yyyy',            
                                startDate: '01-01-1970',
                                endDate: "today"
                            });

                            $('#updt_back_us').click(function () {
                                $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');
                                $("#first_div").show();
                                $("#usr_updt_dtl_form").hide().html('');
                            });

                        });
                        var profile_update = function () {
                            $.validator.methods.digits = function (value, element) {

                                return this.optional(element) || /^[6789][0-9]{9}$/.test(value);

                            }
                            $.validator.methods.pannumber = function (value, element) {

                                return this.optional(element) || /^[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}$/.test(value);

                            }
                            $.validator.methods.pindigits = function (value, element) {

                                return this.optional(element) || /^[1-9][0-9]{5}$/.test(value);

                            }
                            $.validator.addMethod("alpha", function (value, element) {

                                return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
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

                                return this.optional(element) || /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);

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
                                        alpha: true,
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
                                        exactlength: 10,
                                        pannumber: true
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
                                    dob: {
                                        required: true
                                    }
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
                                        exactlength: "Please enter 10 digits for a valid PAN number.",
                                        pannumber: 'Please enter a valid PAN'
                                    },
                                    pin_code: {
                                        required: "Shop Pincode is required.",
                                        pindigits: 'Please enter a valid PAN'
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
                                    dob: {
                                        required: 'Date of Birth is Required'
                                    }
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
                                    la.ladda('start');
                                    $.post('Manage/updateUser', $(form).serialize() + "&showtd1=" + showtd1.accnt_id, function (response) {

                                        if (response) 
                                        {

                                            if (response.Resp_code == 'RCS')
                                            {
                                                toastr.info(response.Resp_desc);
                                                $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal('hide');

                                            } 
                                            else if (response.Resp_code == 'UAC')
                                            {
                                                window.location.reload(true);

                                            } 
                                            else if(response.Resp_code == 'ERR') 
                                            {
                                                $('#lrge_modal').find('.error-alert').remove();
                                                if(response.Resp_desc instanceof Object)
                                                {
                                                    $.each(response.Resp_desc, function(key, value) {
                                                    $('#'+key).parent().parent().append('<div class="text-danger error-alert">'+value+'<div>');
                                                    });
                                                }
                                                else
                                                {
                                                    toastr.error(response.Resp_desc);                                                
                                                }
                                            }
                                        }
                                            la.ladda('stop');

                                    }, 'json').fail(function (error) {

                                        la.ladda('stop');

                                    });

                                    return false;
                                }

                            });
                        }


                     //**************Change Txn Status****************//
                                        $('#change_txnActive').change(function(e)
                                        {
                                            e.preventDefault();
                                            if($(this).prop('checked'))
                                            {
                                                var acid = $(this).data('txnactive');
                                                if (acid == showtd1.accnt_id) {
                                                    var dataString = {'userID': acid};
                                                    $.ajax({
                                                        url: 'Manage/activateDeactivateUserTxn',
                                                        dataType: "json",
                                                        data: dataString,
                                                        type: 'post',
                                                     success: function (data) {
                                                         
                                                         if (data.Resp_code == 'RCS') 
                                                         {
                                                             toastr.success(data.Resp_desc);
                                                             $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal('hide');
                                                         } 
                                                         else if(data.Resp_code == 'ERR') 
                                                         {
                                                             toastr.error(data.Resp_desc);
                                                         }
                                                         else if(data.Resp_code == 'UAC')
                                                         {
                                                            window.location.reload(true);
                                                         }
                                                        }///success function close       
                                                    });
                                                }
                                            }
                                            else
                                            {
                                                var acid = $(this).data('txnactive');
                                                console.log(acid);
                                                if (acid == showtd1.accnt_id) {
                                                    var dataString = {'userID': acid};
                                                    $.ajax({
                                                        url: 'Manage/activateDeactivateUserTxn',
                                                        dataType: "json",
                                                        data: dataString,
                                                        type: 'post',
                                                     success: function (data) {
                                                         
                                                         if (data.Resp_code == 'RCS') 
                                                         {
                                                             toastr.success(data.Resp_desc);
                                                             $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal('hide');
                                                         } 
                                                         else if(data.Resp_code == 'ERR') 
                                                         {
                                                             toastr.error(data.Resp_desc);
                                                         }
                                                         else if(data.Resp_code == 'UAC')
                                                         {
                                                            window.location.reload(true);
                                                         }
                                                        }///success function close       
                                                    });
                                                }
                                            }
                                        })

                                    ///*************End Change Txn Status*****************///

                                     ///****update user details section end*****////
                                     
                                     $('#change_lgnActive').change(function(e)
                                     {
                                         e.preventDefault();
                                         if($(this).prop('checked'))
                                         {
                                            console.log('here');
                                             var acid = $(this).data('lgnactive');
                                             if (acid == showtd1.accnt_id) 
                                             {
                                                 var dataString = {'userID': acid};
                                                 $.ajax({
                                                     url: 'Manage/activateDeactivateUser',
                                                     dataType: "json",
                                                     data: dataString,
                                                     type: 'post',               
                                                     success: function (data) {
                                                         
                                                         if (data.Resp_code == 'RCS') 
                                                         {
                                                             toastr.success(data.Resp_desc);
                                                             $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal('hide');
                                                         } 
                                                         else if(data.Resp_code == 'ERR') 
                                                         {
                                                             toastr.error(data.Resp_desc);
                                                         }
                                                         else if(data.Resp_code == 'UAC')
                                                         {
                                                            window.location.reload(true);
                                                         }
                                                     }///success function close       
                                                 });
                                             }
                                         }
                                         else
                                         {
                                            var acid = $(this).data('lgnactive');
                                            if (acid == showtd1.accnt_id) {
                                                var dataString = {'userID': acid};
                                                $.ajax({
                                                    url: 'Manage/activateDeactivateUser',
                                                    dataType: "json",
                                                    data: dataString,
                                                    type: 'post',
                                                 success: function (data) {
                                                     
                                                     if (data.Resp_code == 'RCS') 
                                                     {
                                                         toastr.success(data.Resp_desc);
                                                         $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal('hide');
                                                     } 
                                                     else if(data.Resp_code == 'ERR') 
                                                     {
                                                         toastr.error(data.Resp_desc);
                                                     }
                                                     else if(data.Resp_code == 'UAC')
                                                     {
                                                        window.location.reload(true);
                                                     }
                                                    }///success function close       
                                                });
                                            }
                                         }
                                     })

                                     //******************Change Login Update***************//
                                     


                        var update_parent = function (showtd1, parent_array) {

                                var la = $('#rt_update_parent').ladda();
                                $.validator.addMethod("valueNotEquals", function (value, element, arg) {
                                    return (value in parent_array);
                                }, "Value must not equal arg.");

                                $('#updt_parent_form').validate({
                                    errorElement: 'span', //default input error message container
                                    errorClass: 'help-block', // default input error message class

                                    rules: {
                                        parent_name: {
                                            valueNotEquals: true,
                                        },
                                    },
                                    messages: {
                                        parent_name: {
                                            valueNotEquals: "Please select parent "
                                        },
                                    },
                                    invalidHandler: function (event, validator) { //display error alert on form submit
                                        $('.alert-danger', $('#updt_btn_rtlr')).show();
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

                                        la.ladda('start');

                                        $.post('Manage/updateParent', $(form).serialize() + "&showtd1=" + showtd1.accnt_id + "&role_id=" + showtd1.role_id, function (response) {

                                            if (response) {

                                                if (response.Resp_code == 'RCS')
                                                {
                                                    toastr.info(response.Resp_desc);

                                                    $('#head_ttl2').hide();
                                                    $('#head_ttl').show().html('Action User Details of ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');


                                                    $('#updt_rate_form').hide().html('');
                                                    $('#mdl_usr_dsc_' + showtd1.accnt_id + '').modal('hide');

                                                } else if (response.Resp_code == 'UAC') {


                                                    window.location.reload(true);


                                                } 
                                                else if (response.Resp_code == 'ERR') 
                                                {
                                                    toastr.error(response.Resp_desc);

                                                }
                                               
                                            }

                                            la.ladda('stop');

                                        }, 'json').fail(function (error) {
                                            la.ladda('stop');
                                        });

                                        return false;
                                    }

                                });

                            }

                        $('#parent_mapping').click(function (e) {
                            e.preventDefault();
                            var acid = $(this).data('p_mapping');
                            var parent = $(this).data('pid');

                            if (acid == showtd1.accnt_id) {
                                $("#head_ttl").hide();
                                $('#head_ttl2').show().html('Manage ' + showtd1.full_name + ' Parent Mapping');

                                //  $('#mng_plan_form_'+showtd1.accnt_id+'').show();
                                $("#first_div").hide();
                                var parent_array = {}
                                $.ajax({
                                    url: 'Manage/GetParentListData',
                                    dataType: "json",
                                    type: 'post',
                                    data: {role_id: showtd1.role_id},
                                    beforeSend: function (data) {

                                        var loader = ' <div class="timeline-wrapper m-t-md m-b-xxl " id="timeline-wrapper-pro"><div class="timeline-item m-b-xl" ><div class="animated-background"><div class="background-masker content-top"></div><div class="background-masker content-first-end"></div><div class="background-masker content-second-line"></div><div class="background-masker content-second-end"></div><div class="background-masker content-third-line"></div><div class="background-masker content-third-end"></div><div class="background-masker content-top-1"></div><div class="background-masker content-first-end-1"></div><div class="background-masker content-second-line-1"></div><div class="background-masker content-second-end-1"></div><div class="background-masker content-third-line-1"></div><div class="background-masker content-third-end-1"></div><div class="background-masker content-top-2"></div><div class="background-masker content-first-end-2"></div><div class="background-masker content-second-line-2"></div><div class="background-masker content-second-end-2"></div><div class="background-masker content-third-line-2"></div><div class="background-masker content-third-end-2"></div></div></div></div>';
                                        $('#mng_plan_form_' + showtd1.accnt_id + '').html(loader).show();
                                    },
                                    success: function (data) {
                                        if (data.Resp_code == 'RCS') {
                                            //console.log(parent)
                                            //console.log(data.msg)
                                            var str = '<div class="row" id="mngplnrt_form_div">';
                                            str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                                            str += '<div class="panel-body">';
                                            str += '<form action="#" id="updt_parent_form">';
                                            str += '<div class="row">';
                                            str += ' <div class="col-md-12">';
                                            str += ' <div class="form-group">';

                                            str += ' <label>Parent</label>';
                                            str += '<div class="input-group">';
                                            str += '<select class="form-control custom-select" name="parent_name" id="parent_name">';
                                            str += '<option value="default">Select Parent</option>';
                                            $.each(data.data, function (k, v) {
                                                parent_array[v.accnt_id] = v;
                                                if (parent != 0) {
                                                    var sel_rt = (parent == v.accnt_id) ? 'selected' : ''
                                                } else {
                                                    var sel_rt = '';
                                                }
                                                str += '<option value="' + v.accnt_id + '" ' + sel_rt + '>' + v.full_name + '</option>';
                                            })
                                            str += '</select>';
                                            str += '</div>';
                                            str += '</div>';
                                            str += '</div>';
                                            str += '</div>';
                                            str += '<div class="modal-footer">';
                                            str += '<button type="submit" class="btn btn-secondary"  id="bcl_parent">Back</button>';
                                            str += '<button type="submit" class="btn btn-space btn-primary legitRipple ladda-button"data-style="zoom-in" id="rt_update_parent">Update</button>';
                                            str += '</div>';
                                            str += '</div>';
                                            str += '</form>';
                                            str += '</div>';
                                            str += '</div>';

                                            $('#mng_plan_form_' + showtd1.accnt_id + '').html(str).show();

                                            $('#bcl_parent').click(function (e) {
                                                e.preventDefault();
                                                $('#head_ttl2').hide();
                                                $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');

                                                $("#first_div").show();
                                                $('#mng_plan_form_' + showtd1.accnt_id + '').hide().html('');
                                            })

                                            update_parent(showtd1, parent_array)

                                        } else if (data.Resp_code == 'UAC') {

                                            window.location.reload(true);

                                        } else if(data.Resp_code == 'ERR') {
                                            $('#head_ttl2').hide();
                                            $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');
                                            $("#first_div").show();
                                            $('#mng_plan_form_' + showtd1.accnt_id + '').hide().html('');
                                            toastr.error(data.Resp_desc);
                                        }
                                    }
                                });

                            }
                        });

                    }
                    /*---add blnc*****---------*/
                    var pyamnt_obj = {'Bank Transfer': 'Bank Transfer', 'Cash': 'Cash', 'Cheque/DD': 'Cheque/DD', 'Admin': 'Admin'};
                    $('#add_blnc').click(function (e) {
                        e.preventDefault();
                        $("#head_ttl").hide();
                        $('#head_ttl2').show().html('Manage ' + showtd1.full_name + ' Balance');
                        $("#usr_blnc_form").show();
                        $("#first_div").hide();
                        var str = '<div class="row" id="mngblnc_form_div">';
                        str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                        str += '<div class="panel-body">';
                        str += '<form action="#" id="updt_users_blnc_form">';
                        str += '<div class="row">';

                        str += '<div class="col-md-12">';
                        str += '<div class="form-group">';
                        str += '<label>Amount</label>';
                        str += '<div class="input-group">';
                        str += '<input type="tel" placeholder="Credit Amount" class="form-control" name="blnc" id="blnc" >';
                        str += '</div>';
                        str += '</div>';
                        str += ' </div>';

                        if(urole_id == 1)
                        {
                            str += ' <div class="col-md-12">';
                            str += ' <div class="form-group">';
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
                        }
                        str += ' <div class="col-md-12">';
                        str += ' <div class="form-group has-error">';

                        str += ' <label>Bank Reference</label>';
                        str += '<div class="input-group">';
                        str += '  <input type="text" placeholder="Enter bank reference" class="form-control" name="bnk_ref" id="bnk_ref">';
                        str += '</div>';
                        str += '</div>';
                        str += '</div>';
                        if(urole_id == 1)
                        {
                            str += ' <div class="col-md-12">';
                            str += ' <div class="form-group">';
                            str += '  <label>Bank Naration</label>';
                            str += ' <div class="input-group">';
                            str += ' <input type="text" placeholder="Enter bank naration" class="form-control" name="bnk_nar" id="bnk_nar">';
                            str += ' </div>';
                            str += ' </div>';
                            str += ' </div>';
                        }
                        str += '</div>';
                        str += '<div class="modal-footer">';
                        str += '<button type="submit" class="btn btn-secondary"  id="bck1_bnk">Back</button>';
                        str += '<button type="submit" class="btn btn-space btn-primary legitRipple ladda-button"data-style="zoom-in" id="next_prc">Proceed</button>';
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
                    var process_add = false;
                    var user_mng_balnc = function (showtd1) {

                        $.validator.addMethod("numeric", function (value, element) {

                            return this.optional(element) || value == value.match(/^\s*(?=.*[0-9])\d*(?:\.\d{1,2})?\s*$/);

                        }),
                                $.validator.addMethod("alpha_n", function (value, element) {

                                    return this.optional(element) || value == value.match(/^[a-zA-Z0-9 ]+$/);
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
                                params.blnc = $('#blnc').val();
                                params.py_amnt = $('#py_amnt').val();
                                params.bnk_ref = $('#bnk_ref').val();
                                params.bnk_nar = $('#bnk_nar').val();
                                params.showtd1 = showtd1.accnt_id;


                                params.py_amnt = typeof (params.py_amnt) != 'undefined' ? params.py_amnt : 'Cash';
                                params.bnk_nar = typeof (params.bnk_nar) != 'undefined' ? params.bnk_nar : 'Add Balance';
                                var str = '<div class="row" id="prcd_form_div">';
                                str += '<div class="col-sm-12">';
                                str += '<div class="panel-body">';
                                str += '<form action="#" id="show_blnc_form">';
                                str += '<div class="row">';
                                str += '<div class="col-md-12">';
                                str += '<div class="form-group">';
                                str += '<label>Amount</label>';
                                str += '<div class="input-group">';
                                str += '<input type="tel" class="form-control"value="' + params.amnt + '"  disabled>';
                                str += '</div>';
                                str += '</div>';
                                str += ' </div>';

                                if(urole_id == 1)
                                {
                                    str += ' <div class="col-md-12">';
                                    str += ' <div class="form-group">';
                                    str += ' <label>Payment Mode</label>';
                                    str += '<div class="input-group">';
                                    str += '<input type="tel" class="form-control"value="' + params.py_amnt + '" disabled>';
                                    str += '</div>';
                                    str += '</div>';
                                    str += '</div>';



                                }
                                str += ' <div class="col-md-12">';
                                str += ' <div class="form-group">';
                                str += ' <label>Bank Reference</label>';
                                str += '<div class="input-group">';
                                str += '  <input type="text"class="form-control"value="' + params.bnk_ref + '" disabled>';
                                str += '</div>';
                                str += '</div>';
                                str += '</div>';

                                if(urole_id == 1)
                                {
                                    str += ' <div class="col-md-12">';
                                    str += ' <div class="form-group">';
                                    str += '  <label>Bank Naration</label>';
                                    str += ' <div class="input-group">';
                                    str += ' <input type="text"class="form-control" value="' + params.bnk_nar + '" disabled>';
                                    str += ' </div>';
                                    str += ' </div>';
                                    str += ' </div>';
                                }

                                str += '</div>';
                                str += '<div class="modal-footer">';
                                str += '<button type="submit" class="btn btn-secondary"  id="bck_bnk">Back</button>';
                                str += '<button type="submit" class="btn btn-space btn-primary legitRipple ladda-button" data-style="zoom-in" id="sucess_pymnt">Confirm</button>';
                                str += '</div>';
                                str += '</form>';
                                str += '</div>';
                                str += '</div>';
                                str += '</div>';
                                $('#prcd_blnc_form').html(str).show();
                                $('#bck_bnk').click(function () {
                                    $("#mngblnc_form_div").show();
                                    $("#show_blnc_form").hide().html('');
                                });
                                // /***----payment proceed section-----***/
                                if (process_add === false) {
                                    $('#sucess_pymnt').click(function (e) {
                                        process_add = true;
                                        e.preventDefault();
                                        var la = $('#sucess_pymnt').ladda();
                                        la.ladda('start');

                                        $.post('Manage/AddBalance', params, function (response) {
                                           
                                            if (response) 
                                            {
                                                process_add = false;
                                                if (response.Resp_code == 'RCS')
                                                {
                                                    toastr.info(response.Resp_desc);

                                                    $('#head_ttl2').hide();
                                                    $('#head_ttl').show().html('Action User Details of ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');

                                                    $('#first_div').show();
                                                    $('#usr_blnc_form').hide().html('');


                                                } else if (response.Resp_code == 'UAC') {


                                                    window.location.reload(true);


                                                } 
                                                else if(response.Resp_code == 'ERR')
                                                {
                                                    $("#mngblnc_form_div").show();
                                                    $("#show_blnc_form").hide().html('');

                                                    $('#lrge_modal').find('.error-alert').remove();
                                                    if(response.Resp_desc instanceof Object)
                                                    {
                                                        $.each(response.Resp_desc, function(key, value) {
                                                        $('#'+key).parent().parent().append('<div class="text-danger error-alert">'+value+'<div>');
                                                        });
                                                    }
                                                    else
                                                    {
                                                        toastr.error(response.Resp_desc);                                                    
                                                    }

                                                }
                                            }

                                             la.ladda('stop');

                                        }, 'json').fail(function (error) {

                                            la.ladda('stop');

                                        });
                                    });
                                }

                                return false;

                            }
                        });

                    }

                    if(urole_id == 1)
                    {
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
                            str += '<button type="submit" class="btn btn-space btn-primary legitRipple ladda-button"data-style="zoom-in" id="debt_next_prc">Proceed</button>';
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

                                        return (this.optional(element) || value == 'Bank Trandfer' || value == 'Cash' || value == 'Cheque/DD' || value == 'Admin');
                                        // --                                    or leave a space here ^^
                                    }),
                                    valid_dedct_blnc();
                            $('#deduct_users_blnc_form').validate({
                                errorElement: 'span', //default input error message container
                                errorClass: 'help-block', // default input error message class

                                /*rules: {
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
                                },*/
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
                                    str += '<button type="submit" class="btn btn-space btn-primary legitRipple ladda-button" data-style="zoom-in" id="sucess_pymnt_deduction">Confirm</button>';
                                    str += '</div>';

                                    str += '</form>';
                                    str += '</div>';
                                    str += '</div>';
                                    str += '</div>';
                                    $('#prcd_deduct_blnc_form').html(str).show();
                                    $('#deduct_bck_bnk').click(function () {

                                        $('#head_ttl2').hide();
                                        $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');
                                        $("#first_div").show();
                                        $("#usr_blnc_form").hide();
                                        $("#deduct_users_blnc_form").hide();
                                        $("#show_deduct_blnc_form").hide().html('');

                                    });
                                    // /***----payment deduction proceed section-----***/
                                    $('#sucess_pymnt_deduction').click(function (e) {
                                        e.preventDefault();                                    
                                        var la = $('#sucess_pymnt_deduction').ladda();
                                        la.ladda('start');
                                        $.post('Manage/DeductBalance', $(form).serialize() + "&showtd1=" + showtd1.accnt_id, function (response) {

                                            if (response) 
                                            {
                                                if (response.Resp_code == 'RCS')
                                                {
                                                    toastr.info(response.Resp_desc);

                                                    $('#head_ttl2').hide();
                                                    $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');

                                                    $('#first_div').show();
                                                    $('#prcd_deduct_blnc_form').hide();
                                                    $('#deduct_users_blnc_form').hide().html('');


                                                } 
                                                else if (response.Resp_code == 'UAC') 
                                                {
                                                    window.location.reload(true);

                                                } 
                                                else if(response.Resp_code == 'ERR') 
                                                {
                                                    $('#lrge_modal').find('.error-alert').remove();
                                                    if(response.Resp_desc instanceof Object)
                                                    {
                                                        $('#prcd_deduct_blnc_form').hide();
                                                        $('#dect_blnc_form_div').show();
                                                        $.each(response.Resp_desc, function(key, value) {
                                                        $('#'+key).parent().parent().append('<div class="text-danger error-alert">'+value+'<div>');
                                                        });
                                                    }
                                                    else
                                                    {
                                                        toastr.error(response.Resp_desc);                                                    
                                                    }

                                                }
                                            }

                                            la.ladda('stop');

                                        }, 'json').fail(function (error) {
                                           
                                            la.ladda('stop');
                                        });
                                    });

                                    return false;
                                }
                            });
                        }
                    }
                    $('#kyc_data').click(function (e) {
                        e.preventDefault();
                        $('#lrge_modal').addClass('modal-lg');
                        $("#head_ttl").hide();
                        $('#head_ttl2').show().html('Manage ' + showtd1.full_name + ' KYC Documents');

                        var acid = $(this).data('kyc_doc');
                        if (acid == showtd1.accnt_id) {
                            $("#first_div").hide();
                            var str = '<div id="model_content_' + showtd1.accnt_id + '">';
                            str += '<div class="timeline-wrapper m-t-md m-b-xxl " id="timeline-wrapper-pro"><div class="timeline-item m-b-xl" ><div class="animated-background"><div class="background-masker content-top"></div><div class="background-masker content-first-end"></div><div class="background-masker content-second-line"></div><div class="background-masker content-second-end"></div><div class="background-masker content-third-line"></div><div class="background-masker content-third-end"></div><div class="background-masker content-top-1"></div><div class="background-masker content-first-end-1"></div><div class="background-masker content-second-line-1"></div><div class="background-masker content-second-end-1"></div><div class="background-masker content-third-line-1"></div><div class="background-masker content-third-end-1"></div><div class="background-masker content-top-2"></div><div class="background-masker content-first-end-2"></div><div class="background-masker content-second-line-2"></div><div class="background-masker content-second-end-2"></div><div class="background-masker content-third-line-2"></div><div class="background-masker content-third-end-2"></div></div></div></div>';
                            str += '</div>';
                            $('#kyc_dtl_documents_' + showtd1.accnt_id + '').html(str).show();
                            user_kyc_docs(showtd1);

                        }

                    });
                    var user_kyc_docs = function (showtd1) {
                        var document = {};
                        var documnt_array = {}
                        var loader = '<div class="timeline-wrapper m-t-md m-b-xxl " id="timeline-wrapper-pro"><div class="timeline-item m-b-xl" ><div class="animated-background"><div class="background-masker content-top"></div><div class="background-masker content-first-end"></div><div class="background-masker content-second-line"></div><div class="background-masker content-second-end"></div><div class="background-masker content-third-line"></div><div class="background-masker content-third-end"></div><div class="background-masker content-top-1"></div><div class="background-masker content-first-end-1"></div><div class="background-masker content-second-line-1"></div><div class="background-masker content-second-end-1"></div><div class="background-masker content-third-line-1"></div><div class="background-masker content-third-end-1"></div><div class="background-masker content-top-2"></div><div class="background-masker content-first-end-2"></div><div class="background-masker content-second-line-2"></div><div class="background-masker content-second-end-2"></div><div class="background-masker content-third-line-2"></div><div class="background-masker content-third-end-2"></div></div></div></div>';
                        $('#kyc_dtl_documents_' + showtd1.accnt_id + '').html(loader);
                        $.ajax({
                            url: 'Manage/userDocuments',
                            dataType: "json",
                            type: 'post',
                            data: {userID: showtd1.accnt_id},
                            success: function (data) {
                                if (data.Resp_code == 'RCS') 
                                {
                                    
                                    document = data.msg;                                
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
                                        
                                        ////console.log($.isEmptyObject(v));
                                        str += '<tr>';
                                        str += ' <td>' + k + '</td>';
                                        var docdta;
                                        var dta = 'kyc_' + k + '';
                                        docdta = dta.replace(" ", "");
                                        //console.log(docdta);
                                        if ($.isEmptyObject(v) === true) 
                                        {
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
                                        } 
                                        else 
                                        {

                                            str += ' <td><a href="' + v.doc_path + '" class="success-btn" target="_blank">View</a></td>';

                                            if (v.status == "PENDING") {

                                                str += '<td><button class="btn btn-primary btn-sm apprv_doc legitRipple ladda-button" data-style="zoom-in" data-apprv="' + v.id + '" >Approve</button> <button class="btn btn-danger btn-sm reject_doc legitRipple ladda-button" data-style="zoom-in" data-reject="' + v.id + '" >Reject</button></td>';

                                            } else {

                                                str += ' <td><div class="chng_doc_apvrject">' + v.status + '  <button class="btn btn-space btn-primary aprvd_rjctd_chng_doc legitRipple ladda-button" data-style="zoom-in" data-chng_doc="' + v.id + '" id="aprvd_rjctd_chng_' + docdta + '_' + showtd1.accnt_id + '" >Change</button></div></td>';

                                            }

                                        }
                                        str += '</tr> ';
                                        i++;
                                    });
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
                                        var doc_id = $(this).data('chng_doc');
                                        var row = $(this).closest('tr').index();
                                        if (documnt_array[row] != undefined) {
                                            if (documnt_array[row].id == doc_id) {
                                                var fl = '<form id="' + chng_fl + '_form"><div class="form-group">';
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
                                                //$('#'+ chng_fl + '_file').file
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
                                            //console.log(docdta);

                                            $('#' + docdta + '_' + showtd1.accnt_id + '').change(function () {
                                                var file = $('#' + docdta + '_' + showtd1.accnt_id + '')[0]['files'][0];
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
                                                label.remove();
                                            }

                                            valid_obj['errorPlacement'] = function (error, element) {

                                                error.insertAfter(element.closest('.form-group').find('.input-group'));
                                            }

                                            valid_obj['submitHandler'] = function (form) {
                                                var row = $('#' + docdta + '_' + showtd1.accnt_id + '_sbmit').closest('tr').index();
                                                //console.log(row);
                                                if (documnt_array[row] != undefined) {
                                                    if (documnt_array[row].length == 0) {

                                                        var la = $('#' + docdta + '_' + showtd1.accnt_id + '_sbmit').ladda();
                                                        la.ladda('start');
                                                        //console.log(la);
                                                        var file = $('#' + docdta + '_' + showtd1.accnt_id + '')[0]['files'][0];
                                                        var data = new FormData();

                                                        data.append('file', file);
                                                        data.append('accntid', showtd1.accnt_id);
                                                        data.append('doctyp', k);


                                                        $.ajax({
                                                            url: 'Manage/uploadUserDocs',
                                                            type: 'POST',
                                                            data: data,
                                                            cache: false,
                                                            dataType: 'json',
                                                            processData: false, // Don't process the files
                                                            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                                                            success: function (response)
                                                            {
                                                                if (response) {

                                                                    if (response.Resp_code == 'RCS')
                                                                    {
                                                                        toastr.info(response.Resp_desc);
                                                                        $(form)[0].reset();

                                                                        // $('#'+docdta+'_'+showtd1.accnt_id+'').hide().html('');
                                                                        user_kyc_docs(showtd1);
                                                                    } 
                                                                    else if (response.Resp_code == 'UAC') 
                                                                    {
                                                                        window.location.reload(true);

                                                                    } 
                                                                    else if(response.Resp_code == 'ERR') 
                                                                    {
                                                                        toastr.error(response.Resp_desc);
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
                                        var la = $(this).ladda();
                                        var doc = $(this).data('apprv');
                                        var row = $(this).closest('tr').index();
                                        //console.log(row);
                                        //console.log(documnt_array);
                                        //console.log(documnt_array[row].id);
                                        //console.log(doc);
                                        if (documnt_array[row] != undefined) {
                                            if (documnt_array[row].id == doc) {

                                                var dataString = {'docID': doc, 'userID': showtd1.accnt_id};
                                                $.ajax({
                                                    url: 'Manage/approveUserDoc',
                                                    dataType: "json",
                                                    data: dataString,
                                                    type: 'post',
                                                    beforeSend: function(){
                                                        la.ladda('start');
                                                    },
                                                    success: function (data) {
                                                        //console.log(data);
                                                        if (data.Resp_code == 'RCS') 
                                                        {
                                                            toastr.success(data.Resp_desc);
                                                            user_kyc_docs(showtd1);
                                                        } 
                                                        else if(data.Resp_code == 'ERR')
                                                        {
                                                            toastr.error(data.error_desc);
                                                        }
                                                        else if(data.Resp_code == 'UAC')
                                                        {
                                                            window.location.reload(true);
                                                        }
                                                        la.ladda('stop');
                                                    },
                                                    error:function(){
                                                        la.ladda('stop');
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
                                        var la = $(this).ladda();
                                        var rjct_doc = $(this).data('reject');
                                        var row = $(this).closest('tr').index();
                                        //console.log(row);
                                        if (documnt_array[row] != undefined) 
                                        {
                                            if (documnt_array[row].id == rjct_doc) 
                                            {
                                                var dataString = {'docID': rjct_doc, 'userID': showtd1.accnt_id};
                                                $.ajax({
                                                    url: 'Manage/rejectUserDoc',
                                                    dataType: "json",
                                                    data: dataString,
                                                    type: 'post',
                                                    beforeSend: function(){
                                                        la.ladda('start');
                                                    },
                                                    success: function (data) 
                                                    {
                                                        //console.log(data);
                                                        if (data.Resp_code == 'RCS') {

                                                            toastr.success(data.Resp_desc);

                                                            user_kyc_docs(showtd1);
                                                        } 
                                                        else if(data.Resp_code == 'ERR')
                                                        {

                                                            toastr.error(data.Resp_desc);

                                                        }
                                                        else if(data.Resp_code == 'UAC')
                                                        {
                                                            window.location.reload(true);
                                                        }

                                                        la.ladda('stop');
                                                    },
                                                    error:function(){
                                                        la.ladda('stop');
                                                    } ///success function close       
                                                });
                                            } 
                                            else 
                                            {
                                                toastr.error("Invalid document");
                                            }
                                        } 
                                        else 
                                        {
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
                                label.remove();
                            }
                            valid_obj['errorPlacement'] = function (error, element) {

                                error.insertAfter(element.closest('.form-group').find('.input-group'));
                            }
                            valid_obj['submitHandler'] = function (form) {
                                var row = $('#' + btnid + '_file').closest('tr').index();
                                var data_row = $('#' + btnid + '_btn').data('upd_chng_dc');

                                if (doc_array[row].id != undefined) {
                                    if (doc_array[row].id = data_row) {
                                        

                                        var file = $('#' + btnid + '_file')[0]['files'][0];
                                        var data = new FormData();

                                        data.append('file', file);
                                        data.append('id', doc_array[row].id);
                                        data.append('accntid', showtd1.accnt_id);
                                        data.append('doctyp', doc_array[row].doc_name);


                                        $.ajax({
                                            url: 'Manage/uploadUserDocs',
                                            type: 'POST',
                                            data: data,
                                            cache: false,
                                            dataType: 'json',
                                            processData: false, // Don't process the files
                                            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                                            success: function (response)
                                            {
                                                
                                                if (response) {

                                                    if (response.Resp_code == 'RCS')
                                                    {
                                                        toastr.info(response.Resp_desc);
                                                        $(form)[0].reset();

                                                        // $('#'+docdta+'_'+showtd1.accnt_id+'').hide().html('');
                                                        user_kyc_docs(showtd1);
                                                    } 
                                                    else if (response.Resp_code == 'UAC') 
                                                    {
                                                        window.location.reload(true);

                                                    } 
                                                    else if(response.Resp_code == 'ERR') 
                                                    {
                                                        toastr.error(response.Resp_desc);
                                                    }

                                                }
                                                    la.ladda('stop');

                                            }, error: function (error) {
                                                la.ladda('stop');                                            
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
                }
            });
        };

        $(document).on('click', '.form-group', function(e){
            $(this).find('.error-alert').remove();
        });

        $('#crb').select2();

});