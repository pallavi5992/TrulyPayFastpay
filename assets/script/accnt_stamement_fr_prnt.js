var Mergereport = function () {

    var currenttime = function ()
    {
        var today = new Date();
        return today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate() + ' ' + today.getHours() + ':' + today.getMinutes() + ':' + today.getSeconds();
    }

    var prevtime = function ()
    {
        var today = new Date();
        return today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + (today.getDate() - 1);
    }

    var Mreport = function () {

        var checked_item = [];
        var checkedfieldmy = {};
        var imy;
        var v;
        var indexmy;
        var arrayDate = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        var today = new Date();


        var fromformat = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
        var selectedTomy = currenttime();
        var selectedFrommy = fromformat;



  var newExportAction = function (e, dt, button, config) {
      var self = this;
      var oldStart = dt.settings()[0]._iDisplayStart;

      dt.one('preXhr', function (e, s, data) {
          // Just this once, load all data from the server...
          data.start = 0;
          data.length = -1;

          dt.one('preDraw', function (e, settings) {
              // Call the original action function
              oldExportAction(self, e, dt, button, config);

              dt.one('preXhr', function (e, s, data) {
                  // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                  // Set the property to what it was before exporting.
                  settings._iDisplayStart = oldStart;
                  data.start = oldStart;
              });

              // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
              setTimeout(dt.ajax.reload, 0);

              // Prevent rendering of the full data to the DOM
             return false;
          });
     });

//      // Requery the server with the new one-time export settings
     dt.ajax.reload();
  };

   var oldExportAction = function (self, e, dt, button, config) {
       if (button[0].className.indexOf('buttons-csv') >= 0) {
           if ($.fn.dataTable.ext.buttons.csvHtml5.available(dt, config)) {
                $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config);
            }
            else {
               $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
           }
        } else if (button[0].className.indexOf('buttons-print') >= 0) {
            $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
        }
    };



        var oTable = $('#mergereports').DataTable({
            //order: [[0, 'desc'], [1, 'asc']],
            order:[],
            "processing": true,
            "serverSide":true,
            "ajax": {
                url: "Reports/account_statement_fr_parent",
                type: "POST",
                data: function (data) {
                    console.log(data);
                    if (selectedFrommy) {
                        data.from = selectedFrommy;
						 data.to = selectedTomy;
                    }
                },
            },
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

            buttons: {
                dom: {
                    button: {
                        className: 'btn btn-default'
                    }
                },
                buttons: [
                    {extend: 'csv'},
                ]
            },
            responsive: {
                details: {
                    type: 'column',
                    target: 'tr'
                }
            },
		/* 	 columns: [
						 {title: '<span>Date/Time</span>', data: 'date', class: 'all'},
						  {title: '<span>Narration</span>', data: 'stype', class: 'all'},
						    {title: '<span>Amount (&#8377;)</span>', data: 'amt', class: 'all'},
							{title: '<span>Charged Amount (&#8377;)</span>', data: 'amt', class: 'all'},
							{title: '<span>Commission (&#8377;)</span>', data: 'chargeamt', class: 'all'},
							{title: '<span>TDS (&#8377;)</span>', data: 'chargeamt', class: 'all'},
							{title: '<span>Balance (&#8377;)</span>', data: 'chargeamt', class: 'all'},
                       
            ], */
//        drawCallback: function (oSettings) {
//
//            oSettings.json.totalrefund=oSettings.json.totalrefund?oSettings.json.totalrefund:0;
//
//            $('.sumref').html('<div class="col-sm-6 mb-10 mt-20 text-danger text-black text-size-large">Total Refund Amt: &#x20b9; '+oSettings.json.totalrefund+'</div>');
//
//            },
            "lengthMenu": [
                [5, 10, 15, 20],
                [5, 10, 15, 20] // change per page values here
            ],
            "columnDefs": [{
                    "targets": [0, 1, 2, 3, 4, 5, 6], // column or columns numbers
                    "orderable": false, // set orderable for selected columns
                }],
            dom: '<"header-my"><"datatable-header search-data"fBl>r<"table-scrollable"t><"datatable-footer"ip><"sumref">',
        });

       	  var top ='<div class="row mt-3">';
			  top += '<div class="col-lg-4">';
			/*   top += '<label>From</label>'; */
			  top += '<input type="text" class="form-control" id="frommy" readonly>';
			  top += '</div>';
			  top += '<div class="col-lg-4">';
			/*   top += '<label>To</label>'; */
			  top += '<input class="form-control"  type="text" id="tomy" readonly>';
			  top += '</div>';
			  top += '<div class="col-lg-2">';
			 top += '<div class="form-group">';
                   top += '<button id="srchsubmy" class="btn btn-success btn-md" type="button">';
                   top += '<i class="fa fa-search fa-fw"></i>Search</button>';
                 top += '</div>';
			  top += '</div>';
			   top += '<div class="col-lg-2">';
			   top += '<div class="form-group">';
                    top += '<button id="my_reset" class="btn btn-danger btn-md" type="button"> Reset</button>';
                 top += '</div>';
			  top += '</div>';
			  top += '</div>';
	
			  $('.header-my').append(top);


        var year = (new Date).getFullYear();
        var month = (new Date).getMonth();
        $('#frommy').datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: new Date(year, month - 5, 1),
            maxDate: 0,
            changeMonth: true,
            changeYear: true,
            "onSelect": function (date) {
               var selectedDate = new Date(date);
               var msecsInADay = 86400000;
               var currentdate = new Date();
               var startDate = $(this).datepicker('getDate');
               var minDate = $(this).datepicker('getDate');
             var mo = new Date(startDate.getFullYear(), startDate.getMonth() + 1, 0);
              var endDate = new Date(selectedDate.getTime() + msecsInADay);
              $("#tomy").datepicker( "option", "minDate", selectedDate);
              if(startDate.getMonth() == currentdate.getMonth() ){
                 $("#tomy").datepicker( "option", "maxDate", currentdate);
                 $("#tomy").datepicker("setDate", new Date());
                 selectedTomy = currenttime();
             }else{
                $("#tomy").datepicker( "option", "maxDate", mo);
                $("#tomy").datepicker("setDate", new Date());
                selectedTomy = startDate.getFullYear()+'-'+(startDate.getMonth() + 1)+'-'+arrayDate[startDate.getMonth()];
             }
                selectedFrommy = date;
            }
        }).datepicker("setDate", new Date());
             $('#tomy').datepicker({
               dateFormat:'yy-mm-dd',
               minDate: 0,
               maxDate: 0,
               changeMonth: false,
               changeYear: false,
              "onSelect":function(date){
                selectedTomy = date;
            }
            }).datepicker("setDate", new Date());



        $('#srchsubmy').on('click', function () {

            oTable.ajax.reload();
        });


        $('#my_reset').on('click', function () {
            checkedfieldmy = {};
            // selectedTomy = currenttime();
            selectedFrommy = fromformat;
            $('#frommy').datepicker("setDate", new Date());
             $("#tomy").datepicker("option", "maxDate", new Date());
                $("#tomy").datepicker("option", "minDate", 0);

              $('#tomy').val('').datepicker("setDate", new Date());
            oTable.ajax.reload();

        });




    }



    return{
        init: function () {
            Mreport();

        }
    }

}();


    
$(document).ready(function () {

    Mergereport.init();
   


});