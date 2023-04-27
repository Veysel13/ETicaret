 $(document).ready(function () {
                //Basic
                $('#sa-basic').on('click',function () {
                    swal("Here's a message!");
                });
                //A title with a text under
                $('#sa-title').on('click',function () {
                    swal("Here's a message!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat, tincidunt vitae ipsum et, pellentesque maximus enim. Mauris eleifend ex semper, lobortis purus sed, pharetra felis");
                });

                //Success Message
                $('#sa-success').on('click',function () {
                    swal("Good job!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat, tincidunt vitae ipsum et, pellentesque maximus enim. Mauris eleifend ex semper, lobortis purus sed, pharetra felis", "success");
                });

                //Warning Message
                $('#sa-warning').on('click',function () {
                    swal({
                        title: "Are you sure?",
                        text: "You will not be able to recover this imaginary file!",
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonClass: 'btn-secondary ',
                        confirmButtonClass: 'btn-warning',
                        confirmButtonText: "Yes, delete it!",
                        closeOnConfirm: false
                    }, function () {
                        swal("Deleted!", "Your imaginary file has been deleted.", "success");
                    });
                });

                //Parameter
                $('#sa-params').on('click',function () {
                    swal({
                        title: "Are you sure?",
                        text: "You will not be able to recover this imaginary file!",
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonClass: 'btn-secondary ',
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel plx!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }, function (isConfirm) {
                        if (isConfirm) {
                            swal("Deleted!", "Your imaginary file has been deleted.", "success");
                        } else {
                            swal("Cancelled", "Your imaginary file is safe :)", "error");
                        }
                    });
                });

                //Custom Image
                $('#sa-image').on('click',function () {
                    swal({
                        title: "Sweet!",
                        text: "Here's a custom image.",
                        imageUrl: "./images/user1.jpg"
                    });
                });

                //Auto Close Timer
                $('#sa-close').on('click',function () {
                    swal({
                        title: "Auto close alert!",
                        text: "I will close in 2 seconds.",
                        timer: 2000,
                        showConfirmButton: false
                    });
                });

                //Primary
                $('#primary-alert').on('click',function () {
                    swal({
                        title: "Are you sure?",
                        text: "You will not be able to recover this imaginary file!",
                        type: "info",
                        showCancelButton: true,
                        cancelButtonClass: 'btn-secondary  ',
                        confirmButtonClass: 'btn-primary  ',
                        confirmButtonText: 'Primary!'
                    });
                });

                //Info
                $('#info-alert').on('click',function () {
                    swal({
                        title: "Are you sure?",
                        text: "You will not be able to recover this imaginary file!",
                        type: "info",
                        showCancelButton: true,
                        cancelButtonClass: 'btn-secondary ',
                        confirmButtonClass: 'btn-info  ',
                        confirmButtonText: 'Info!'
                    });
                });

                //Success
                $('#success-alert').on('click',function () {
                    swal({
                        title: "Are you sure?",
                        text: "You will not be able to recover this imaginary file!",
                        type: "success",
                        showCancelButton: true,
                        cancelButtonClass: 'btn-secondary ',
                        confirmButtonClass: 'btn-success  ',
                        confirmButtonText: 'Success!'
                    });
                });

                //Warning
                $('#warning-alert').on('click',function () {
                    swal({
                        title: "Are you sure?",
                        text: "You will not be able to recover this imaginary file!",
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonClass: 'btn-secondary ',
                        confirmButtonClass: 'btn-warning  ',
                        confirmButtonText: 'Warning!'
                    });
                });

                //Danger
                $('#danger-alert').on('click',function () {
                    swal({
                        title: "Are you sure?",
                        text: "You will not be able to recover this imaginary file!",
                        type: "error",
                        showCancelButton: true,
                        cancelButtonClass: 'btn-secondary',
                        confirmButtonClass: 'btn-danger',
                        confirmButtonText: 'Danger!'
                    });
                });
            });



