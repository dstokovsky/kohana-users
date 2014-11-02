$(document).ready(function() {
    
    function showForm(){
        var user_id = $( this ).attr( "data-id" ), user_email = $( this ).attr( "data-email" );
        $( "div.container h1.page-header" ).text( "User " + user_email + " Edit Page" );
        $( "input#user_id" ).val( user_id );
        $( "div.row" ).hide();
        $( "div.container" ).show();
        return false;
    }
    
    function showList(){
        $( "input#user_id" ).val( "" );
        $( "div.container h1.page-header" ).text( "" );
        $( "div.row" ).show();
        $( "div.container" ).hide();
        return false;
    }
    
    $("#usersTable").tablesorter({ 
        headers: { 
            8: {
                sorter: false 
            } 
        } 
    });

    $( "a.callUserEditForm" ).on( 'click', showForm );
    $( "a.backButton" ).on( 'click', showList );

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
        submitHandler: function (form){
            $.getJSON('/member/save', $( form ).serialize(), function( data ){

            });
            return false;
        }
    });

    $( "form#userEditForm" ).on('submit', function(){
        return false;
    });
});