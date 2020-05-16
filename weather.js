$(document).ready(function() {
        $.ajax({
            url: 'http://localhost:8888/SmartMirror/weather.php',
            dataType: 'json',
            success: function(data) {
                var temp = JSON.stringify(data.main.temp);
                var Ftemp = parseInt(temp)*1.8-459.67;

                var weather = JSON.stringify(data.weather[0].id);
                var description = JSON.stringify(data.weather[0].description);
                $("#weather").append('<p>'+ Ftemp.toPrecision(2) +'&#176;<i class="owf owf-'+weather+'"></i></p>');
                
            },
             error: function() {
                $("#weather").append("error");
                alert('error');
            }
        });
    });