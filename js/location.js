function success() {
    console.log("Success");
}

function error() {
    console.log("Failure");
}

function giveCoordinates(lat, long) {
    //console.log(lat,long);

    document.cookie = 'latitude = ' + lat;
    document.cookie = 'longitude = ' + long;
}

navigator.geolocation.getCurrentPosition(function (position) {
    giveCoordinates(position.coords.latitude, position.coords.longitude);
});