$(function () {
 /**Pie chartJs**/   
       var doughnutData = {
        labels: ["App","Software","Laptop" ],
        datasets: [{
            data: [300,50,100],
            backgroundColor: ["#0073ff","#e65252","#f0ad4e"]
        }]
    } ;
    var doughnutOptions = {
        responsive: true
    };
    var ctx4 = document.getElementById("doughnutChart").getContext("2d");
    new Chart(ctx4, {type: 'doughnut', data: doughnutData, options:doughnutOptions}); 
    /** line chart **/
        var lineData = {
        labels: ["January", "February", "March", "April", "May", "June", "July"],
        datasets: [
            {
                label: "Register",
                backgroundColor: 'rgba(21,158,238,0.5)',
                borderColor: "rgba(21,158,238,0.7)",
                pointBackgroundColor: "rgba(21,158,238,1)",
                pointBorderColor: "#fff",
                data: [34, 54, 21, 54, 34, 27, 90]
            },{
                label: "Visitors",
                backgroundColor: 'rgba(220, 220, 220, 0.5)',
                pointBorderColor: "#fff",
                data: [65, 35, 80, 67, 56, 55, 40]
            }
        ]
    };

    var lineOptions = {
        responsive: true
    };


    var ctx = document.getElementById("lineChart").getContext("2d");
    new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});
});


