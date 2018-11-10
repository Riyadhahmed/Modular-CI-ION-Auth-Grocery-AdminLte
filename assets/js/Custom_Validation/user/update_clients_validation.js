$(document).ready(function () {
    $('#loader').hide();
    //alert();
    $('#update_new_clients').validate({// <- attach '.validate()' to your form
        // Rules for form validation
        rules: {
            clients_name: {
                required: true
            },
			clients_address: {
                required: true
            },
			clients_contact_no: {
                required: true,
				number: true
            },
			clients_contact_no_extra: {
                number: true
            }
        },
        // Messages for form validation
        messages: {
            clients_name: {
                required: 'Please enter clients name'
            }
        },
        submitHandler: function (form) {

            var myData = new FormData($("#update_new_clients")[0]);

            $.ajax({
                url: BASE_URL + 'admin/clients/update_new_clients_process',
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
                        notify_view(data.type, data.message);
                      //  $("#status").html(data.message);
                        $('#loader').hide();
                        $("#submit").prop('disabled', false); // disable button
                        $("html, body").animate({scrollTop: 0}, "slow");
                        $("#update_new_clients")[0].reset();

                    } else if (data.type === 'danger') {
                        notify_view(data.type, data.message);
                        if(data.errors){
                            $.each(data.errors, function (key, val) {
                                $('#error_' + key).html(val);
                            });
                        }
                       // $("#status").html(data.message);
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