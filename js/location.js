//used to indicate the success of the geolocation
function success() {
    console.log("Success");
}

//used to indicate the failure of the geolocation
function error() {
    console.log("Failure");
}

//sets cookies of the latitude and longitude to be used by PHP
function giveCoordinates(lat, long) {
    //console.log(lat,long);

    document.cookie = 'latitude = ' + lat;
    document.cookie = 'longitude = ' + long;
}

//this gets the current location of the user and runs the giveCoordinates function, passing the latitude and longitude
navigator.geolocation.getCurrentPosition(function (position) {
    giveCoordinates(position.coords.latitude, position.coords.longitude);
});