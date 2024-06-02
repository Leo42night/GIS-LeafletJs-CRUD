@extends('layouts.app')
@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-search@2.9.0/dist/leaflet-search.min.css" />
    <style>
        #map {
            min-height: 700px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="form-group mb-4">
            <input type="text" name="" id="textsearch" placeholder="search place here..." class="form-control">
        </div>
        <div class="card">
            <div class="card-body">
                <!-- Create a div for the map -->
                <div id="map" style="height: 400px;"></div>
            </div>
        </div>
    </div>
    @include('notify::components.notify')
@endsection

@push('scripts')
    <!-- Leaflet JavaScript -->
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <!-- Include Leaflet Search JS -->
    <script src="https://unpkg.com/leaflet-search@2.9.0/dist/leaflet-search.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script>
        var center = [{{ config('leafletsetup.map_center_latitude') }}, {{ config('leafletsetup.map_center_longitude') }}]
        // Initialize the map
        var map = L.map('map').setView(center, {{ config('leafletsetup.zoom_level') }});

        // Add a tile layer (you can use any tile provider)
        var tile = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: "&copy; <a href='https://www.openstreetmap.org/copyright'>OpenStreetMap</a> contributors"
        }).addTo(map);

        // ikuti modul
        axios.get("{{ route('place.api') }}")
            .then(function(response) {
                //console.log(response.data);
                L.geoJSON(response.data, {
                        pointToLayer: function(geoJsonPoint, latlng) {
                            return L.marker(latlng);
                        }
                    })
                    .bindPopup(function(layer) {
                        //return layer.feature.properties.map_popup_content;
                        return ("<div><img class='w-100' src='"+ layer.feature.properties
                            .image + "'></div><div class='my-2'><strong>Place Name</strong> :<br>" + layer.feature.properties
                            .name + '</div> <div class="my-2"><strong>Description</strong>:<br>' + layer.feature
                            .properties.description + '</div><div class="my-2"><strong>Address</strong>:<br>' +
                            layer.feature.properties.address + '</div>');
                    }).addTo(map);
                console.log(response.data);
            }).catch(function(error) {
                console.log(error);
            });
        //SIMPLE SEARCH LOCATION
        var data = [
            <?php
            foreach ($places as $key => $value) {
            ?> {
                "loc": [<?= $value->latitude ?>, <?= $value->longitude ?>],
                "title": "<?= $value->name ?>"
            },
            <?php } ?>
        ];

        var markersLayer = new L.LayerGroup(); //layer contain searched elements

        map.addLayer(markersLayer);
        console.log(data);
        var controlSearch = new L.Control.Search({
            position: 'topleft',
            layer: markersLayer,
            initial: false,
            zoom: 17,
            markerLocation: true
        })
        map.addControl(controlSearch);

        ////////////populate map with markers from sample data
        for (i in data) {
            var title = data[i].title, //value searched
                loc = data[i].loc, //position found
                marker = new L.Marker(new L.latLng(loc), {
                    title: title
                }); //se property searched
            marker.bindPopup('title: ' + title);
            markersLayer.addLayer(marker);
        }
        // SIMPLE SEARCH LOCATION
        $('#textsearch').on('keyup', function(e) {
            controlSearch.searchText(e.target.value);
        });

        // Create a search layer
        // var searchLayer = L.layerGroup().addTo(map);

        // Add data to the search layer (you can customize this)
        // ...

        // Add the search control to the map
        // map.addControl(new L.Control.Search({
        //     layer: searchLayer
        // }));
    </script>
@endpush
