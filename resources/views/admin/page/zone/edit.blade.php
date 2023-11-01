@extends('admin.layouts.master')

@section('style')
    <style>
        #map {
            width: 100%;
            height: 500px;
        }

        .map-container {
            position: relative;
            display: flex;
            justify-content: flex-start;
            margin-bottom: 10px;
        }

        .controls {
            background-color: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            border: 1px solid transparent;
            border-radius: 5px;
            box-sizing: border-box;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            margin-top: 10px;
            outline: none;
            padding: 5px 10px;
            text-overflow: ellipsis;
            width: 300px;
        }

        .controls:focus {
            border-color: #4d90fe;
        }
    </style>
@endsection

@section('title')
    <title>Edit Zone</title>
@endsection

@section('breadcrumb-items')
    <!-- Include breadcrumb items -->
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <form class="row g-3" id="zoneForm" method="post" action="{{ route('zones.update', $zone->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="col-md-12">
                            <label for="input1" class="form-label">Zone Name</label>
                            <input type="text" class="form-control" id="input1" name="name" placeholder="Zone Name" required value="{{ $zone->name }}">
                        </div>

                        <div class="col-md-12">
                            <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                            <div id="map"></div>
                        </div>

                        <!-- Hidden input fields to store latitude and longitude values -->
                        <input type="hidden" id="coordinates" name="coordinates">

                        <div class="col-md-12">
                            <div class="d-md-flex d-grid justify-content-end gap-3">
                                <button type="button" class="btn btn-primary px-4" onclick="submitForm()">Update</button>
                                <a href="{{ route('list.zone') }}" class="btn btn-light px-4">Cancel</a>                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var map; // Global declaration of the map
        var drawingManager;
        var lastpolygon = null;

        function resetMap(controlDiv) {
            // Set CSS for the control border.
            const controlUI = document.createElement("div");
            controlUI.style.backgroundColor = "#fff";
            controlUI.style.border = "2px solid #fff";
            controlUI.style.borderRadius = "3px";
            controlUI.style.boxShadow = "0 2px 6px rgba(0,0,0,.3)";
            controlUI.style.cursor = "pointer";
            controlUI.style.marginTop = "8px";
            controlUI.style.marginBottom = "22px";
            controlUI.style.textAlign = "center";
            controlUI.title = "Reset map";
            controlDiv.appendChild(controlUI);
            // Set CSS for the control interior.
            const controlText = document.createElement("div");
            controlText.style.color = "rgb(25,25,25)";
            controlText.style.fontFamily = "Roboto,Arial,sans-serif";
            controlText.style.fontSize = "10px";
            controlText.style.lineHeight = "16px";
            controlText.style.paddingLeft = "2px";
            controlText.style.paddingRight = "2px";
            controlText.innerHTML = "X";
            controlUI.appendChild(controlText);
            // Setup the click event listeners: simply set the map to Chicago.
            controlUI.addEventListener("click", () => {
                if (lastpolygon) {
                    lastpolygon.setMap(null);
                    lastpolygon = null;
                    document.getElementById('coordinates').value = '';
                }
            });
        }

        function initialize() {
            var myLatlng = { lat: 23.8103, lng: 90.4125 }; // Replace with your desired center coordinates

            var myOptions = {
                zoom: 13,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map"), myOptions);
            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [google.maps.drawing.OverlayType.POLYGON]
                },
                polygonOptions: {
                    editable: true
                }
            });
            drawingManager.setMap(map);

            drawingManager.addListener("overlaycomplete", function(event) {
                if (lastpolygon) {
                    lastpolygon.setMap(null);
                }
                document.getElementById('coordinates').value = JSON.stringify(event.overlay.getPath().getArray());
                lastpolygon = event.overlay;
                auto_grow();
            });

            const resetDiv = document.createElement("div");
            resetMap(resetDiv, lastpolygon);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(resetDiv);

            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    const icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25),
                    };
                    // Create a marker for each place.
                    markers.push(
                        new google.maps.Marker({
                            map,
                            icon,
                            title: place.name,
                            position: place.geometry.location,
                        })
                    );

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }

        $('#reset_btn').click(function() {
            if (lastpolygon) {
                lastpolygon.setMap(null);
                lastpolygon = null;
                document.getElementById('coordinates').value = '';
            }
        });

        // function resetForm() {
        //     $('#input1').val('');
        //     $('#coordinates').val('');
        //     if (lastpolygon) {
        //         lastpolygon.setMap(null);
        //         lastpolygon = null;
        //     }
        //     auto_grow();
        // }
        function resetForm() {
            // Reset the form inputs
            document.getElementById('input1').value = '';
            document.getElementById('coordinates').value = '';

            // Remove the last drawn polygon from the map
            if (lastpolygon) {
                lastpolygon.setMap(null);
                lastpolygon = null;
            }

            // Reset the map center and zoom level
            var myLatlng = { lat: 23.8103, lng: 90.4125 }; // Replace with your desired center coordinates
            map.setCenter(myLatlng);
            map.setZoom(13);
        }

        function auto_grow() {
            let element = document.getElementById("coordinates");
            element.style.height = "5px";
            element.style.height = element.scrollHeight + "px";
        }

        // function submitForm() {
        //     $('#zoneForm').submit();
        // }
        function submitForm() {
            // Get the polygon coordinates and store them in the coordinates input field
            if (lastpolygon) {
                var coordinates = lastpolygon.getPath().getArray().map(function(point) {
                    return "(" + point.lat() + ", " + point.lng() + ")";
                }).join(",");

                document.getElementById('coordinates').value = coordinates;
            }

            // Submit the form directly
            document.getElementById('zoneForm').submit();
        }

        document.addEventListener('DOMContentLoaded', function() {
            initialize();
        });
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    map.setCenter(pos);
                    if (currentLocationMarker) {
                        currentLocationMarker.setMap(null);
                    }
                    currentLocationMarker = new google.maps.Marker({
                        position: pos,
                        map: map,
                        title: "Your Location",
                        icon: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
                    });
                },
                () => {
                    // If user denies permission or location service is off, use default location
                    const defaultLocation = { lat: 23.8103, lng: 90.4125 };
                    map.setCenter(defaultLocation);
                }
            );
        } else {
            // Browser doesn't support Geolocation
            const defaultLocation = { lat: 23.8103, lng: 90.4125 };
            map.setCenter(defaultLocation);
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCc9NIB-ScnkTvQZzrB53TfaCwo1XUegHM&callback=initialize&libraries=drawing,places&v=3.49" async defer></script>
@endsection