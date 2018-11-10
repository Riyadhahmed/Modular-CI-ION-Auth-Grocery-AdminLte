$(document).ready(function () {
    $('#loader').hide();
    $('#assign_user_role').validate({// <- attach '.validate()' to your form
        // Rules for form validation
        rules: {
            user_name: {
                required: true
            }
        },
        // Messages for form validation
        messages: {
            user_name: {
                required: 'Please enter user name'
            }
        },
        submitHandler: function (form) {

            var myData = new FormData($("#assign_user_role")[0]);

            $.ajax({
                url: BASE_URL + 'admin/user/assign_user_role_process',
                type: 'POST',
                data: myData,
                dataType: 'json',
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#loader').show();
                    $("#submit").prop('disabled', true); // disable button
                },
                success: function (data) {

                    if (data.type === 'success') {
                        reload_table();
						
						notify_view(data.type, data.message);  
					    
						
                        $("#status").html(data.message);
                        $('#loader').hide();
                        $("#submit").prop('disabled', false); // disable button
                        $("html, body").animate({scrollTop: 0}, "slow");
                        $("#assign_user_role")[0].reset();
						
						 $('#modalUser').modal('hide'); // hide bootstrap modal

                    } else if (data.type === 'danger') {
                        if(data.errors){
                            $.each(data.errors, function (key, val) {
                                $('#error_' + key).html(val);
                            });
                        }
                        $("#status").html(data.message);
                        $('#loader').hide();
                        $("#submit").prop('disabled', false); // disable button
                        $("html, body").animate({scrollTop: 0}, "slow");

                    }
                }
            });
        }
        // <- end 'submitHandler' callback
    });                    // <- end '.validate()'

});         