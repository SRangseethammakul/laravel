@extends('frontend.layouts.main')
@section('meta')
<meta name="description" content="Dealer">
<meta name="keywords">
<meta property="og:url" content="{{ url(app()->getLocale().'/Dealer') }}" />
<meta property="og:type" content="dealer" />
<meta property="og:title" content="MG CARS Website"/>
<meta property="og:description" content="Dealer"/>
@endsection
@section('title', 'Dealer')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ url('css/dealer.css') }}?v=1">
<link rel="stylesheet" type="text/css" href="{{ url('css/fa_all.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('css/font-awesome.min.css') }}"/>
@endsection
@section('script')
<script type="text/javascript">
      $('#geography-select').change(function() {
        $('#dealer-detail').css('display', 'none');
        $('#search-keyword').val('');
        $('#dealers-list').html('');
        var geography_id = $(this).val();
        $.ajax({
            url: 'Dealer/getDealerByGeography',
            method: 'GET',
            data: {
                id: geography_id
            },
            beforeSend: function(){
                $("#loader").show();
            },
            success: function (response) {
                if (response.status == 1) {
                    map = new google.maps.Map(document.getElementById("map"));
                    $('#dealers-list').html('');

                    if (response.data.length > 0) {
                        var count = response.data.length;
                        var bounds = new google.maps.LatLngBounds();
                        $.each(response.data, function(index, item) {
                            var  open_time = (item.open_time).substring(0, 5);
                            var close_time = (item.close_time).substring(0, 5);
                            var lat = parseFloat(item.dealer_latitude);
                            var lng = parseFloat(item.dealer_longitude);
                            var latlng = new google.maps.LatLng(lat,lng);
                            var marker = new google.maps.Marker({
                                    position: latlng,
                                    map: map
                            });

                            bounds.extend(marker.position);

                         function getDistance() {
                            var dist = 0;
                            var infoWindow = new google.maps.InfoWindow;
                             if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition(function(position) {
                                var latLngA = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
                                var latLngB = new google.maps.LatLng(item.dealer_latitude, item.dealer_longitude);
                                var currentLocation = {
                                curr_lat: position.coords.latitude,
                                curr_lng: position.coords.longitude
                                };
                                var distances = google.maps.geometry.spherical.computeDistanceBetween(latLngA, latLngB);
                                dist_km = parseFloat(distances/1000).toFixed(2);

                                    var result =
                                    `<a class="select-dealer" name="` + item.lang[0]['dealer_name'] + `" data-lat="` + item.dealer_latitude + `" data-lng="` + item.dealer_longitude + `" data-time="` + open_time + ' - ' + close_time + `" data-address="` + item.lang[0]['dealer_address'] + `" data-phone="` + item.lang[0]['dealer_phone'] + `" data-email="` + item.lang[0]['dealer_email'] + `" data-distance="` + dist_km + `" data-toggle="modal">
                                        <div class="row result-wrap no-gutters--">
                                            <div class="col-10">
                                                <p> ` + item.lang[0]['dealer_name'] + `</p>
                                            </div>
                                        </div>
                                    </a>`;
                                    $('#dealers-list').append(result);

                            google.maps.event.addListener(marker, 'click', function() {

                                var dealer_name = item.lang[0]['dealer_name'];
                                var dealer_time = open_time + ' - ' + close_time;
                                var dealer_address = item.lang[0]['dealer_address'];
                                var dealer_phone = item.lang[0]['dealer_phone']
                                var dealer_email = item.lang[0]['dealer_email'];

                                var today = new Date();
                                var open = new Date();
                                var close = new Date();
                                var open_hour = dealer_time.substring(0,2);
                                var open_min = dealer_time.substring(3,5);
                                var close_hour = dealer_time.substring(8,10);
                                var close_min = dealer_time.substring(11);
                                open.setHours(open_hour,open_min,0);
                                close.setHours(close_hour,close_min,0);

                                $('#testdrive-preferred-dealer').val(dealer_name);
                                $('.dealer-name').text(dealer_name);
                                $('.dealer-time').text(dealer_time);
                                $('.dealer-address').text(dealer_address);
                                $('.dealer-phone').text(dealer_phone);
                                // $('.dealer-tomap').html('<a href="http://google.com/maps/search/?api=1&query='+lat+','+lng+'"><img src="../images/tomap_icon.png" alt=""><br>View on Google Map</a>');
                                                            // 'https://maps.google.com/?saddr="+currentLocation.lat+","+currentLocation.lng+"&daddr="+locations[i]['lat']+","+locations[i]['lng']+"'

                                $('.dealer-tomap').html('<a href="https://maps.google.com/?saddr='+currentLocation.curr_lat+','+currentLocation.curr_lng+'&daddr='+lat+','+lng+'"><img src="../images/tomap_icon.png" alt=""><br>View on Google Map</a>');
                                $('#dealer-detail').css('display', 'block');

                                $('.dealer-tomap a').on('click', function(e) {
                                    e.preventDefault();
                                    if(today < open || today > close)
                                    {
                                    $('#service-closed-modal').modal('show');
                                        $("#service-closed-modal").appendTo("body");
                                    $('button#yes').on('click', function() {
                                        // window.open("http://google.com/maps/search/?api=1&query="+lat+","+lng);
                                        window.open("https://maps.google.com/?saddr="+currentLocation.curr_lat+","+currentLocation.curr_lng+"&daddr="+lat+","+lng);
                                    });
                                    }
                                    else {
                                        // window.open("http://google.com/maps/search/?api=1&query="+lat+","+lng);
                                        window.open("https://maps.google.com/?saddr="+currentLocation.curr_lat+","+currentLocation.curr_lng+"&daddr="+lat+","+lng);
                                    }
                                });
                                $('#dealer-detail').css('display', 'block');
                            });

                                    count = count - 1;
                                    if(count <= 0){
                                        var dealerList = document.querySelectorAll('#dealers-list a');
                                        var locationArray = Array.prototype.slice.call(dealerList, 0);

                                        locationArray.sort(function(a,b){
                                        var distA  = a.getAttribute('data-distance');
                                        var distB  = b.getAttribute('data-distance');
                                        return distA - distB;
                                        });

                                        $('#dealers-list').empty();
                                        locationArray.forEach(function(el) {
                                            $('#dealers-list').append(el);
                                        });
                                        $('.dealer-count').text(response.data.length);
                                    }

                               });
                            }
                            else{
                                handleLocationError(false, infoWindow, map.getCenter());
                            }
                           }
                           getDistance();

                        });
                        map.fitBounds(bounds);
                        $('.no-result').attr('style','display: none !important');
                    } else {

                        $('.dealer-count').text(0);
                        $('.no-result').attr('style','display: block !important');
                    }
                }
            },
            complete:function(data){
                $("#loader").hide();
            }
        });
    });

    $('.around-location').on('click', function() {
        $('#dealer-detail').css('display', 'none');
        $('#geography-select').val('');
        $('#search-keyword').val('');
        allLocation(1);
    });

    $('.reset').on('click', function() {
        $('#dealer-detail').css('display', 'none');
        $('#geography-select').val('');
        $('#search-keyword').val('');
        allLocation(0);
    });

    $('#search-keyword').on('mousedown', function() {
        $('#geography-select').val('');
    });

    $('#search-dealer').on('click', function() {
        $('#dealer-detail').css('display', 'none');
        $('#dealers-list').html('');
        var geography_id = $('#geography-select').val();
        var search_keyword = $('#search-keyword').val();
        $.ajax({
            url: 'Dealer/searchDealer',
            method: 'GET',
            data: {
                keyword: search_keyword,
                id: geography_id
            },
            beforeSend: function(){
                $("#loader").show();
            },
            success: function (response) {
                if (response.status == 1) {
                     map = new google.maps.Map(document.getElementById("map"));
                    $('#dealers-list').html('');

                    if (response.data.length > 0) {
                        var bounds = new google.maps.LatLngBounds();
                        var count = response.data.length;
                        $.each(response.data, function(index, item) {
                            var  open_time = (item.open_time).substring(0, 5);
                            var close_time = (item.close_time).substring(0, 5);

                            var lat = parseFloat(item.dealer_latitude);
                            var lng = parseFloat(item.dealer_longitude);
                            var latlng = new google.maps.LatLng(lat,lng);

                            var marker = new google.maps.Marker({
                                    position: latlng,
                                    map: map
                            });
                            bounds.extend(marker.position);
                            function getDistance() {
                            var dist = 0;
                            var infoWindow = new google.maps.InfoWindow;
                            if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition(function(position) {
                                var latLngA = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
                                var latLngB = new google.maps.LatLng(item.dealer_latitude, item.dealer_longitude);
                                var currentLocation = {
                                curr_lat: position.coords.latitude,
                                curr_lng: position.coords.longitude
                                };
                                var distances = google.maps.geometry.spherical.computeDistanceBetween(latLngA, latLngB);
                                dist_km = parseFloat(distances/1000).toFixed(2);

                                    var result =
                                    `<a class="select-dealer" name="` + item.lang[0]['dealer_name'] + `" data-lat="` + item.dealer_latitude + `" data-lng="` + item.dealer_longitude + `" data-time="` + open_time + ' - ' + close_time + `" data-address="` + item.lang[0]['dealer_address'] + `" data-phone="` + item.lang[0]['dealer_phone'] + `" data-email="` + item.lang[0]['dealer_email'] + `" data-distance="` + dist_km + `" data-toggle="modal">
                                     <div class="row result-wrap no-gutters--">
                                       <div class="col-10">
                                        <p> ` + item.lang[0]['dealer_name'] + `</p>
                                         </div>
                                        </div>
                                      </a>`;
                                    $('#dealers-list').append(result);
                           google.maps.event.addListener(marker, 'click', function() {

                            var dealer_name = item.lang[0]['dealer_name'];
                            var dealer_time = open_time + ' - ' + close_time;
                            var dealer_address = item.lang[0]['dealer_address'];
                            var dealer_phone = item.lang[0]['dealer_phone']
                            var dealer_email = item.lang[0]['dealer_email'];

                            var today = new Date();
                            var open = new Date();
                            var close = new Date();
                            var open_hour = dealer_time.substring(0,2);
                            var open_min = dealer_time.substring(3,5);
                            var close_hour = dealer_time.substring(8,10);
                            var close_min = dealer_time.substring(11);
                            open.setHours(open_hour,open_min,0);
                            close.setHours(close_hour,close_min,0);

                            $('#testdrive-preferred-dealer').val(dealer_name);
                            $('.dealer-name').text(dealer_name);
                            $('.dealer-time').text(dealer_time);
                            $('.dealer-address').text(dealer_address);
                            $('.dealer-phone').text(dealer_phone);
                            // $('.dealer-tomap').html('<a href="http://google.com/maps/search/?api=1&query='+lat+','+lng+'"><img src="../images/tomap_icon.png" alt=""><br>View on Google Map</a>');
                            $('.dealer-tomap').html('<a href="https://maps.google.com/?saddr='+currentLocation.curr_lat+','+currentLocation.curr_lng+'&daddr='+lat+','+lng+'"><img src="../images/tomap_icon.png" alt=""><br>View on Google Map</a>');
                            $('#dealer-detail').css('display', 'block');

                            $('.dealer-tomap a').on('click', function(e) {
                                e.preventDefault();
                                if(today < open || today > close)
                                {
                                $('#service-closed-modal').modal('show');
                                    $("#service-closed-modal").appendTo("body");
                                $('button#yes').on('click', function() {
                                    // window.open("http://google.com/maps/search/?api=1&query="+lat+","+lng);
                                    window.open("https://maps.google.com/?saddr="+currentLocation.curr_lat+","+currentLocation.curr_lng+"&daddr="+lat+","+lng);
                                });
                                }
                                else {
                                    // window.open("http://google.com/maps/search/?api=1&query="+lat+","+lng);
                                    window.open("https://maps.google.com/?saddr="+currentLocation.curr_lat+","+currentLocation.curr_lng+"&daddr="+lat+","+lng);
                                }
                            });
                            $('#dealer-detail').css('display', 'block');
                        });
                                    count = count - 1;
                                    if(count <= 0){
                                        var dealerList = document.querySelectorAll('#dealers-list a');
                                        var locationArray = Array.prototype.slice.call(dealerList, 0);

                                        locationArray.sort(function(a,b){
                                        var distA  = a.getAttribute('data-distance');
                                        var distB  = b.getAttribute('data-distance');
                                        return distA - distB;
                                        });

                                        $('#dealers-list').empty();
                                        locationArray.forEach(function(el) {
                                            $('#dealers-list').append(el);
                                        });
                                        $('.dealer-count').text(response.data.length);
                                    }
                                }
                              );
                            }
                            else{
                                handleLocationError(false, infoWindow, map.getCenter());
                            }
                           }
                           getDistance();
                        });
                        map.fitBounds(bounds);

                        $('.no-result').attr('style','display: none !important');
                    } else {

                        $('.dealer-count').text(0);
                        $('.no-result').attr('style','display: block !important');
                    }
                } else {
                }
            },
            complete:function(data){
                $("#loader").hide();
            }
        });
    });

    function allLocation(isAroundLocation)
    {
        $.ajax({
            url: 'Dealer/getDealerByGeography',
            method: 'GET',
            data: {
                id: 999
            },
            beforeSend: function(){
                $("#loader").show();
            },
            success: function (response) {
                initMap();
                if (response.status == 1) {
                    $('#dealers-list').html('');

                    if (response.data.length > 0) {
                      var count = response.data.length;
                        $.each(response.data, function(index, item) {
                            var  open_time = (item.open_time).substring(0, 5);
                            var close_time = (item.close_time).substring(0, 5);
                            var lat = parseFloat(item.dealer_latitude);
                            var lng = parseFloat(item.dealer_longitude);
                            var latlng = new google.maps.LatLng(lat,lng);
                            var marker = new google.maps.Marker({
                                    position: latlng,
                                    map: map
                            });

                         function getDistance() {
                            var dist = 0;
                            var infoWindow = new google.maps.InfoWindow;

                            if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition(function(position) {
                                var pos = {
                                        lat: position.coords.latitude,
                                        lng: position.coords.longitude
                                };
                                var latLngA = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
                                var latLngB = new google.maps.LatLng(item.dealer_latitude, item.dealer_longitude);
                                var distances = google.maps.geometry.spherical.computeDistanceBetween(latLngA, latLngB);
                                dist_km = parseFloat(distances/1000).toFixed(2);

                                    var result =
                                    `<a class="select-dealer" name="` + item.lang[0]['dealer_name'] + `" data-lat="` + item.dealer_latitude + `" data-lng="` + item.dealer_longitude + `" data-time="` + open_time + ' - ' + close_time + `" data-address="` + item.lang[0]['dealer_address'] + `" data-phone="` + item.lang[0]['dealer_phone'] + `" data-email="` + item.lang[0]['dealer_email'] + `" data-distance="` + dist_km + `" data-toggle="modal">
                                        <div class="row result-wrap no-gutters--">
                                            <div class="col-10">
                                                <p> ` + item.lang[0]['dealer_name'] + `</p>
                                            </div>
                                        </div>
                                    </a>`;
                                    $('#dealers-list').append(result);

                            google.maps.event.addListener(marker, 'click', function() {

                            var dealer_name = item.lang[0]['dealer_name'];
                            var dealer_time = open_time + ' - ' + close_time;
                            var dealer_address = item.lang[0]['dealer_address'];
                            var dealer_phone = item.lang[0]['dealer_phone']
                            var dealer_email = item.lang[0]['dealer_email'];

                            var today = new Date();
                            var open = new Date();
                            var close = new Date();
                            var open_hour = dealer_time.substring(0,2);
                            var open_min = dealer_time.substring(3,5);
                            var close_hour = dealer_time.substring(8,10);
                            var close_min = dealer_time.substring(11);
                            open.setHours(open_hour,open_min,0);
                            close.setHours(close_hour,close_min,0);

                            $('#testdrive-preferred-dealer').val(dealer_name);
                            $('.dealer-name').text(dealer_name);
                            $('.dealer-time').text(dealer_time);
                            $('.dealer-address').text(dealer_address);
                            $('.dealer-phone').text(dealer_phone);
                            // $('.dealer-tomap').html('<a href="http://google.com/maps/search/?api=1&query='+lat+','+lng+'"><img src="../images/tomap_icon.png" alt=""><br>View on Google Map</a>');
                            $('.dealer-tomap').html('<a href="https://maps.google.com/?saddr='+pos.lat+','+pos.lng+'&daddr='+lat+','+lng+'"><img src="../images/tomap_icon.png" alt=""><br>View on Google Map</a>');
                            $('#dealer-detail').css('display', 'block');

                            $('.dealer-tomap a').on('click', function(e) {
                                e.preventDefault();
                                if(today < open || today > close)
                                {
                                $('#service-closed-modal').modal('show');
                                    $("#service-closed-modal").appendTo("body");
                                $('button#yes').on('click', function() {
                                    // window.open("http://google.com/maps/search/?api=1&query="+lat+","+lng);
                                    window.open("https://maps.google.com/?saddr="+pos.lat+","+pos.lng+"&daddr="+lat+","+lng);

                                });
                                }
                                else {
                                    // window.open("http://google.com/maps/search/?api=1&query="+lat+","+lng);
                                    window.open("https://maps.google.com/?saddr="+pos.lat+","+pos.lng+"&daddr="+lat+","+lng);

                                }
                            });
                            $('#dealer-detail').css('display', 'block');
                        });
                                    count = count - 1;
                                    if(count <= 0){

                                        var dealerList = document.querySelectorAll('#dealers-list a');
                                        var locationArray = Array.prototype.slice.call(dealerList, 0);

                                        locationArray.sort(function(a,b){
                                        var distA  = a.getAttribute('data-distance');
                                        var distB  = b.getAttribute('data-distance');
                                        return distA - distB;
                                        });

                                        $('#dealers-list').empty();

                                        if(isAroundLocation == 1) {
                                         var bounds = new google.maps.LatLngBounds();

                                        locationArray.slice(0, 5).forEach(function(el) {
                                            $('#dealers-list').append(el);
                                        });

                                          map = new google.maps.Map(document.getElementById("map"));

                                           $('#dealers-list').children('a').each(function () {

                                            var dealer_name = $(this).attr('name');
                                            var dealer_time = $(this).attr('data-time');
                                            var dealer_address = $(this).attr('data-address');
                                            var dealer_phone = $(this).attr('data-phone');
                                            var dealer_email = $(this).attr('data-email');
                                            var lat = parseFloat($(this).attr('data-lat'));
                                            var lng = parseFloat($(this).attr('data-lng'));
                                            var latlng = new google.maps.LatLng(lat,lng);
                                            var marker = new google.maps.Marker({
                                                    position: latlng,
                                                    map: map
                                            });

                                            bounds.extend(marker.position);

                                            google.maps.event.addListener(marker, 'click', function() {

                                            var today = new Date();
                                            var open = new Date();
                                            var close = new Date();
                                            var open_hour = dealer_time.substring(0,2);
                                            var open_min = dealer_time.substring(3,5);
                                            var close_hour = dealer_time.substring(8,10);
                                            var close_min = dealer_time.substring(11);
                                            open.setHours(open_hour,open_min,0);
                                            close.setHours(close_hour,close_min,0);

                                            $('#testdrive-preferred-dealer').val(dealer_name);
                                            $('.dealer-name').text(dealer_name);
                                            $('.dealer-time').text(dealer_time);
                                            $('.dealer-address').text(dealer_address);
                                            $('.dealer-phone').text(dealer_phone);
                                            $('.dealer-tomap').html('<a href="https://maps.google.com/?saddr='+pos.lat+','+pos.lng+'&daddr='+lat+','+lng+'"><img src="../images/tomap_icon.png" alt=""><br>View on Google Map</a>');
                                            // $('.dealer-tomap').html('<a href="http://google.com/maps/search/?api=1&query='+lat+','+lng+'"><img src="../images/tomap_icon.png" alt=""><br>View on Google Map</a>');

                                            $('.dealer-tomap a').on('click', function(e) {
                                                e.preventDefault();
                                                if(today < open || today > close)
                                                {
                                                $('#service-closed-modal').modal('show');
                                                    $("#service-closed-modal").appendTo("body");
                                                $('button#yes').on('click', function() {
                                                    // window.open("http://google.com/maps/search/?api=1&query="+lat+","+lng);
                                                    window.open("https://maps.google.com/?saddr="+pos.lat+","+pos.lng+"&daddr="+lat+","+lng);
                                                });
                                                }
                                                else {
                                                    // window.open("http://google.com/maps/search/?api=1&query="+lat+","+lng);
                                                    window.open("https://maps.google.com/?saddr="+pos.lat+","+pos.lng+"&daddr="+lat+","+lng);
                                                }
                                            });
                                            $('#dealer-detail').css('display', 'block');
                                          });//close onclick
                                        });
                                        var current= new google.maps.LatLng(pos);
                                        infoWindow.setPosition(pos);
                                        infoWindow.setContent('You are here');
                                        infoWindow.open(map);
                                        bounds.extend(current)
                                        map.fitBounds(bounds);

                                        var list = $('#dealers-list').children().length;
                                        $('.dealer-count').text(list);
                                        }
                                        else
                                        {
                                            locationArray.forEach(function(el) {
                                            $('#dealers-list').append(el);
                                        });
                                        $('.dealer-count').text(response.data.length);
                                        }
                                    }
                                }
                              );
                            }
                            else{
                                handleLocationError(false, infoWindow, map.getCenter());
                            }
                           }
                           getDistance();
                        });

                        $('.no-result').attr('style','display: none !important');
                    } else {

                        $('.dealer-count').text(0);
                        $('.no-result').attr('style','display: block !important');
                    }
                }
            },
            complete:function(data){
                $("#loader").hide();
            }
        });
    }

    $(function() {
       allLocation(0);
    });

    $('.dealer-detail-close').on('click', function() {
        $('#dealer-detail').css('display', 'none');
    });

    var map, infoWindow;

    function distance(lat1, lon1, lat2, lon2, unit) {
        if ((lat1 == lat2) && (lon1 == lon2)) {
            return 0;
        }
        else {
            var radlat1 = Math.PI * lat1/180;
            var radlat2 = Math.PI * lat2/180;
            var theta = lon1-lon2;
            var radtheta = Math.PI * theta/180;
            var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
            if (dist > 1) {
                dist = 1;
            }
            dist = Math.acos(dist);
            dist = dist * 180/Math.PI;
            dist = dist * 60 * 1.1515;
            if (unit=="K") { dist = dist * 1.609344 }
            if (unit=="N") { dist = dist * 0.8684 }
            return dist;
        }
    }

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 13.736717, lng: 100.523186},
            zoom: 6
        });

        infoWindow = new google.maps.InfoWindow;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                infoWindow.setPosition(pos);
                infoWindow.setContent('You are here');
                infoWindow.open(map);
                map.setCenter(pos);
            }, function() {
                handleLocationError(true, infoWindow, map.getCenter());
            });
        } else {
            handleLocationError(false, infoWindow, map.getCenter());
        }
    }

    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ? 'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);
    }

    var locations = @json($locations);

    $(document).on('click', '.select-dealer', function(e) {
        $(this).addClass("active");

        if ($(window).width() < 992) {
            $('html, body').animate({
                scrollTop: $('#map-scroll').offset().top - $('header').height()
            });
        }

        $('.result a').not(this).removeClass("active");
        var lat = parseFloat($(this).attr('data-lat'));
        var lng = parseFloat($(this).attr('data-lng'));

        // var pos = {
        //     lat: position.coords.latitude,
        //     lng: position.coords.longitude
        // };
        var dealer_name = $(this).attr('name');
        var dealer_time = $(this).attr('data-time');
        var dealer_address = $(this).attr('data-address');
        var dealer_phone = $(this).attr('data-phone');
        var dealer_email = $(this).attr('data-email');

        var today = new Date();
        var open = new Date();
        var close = new Date();
        var open_hour = dealer_time.substring(0,2);
        var open_min = dealer_time.substring(3,5);
        var close_hour = dealer_time.substring(8,10);
        var close_min = dealer_time.substring(11);
        open.setHours(open_hour,open_min,0);
        close.setHours(close_hour,close_min,0);

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

        $('#testdrive-preferred-dealer').val(dealer_name);
        $('.dealer-name').text(dealer_name);
        $('.dealer-time').text(dealer_time);
        $('.dealer-address').text(dealer_address);
        $('.dealer-phone').text(dealer_phone);
        $('.dealer-tomap').html('<a href="https://maps.google.com/?saddr='+pos.lat+','+pos.lng+'&daddr='+lat+','+lng+'"><img src="../images/tomap_icon.png" alt=""><br>View on Google Map</a>');
        // $('.dealer-tomap').html('<a href="http://google.com/maps/search/?api=1&query='+lat+','+lng+'"><img src="../images/tomap_icon.png" alt=""><br>View on Google Map</a>');
        $('#dealer-detail').css('display', 'block');

        $('.dealer-tomap a').on('click', function(e) {
            e.preventDefault();
            if(today < open || today > close)
             {
               $('#service-closed-modal').modal('show');
                $("#service-closed-modal").appendTo("body");
               $('button#yes').on('click', function() {
                // window.open("http://google.com/maps/search/?api=1&query="+lat+","+lng);
                window.open("https://maps.google.com/?saddr="+pos.lat+","+pos.lng+"&daddr="+lat+","+lng);
               });
             }
             else {
                // window.open("http://google.com/maps/search/?api=1&query="+lat+","+lng);
                window.open("https://maps.google.com/?saddr="+pos.lat+","+pos.lng+"&daddr="+lat+","+lng);
             }
        });

        });
    }

        map.setCenter({
            lat: lat,
            lng: lng,
        });

         map.setZoom(16);
    });

</script>
{{-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDA2dtJsLmRx3jdrx1HtNnnMBWLNljolnY&libraries=geometry"></script> --}}
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDA2dtJsLmRx3jdrx1HtNnnMBWLNljolnY&callback=initMap&libraries=geometry"></script>
@endsection
@section('content')
<main>
    <div class="container">
        <div class="row">
            <div class="col-12 pt-3 pt-md-5 pb-5">
                <h1 class="text-center">{!! trans('dealer.dealer_header') !!}</h1>
                <h2 class="text-center">{!! trans('dealer.dealer_sub_header') !!}</h2>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <form>
                            <div class="form-group">
                                <select class="form-control" id="geography-select">
                                    <option value="">{!! trans('dealer.choose_geography') !!}</option>
                                    @foreach ($geographies as $geography)
                                    <option value="{{ $geography->id }}">{{ App::getLocale() == 'th' ? $geography->name : $geography->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <input id="search-keyword" type="text" class="form-control" placeholder="{!! trans('dealer.search_placeholder') !!}" name="search_keyword">
                            </div>
                            <div class="form-group row">
                                <div class="col-md-8 d-md-flex align-items-center">
                                    <button class="around-location" type="button">{!! trans('dealer.around_location') !!}</button>
                                </div>
                                <div class="col-md-4">
                                    <button id="search-dealer" class="btn-mg-black" type="button">{!! trans('dealer.search') !!}</button>
                                </div>
                                <div class="col-md-4">
                                    <button id="reset" class="reset" type="button">{!! trans('dealer.reset') !!}</button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-12 col-md-8">
                                <h3>{!! trans('dealer.branch_nationwide') !!}</h3>
                            </div>
                            <div class="col-12 col-md-4 text-right">
                                <h4 class="total">({!! trans('dealer.total') !!} <span class="dealer-count">0</span>)</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="no-result d-none">
                                    Dealer or Shop not found !! <br>Please go to nearby provinces.
                                </div>
                            </div>
                            <div class="col-12">
                                <div id='loader' style='display: none;text-align: center;'>
                                    <img src="{{ url('images/loading.gif') }}" width='210px' height='150px'>
                                </div>
                                <div id="dealers-list" class="result scrollbar-inner">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6" id="dealer-map">
                        <div id="dealer-detail" style="height: auto;">
                            <div class="dealer-wrap">
                                <div class="dealer-header">
                                    <span class="dealer-detail-close"><i class="fa fa-times-circle"></i></span>
                                </div>
                                <div class="dealer-name"></div>
                                <div class="dealer-time" style="background-image: url(../images/b-clock-icon.png);"></div>
                                <div class="dealer-address" style="background-image: url(../images/b-find-a-showroom-icon.png);"></div>
                                <div class="dealer-phone" style="background-image: url(../images/b-call-icon-3.png);"></div>
                                <div class="dealer-tomap"></div>
                            </div>
                        </div>
                        <div id="map-scroll"></div>
                        <div id="map"></div>
                    </div>
                </div>
                <div class="social d-none d-xl-flex align-items-center">
                        <div class="text-vertical">
                            <span class="social-icon">
                                <a href="https://www.youtube.com/channel/UCWBomBcUxilPt-H3mqjpdQA" target="_blank"><img src="{{ url('images/youtube-icon-left.png') }}" alt="" class="grey"><img src="{{ url('images/youtube-icon-left-color.png') }}" alt="" class="color"></a>
                                <a href="https://www.instagram.com/mgthailand/" target="_blank"><img src="{{ url('images/instagram-icon-left.png') }}" alt="" class="grey"><img src="{{ url('images/instagram-icon-left-color.png') }}" alt="" class="color"></a>
                                <a href="https://www.facebook.com/MGcarsThailand/" target="_blank"><img src="{{ url('images/facebook-icon-left.png') }}" alt="" class="grey"><img src="{{ url('images/facebook-icon-left-color.png') }}" alt="" class="color"></a>
                                <a href="https://lin.ee/cpQc5GA" target="_blank"><img src="{{ url('images/line-icon-left.png') }}" alt="" class="grey"><img src="{{ url('images/line-icon-left-color.png') }}" alt="" class="color"></a>
                            </span>
                            <span>{!! trans('main.mg_social_media') !!}</span>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</main>
<!-- Modal -->
<div class="modal fade" id="service-closed-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body text-center">
                <img src="{{ url('images/dealer/service-closed.png') }}" alt="" class="img-fluid">
                @if(App::getLocale() == 'th')
                <h3> ขออภัยโชว์รูม/ศูนย์บริการ กำลังปิดทำการ </h3> คุณต้องการเดินทางต่อไปหรือไม่ <br><br>
                <button type="button" id="no" class="btn btn-close" data-dismiss="modal">ไม่</button>
                <button type="button" id="yes" class="btn btn-close" data-dismiss="modal">ใช่</button>
                @else
                <h4>Showroom/Service Centre is closed now!!</h4> Do you want to proceed? <br><br>
                <button type="button" id="no" class="btn btn-close" data-dismiss="modal">NO</button>
                <button type="button" id="yes" class="btn btn-close" data-dismiss="modal">YES</button>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
