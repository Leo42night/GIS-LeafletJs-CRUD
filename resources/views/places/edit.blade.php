@extends('layouts.app')

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <style>
        #mapid {
            min-height: 500px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <a href="{{ route('places.index') }}" class="text-center">
                <button type="submit" class="btn btn-primary btn-sm m-2">
                <i class='fas fa-angle-double-left' style='font-size:;color:'></i>
                Back</button>
            </a>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div id="mapid"></div>
                        <img src="{{ $place->getImageAsset() }}" alt="{{ $place->getImageAsset() }}"
                            class="object-fit-contain mt-2">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    @include('notify::components.notify')
                    <div class="card-header">Update Place</div>
                    <div class="card-body">
                        <form action="{{ route('places.update', $place) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <div class="form-row mb-2">
                                    <div class="col">
                                        <label for="">Place Name</label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ $place->name }}">
                                        @error('name')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label for="">Upload image</label>
                                        <input type="file" name="image"
                                            class="form-control @error('image') is-invalid @enderror"
                                            placeholder="file image">
                                        <small><strong>**let empty if there is no image to upload</strong></small>
                                        @error('image')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row row mb-2">
                                    <div class="col-6">
                                        <label for="">Longitude</label>
                                        <input type="text" name="longitude" id="longitude" readonly
                                            class="form-control @error('longitude') is-invalid @enderror"
                                            value="{{ $place->longitude }}">
                                        @error('longitude')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="">Latitude</label>
                                        <input type="text" name="latitude" id="latitude" readonly
                                            class="form-control @error('latitude') is-invalid @enderror"
                                            value="{{ $place->latitude }}">
                                        @error('latitude')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <label for="">Description</label>
                                <textarea name="description" placeholder="Description here..."
                                    class="form-control @error('description') is-invalid @enderror" rows="3">{{ $place->description }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="">Address</label>
                                <textarea name="address" placeholder="address here..." class="form-control @error('address') is-invalid @enderror"
                                    rows="3">{{ $place->address }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                    </div>
                </div>
                <div class="form-group float-right mt-4">
                    <button type="submit" class="btn btn-primary btn-block">Update Place</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection

@push('scripts')
    <!-- Leaflet JavaScript -->
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>

    <script>
        var mapCenter = [
            {{ $place->latitude }},
            {{ $place->longitude }},
        ];
        var map = L.map('mapid').setView(mapCenter, {{ config('leafletsetup.zoom_level') }});
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker = L.marker(mapCenter).addTo(map);

        function updateMarker(lat, lng) {
            marker
                .setLatLng([lat, lng])
                .bindPopup("Your location :" + marker.getLatLng().toString())
                .openPopup();
            return false;
        };

        map.on('click', function(e) {
            let latitude = e.latlng.lat.toString().substring(0, 15);
            let longitude = e.latlng.lng.toString().substring(0, 15);
            $('#latitude').val(latitude);
            $('#longitude').val(longitude);
            updateMarker(latitude, longitude);
        });

        var updateMarkerByInputs = function() {
            return updateMarker($('#latitude').val(), $('#longitude').val());
        }
        $('#latitude').on('input', updateMarkerByInputs);
        $('#longitude').on('input', updateMarkerByInputs);
    </script>
@endpush
