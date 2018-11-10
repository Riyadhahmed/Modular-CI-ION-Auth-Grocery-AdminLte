$(document).ready(function () {
    $('#loader').hide();
   // alert();
    $('#update_group').validate({// <- attach '.validate()' to your form
        // Rules for form validation
        rules: {
            group_name: {
                required: true
            }
		},
        // Messages for form validation
        messages: {
            group_name: {
                required: 'Please enter group name'
            }
        },
        submitHandler: function (form) {

            var myData = new FormData($("#update_group")[0]);

            $.ajax({
                url: BASE_URL + 'company/group/update_group_process',
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
						
						 $('#modalGroup').modal('hide'); // hide bootstrap modal
                        notify_view(data.type, data.message);
						reload_table();
                      
                        $('#loader').hide();
                        $("#submit").prop('disabled', false); // disable button
                        $("html, body").animate({scrollTop: 0}, "slow");
                        $("#update_group")[0].reset();

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
       