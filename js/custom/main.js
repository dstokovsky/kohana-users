$(document).ready(function() {
    
    $.tablesorter.themes.bootstrap = {
        // these classes are added to the table. To see other table classes available,
        // look here: http://getbootstrap.com/css/#tables
        table      : 'table table-bordered',
        caption    : 'caption',
        header     : 'bootstrap-header', // give the header a gradient background
        footerRow  : '',
        footerCells: '',
        icons      : '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
        sortNone   : 'bootstrap-icon-unsorted',
        sortAsc    : 'icon-chevron-up glyphicon glyphicon-chevron-up',     // includes classes for Bootstrap v2 & v3
        sortDesc   : 'icon-chevron-down glyphicon glyphicon-chevron-down', // includes classes for Bootstrap v2 & v3
        active     : '', // applied when column is sorted
        hover      : '', // use custom css here - bootstrap class may not override it
        filterRow  : '', // filter row class
        even       : '', // odd row zebra striping
        odd        : ''  // even row zebra striping
    };
    $("#usersTable").tablesorter({
        // this will apply the bootstrap theme if "uitheme" widget is included
        // the widgetOptions.uitheme is no longer required to be set
        theme : "bootstrap",

        widthFixed: false,

        headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

        // widget code contained in the jquery.tablesorter.widgets.js file
        // use the zebra stripe widget if you plan on hiding any rows (filter widget)
        widgets : [ "uitheme", "zebra" ],

        widgetOptions : {
          // using the default zebra striping class name, so it actually isn't included in the theme variable above
          // this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
          zebra : ["even", "odd"],

          // reset filters button
          filter_reset : ".reset"

          // set the uitheme widget to use the bootstrap theme class names
          // this is no longer required, if theme is set
          // ,uitheme : "bootstrap"

        }
    }).tablesorterPager({

        // target the pager markup - see the HTML block below
        container: $(".ts-pager"),

        // target the pager page select dropdown - choose a page
        cssGoto  : ".pagenum",

        // remove rows from the table to speed up the sort of large tables.
        // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
        removeRows: false,

        // output string - default is '{page}/{totalPages}';
        // possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
        output: '{startRow} - {endRow} / {filteredRows} ({totalRows})'
    });
    
    $(document).on( 'click', 'a.callUserEditForm', showForm );
    $(document).on( 'click', 'a.callUserDelete', deleteUser );
    $(document).on( 'click', 'a.backButton', showList );
    
    function showForm(){
        var user_id = $( this ).attr( "data" );
        if( user_id ){
            $.getJSON('/user/get', { id: user_id }, function( data ){
                if( data.success ){
                    $( "input#user_id" ).val( data.user.id );
                    $( "input#user_name" ).val( data.user.name );
                    $( "input#user_surname" ).val( data.user.surname );
                    $( "input#user_email" ).val( data.user.email );
                    $( "input#user_address" ).val( data.user.address );
                    $( "input#user_country" ).val( data.user.country );
                    $( "input#user_city" ).val( data.user.city );
                    $( "div.container h1.page-header" ).text( "User " + data.user.email + " Edit Page" );
                }else{
                    $( "div.alert-danger" ).append( data.error + "<br />" );
                    $( "div.alert-danger" ).show();
                }
            });
        }else{
            $( "div.container h1.page-header" ).text( "Create New User Page" );
        }
        $( "div.row" ).hide();
        $( "div.container" ).show();
        return false;
    }
    
    function deleteUser(){
        var user_id = $( this ).attr( "data" );
        if( user_id ){
            $( "tr#user_" + user_id ).hide( "slow" );
            $.getJSON('/user/delete', { id: user_id }, function( data ){
                if( data.success ){
                    $( "tr#user_" + user_id ).remove();
                    $("#usersTable").trigger( 'update' );
                }else{
                    $( "tr#user_" + user_id ).show( "slow" );
                }
            });
        }
        return false;
    }
    
    function showList(){
        $( "input#user_id" ).val( "" );
        $( "input#user_name" ).val( "" );
        $( "input#user_surname" ).val( "" );
        $( "input#user_email" ).val( "" );
        $( "input#user_address" ).val( "" );
        $( "input#user_country" ).val( "" );
        $( "input#user_city" ).val( "" );
        $( "div.container h1.page-header" ).text( "" );
        $( "div.row" ).show();
        $( "div.container" ).hide();
        $( "div.alert-danger" ).hide();
        $( "div.alert-success" ).hide();
        return false;
    }

    $( "form#userEditForm" ).validate({
        rules: {
            'user[name]': {
                required: true,
                minlength: 2,
                maxlength: 50
            },
            'user[surname]': {
                required: true,
                minlength: 2,
                maxlength: 50
            },
            'user[email]': {
                required: true,
                email: true,
                maxlength: 100
            },
            'user[address]': {
                maxlength: 100
            },
            'user[city]': {
                required: true,
                maxlength: 30
            },
            'user[country]': {
                required: true,
                maxlength: 30
            }
        },
        messages: {
            'user[name]': {
                required: "Please specify your first name",
                minlength: $.validator.format("At least {0} characters required"),
                maxlength: $.validator.format("Maximum {0} characters allowed")
            },
            'user[surname]': {
                required: "Please specify your second name",
                minlength: $.validator.format("At least {0} characters required"),
                maxlength: $.validator.format("Maximum {0} characters allowed")
            },
            'user[email]': {
                required: "Please specify your email",
                email: "Please specify valid email",
                maxlength: $.validator.format("Maximum {0} characters allowed")
            },
            'user[address]': {
                maxlength: $.validator.format("Maximum {0} characters allowed")
            },
            'user[city]': {
                required: "Please specify your city",
                maxlength: $.validator.format("Maximum {0} characters allowed")
            },
            'user[country]': {
                required: "Please specify your country",
                maxlength: $.validator.format("Maximum {0} characters allowed")
            }
        },
        submitHandler: function ( form ){
            var user_id = $( form ).find( "#user_id" ).val();
            $.getJSON('/user/save', $( form ).serialize(), function( data ){
                if( data.success ){
                    $( "div.alert-danger" ).html( "" );
                    $( "div.alert-danger" ).hide();
                    $( "div.container h1.page-header" ).text( "User " + data.user.email + " Edit Page" );
                    $( "div.alert-success" ).text( "Successfully added." );
                    $( "div.alert-success" ).show();
                    $( "input#user_id" ).val( data.user.id );
                    if( !user_id ){
                        $( "table#usersTable tbody" ).append( "<tr id='user_" + data.user.id + "'>" + 
                            "<td>" + data.user.id + "</td>" + 
                            "<td>" + data.user.name + "</td>" + 
                            "<td>" + data.user.surname + "</td>" + 
                            "<td>" + data.user.email + "</td>" + 
                            "<td>" + data.user.personal_code + "</td>" + 
                            "<td>" + data.user.address + "</td>" + 
                            "<td>" + data.user.city + "</td>" + 
                            "<td>" + data.user.country + "</td>" + 
                            "<td>" + 
                            "<a class='link callUserEditForm' href='#' data='" + data.user.id + "'>Edit</a>&nbsp;" + 
                            "<a class='link callUserDelete' href='#' data='" + data.user.id + "'>Delete</a>" + 
                            "</td>" + 
                            "</tr>" );
                    }else{
                        $( "tr#user_" + data.user.id ).html( "<td>" + data.user.id + "</td>" + 
                            "<td>" + data.user.name + "</td>" + 
                            "<td>" + data.user.surname + "</td>" + 
                            "<td>" + data.user.email + "</td>" + 
                            "<td>" + data.user.personal_code + "</td>" + 
                            "<td>" + data.user.address + "</td>" + 
                            "<td>" + data.user.city + "</td>" + 
                            "<td>" + data.user.country + "</td>" + 
                            "<td>" + 
                            "<a class='link callUserEditForm' href='#' data='" + data.user.id + "'>Edit</a>&nbsp;" + 
                            "<a class='link callUserDelete' href='#' data='" + data.user.id + "'>Delete</a>" + 
                            "</td>" );
                    }
                    $("#usersTable").trigger( 'update' );
                }else{
                    $( "div.alert-danger" ).html( "" );
                    if( data.errors ){
                        for( var index in data.errors ){
                            $( "div.alert-danger" ).append( data.errors[ index ] + "<br/>" );
                        }
                        $( "div.alert-danger" ).show();
                    }
                }
            });
            return false;
        }
    });

    $( "form#userEditForm" ).on('submit', function(){
        return false;
    });
});