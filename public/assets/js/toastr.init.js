$(document).ready(function() {
    
      $("#type-success").on("click", function() {
        toastr.success("Have fun storming the castle!", "Miracle Max Says");
    }); $("#type-info").on("click", function() {
        toastr.info("We do have the Kapua suite available.", "Turtle Bay Resort");
    }); $("#type-warning").on("click", function() {
        toastr.warning("My name is Inigo Montoya. You killed my father, prepare to die!");
    }); $("#type-error").on("click", function() {
        toastr.error("I do not think that word means what you think it means.", "Inconceivable!");
    }); $("#position-top-left").on("click", function() {
        toastr.info("I do not think that word means what you think it means.", "Top Left!", {
            positionClass: "toast-top-left"
        });
    });
    $("#position-top-right").on("click", function() {
        toastr.info("I do not think that word means what you think it means.", "Top Right!", {
            positionClass: "toast-top-right"
        });
    });
    $("#position-bottom-right").on("click", function() {
        toastr.info("I do not think that word means what you think it means.", "Bottom Right!", {
            positionClass: "toast-bottom-right"
        });
    });
       $("#position-top-center").on("click", function() {
        toastr.info("I do not think that word means what you think it means.", "Top Center!", {
            positionClass: "toast-top-center"
        });
    });
       $("#position-bottom-left").on("click", function() {
        toastr.info("I do not think that word means what you think it means.", "Bottom Left!", {
            positionClass: "toast-bottom-left"
        });     
    });
      $("#position-bottom-center").on("click", function() {
        toastr.info("I do not think that word means what you think it means.", "Bottom Center!", {
            positionClass: "toast-bottom-center"
        });     
    });
});