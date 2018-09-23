var App = {
    notamRequest: {
        form: $('#form'),
        markers: [],
        init: function() {
            this.form.submit(function() {
                App.notamRequest.clearErrors();
                App.notamRequest.ajaxRequest();
                return false;
            });
        }, 
        ajaxRequest: function() {
            var icao = $('#icao').val();                    
            $.ajax({
                method: 'GET',
                url: this.form.attr('action'),
                data: {
                    icao: icao
                },
                success: function(res) {
                    if (!res.status) {
                        App.notamRequest.showErrors(res.error);
                    }
                    else {
                        App.notamRequest.showMarkers(res.data);
                    }
                }                
            });
        },
        showErrors: function(error) {            
            $('.error-text').text(error);
            $('#icao').addClass('error');
            $('#icao').change(function(){
                App.notamRequest.clearErrors();
            });
        },
        showMarkers: function(data) {            
            //remove previous markers            
            for(var i = 0; i < App.notamRequest.markers.length; i++) {
                App.notamRequest.markers[i].setMap(null);
            }
            //if there are several notams for the same location, concatenate its notices
            // to show them in one marker
            var dataFormatted = {};
            for (var i = 0; i < data.length; i++) {                 
                var key = data[i].dms; 
                var notice = "<div><strong>" + data[i].id + ") </strong>" +  data[i].notice + "</div>"
                if (typeof dataFormatted[key] != 'undefined') {
                    dataFormatted[key].notice += notice;
                } else {
                    data[i].notice = notice;
                    dataFormatted[key] = data[i];
                }
            }
            var infoWindow = new google.maps.InfoWindow();            
            for (var notamDms in dataFormatted) { 

                var notam = dataFormatted[notamDms];                
                var location = new google.maps.LatLng(notam.latitude, notam.longitude);
                
                var marker = new google.maps.Marker({
                    position: location,      
                    animation: google.maps.Animation.DROP,              
                    title: notam.id,
                    icon: assetsBaseDir + 'image/warning-sign-pin.png'
                });
                     
                marker.setMap(map);                
                (function (marker, notam) {
                    google.maps.event.addListener(marker, "click", function (e) {                        
                        infoWindow.setContent("<div>" + notam.notice + "</div>");
                        infoWindow.open(map, marker);
                    });
                })(marker, notam);
                App.notamRequest.markers.push(marker);
            }
        },
        clearErrors: function() {            
            $('.error-text').text('');
            $('#icao').removeClass('error');
        }
    },
    init: function(){
        this.notamRequest.init();
    }
}
$(document).ready(function(){
    App.init();
})

