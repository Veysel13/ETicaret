$().ready(function () {
// validate signup form on keyup and submit
    $("#form-validation").validate({
        rules: {
fname: "required",
				lastname: "required",
				uname: {
					required: true,
					minlength: 2
				},
				pass1: {
					required: true,
					minlength: 8
				},
				pass2: {
					required: true,
					minlength: 8,
					equalTo: "#pass1"
				},
				mobile: {
					required: true,
                                        minlength: 10,
                                        maxlength: 13
				},
                                email: {
					required: true,
                                        email: true
				},
                                sub: {
					required: true,
                                        minlength: 4
				},
				topic: {
					required: "#newsletter:checked",
					minlength: 2
				},
				agree: "required"

        }
    });
});

