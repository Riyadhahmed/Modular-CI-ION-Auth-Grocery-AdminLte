$(document).ready(function () {
    $('#loader').hide();
    $('#user_registration').validate({// <- attach '.validate()' to your form
        // Rules for form validation
        rules: {
            user_name: {
                required: true
            },
			email_address: {
                required: true,
				email:true
            },
			password: {
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

            var myData = new FormData($("#user_registration")[0]);

            $.ajax({
                url: BASE_URL + 'public/user_signup/user_registration_process',
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
                        $("#status").html(data.message);
                        $('#loader').hide();
                        $("#submit").prop('disabled', false); // disable button
                        $("html, body").animate({scrollTop: 0}, "slow");
                        $("#user_registration")[0].reset();

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