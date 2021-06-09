
let formMap = $('#form_map');
let viewMap = $('#view_map');

$(document).ready(function () {
    var panel = $('.js_panel');
    $(document).on('click', '.js_panel_toggle', function () {
        if (panel.hasClass('panel-opened')) //if open do close
        {
            panel.removeClass('panel-opened tooltiphide');
            $(this).addClass('glyphicon-option-vertical').removeClass('glyphicon-option-horizontal');
        }
        else //if closed do open
        {
            panel.addClass('panel-opened tooltiphide');
            $(this).removeClass('glyphicon-option-vertical').addClass('glyphicon-option-horizontal');
        }
    });

    $("[data-toggle='tooltip']").tooltip();

    if (formMap.length || viewMap.length) {
        iniMap();
    }

});

/*  Google Maps */
let map;
let bishkekCenter = { lat: 42.87, lng: 74.59 }
let oshCenter = { lat: 40.51506, lng: 72.80826 };
let cities = [bishkekCenter, oshCenter];
let formMarker;
let viewMarker;

let setFormMap = function (mapCenter, change = false) {
    let latField = $('#objects-lat');
    let lngField = $('#objects-lng');
    if (!change && lngField.val().length) {
        mapCenter = { lat: parseFloat(latField.val()), lng: parseFloat(lngField.val()) };
    }

    map = new google.maps.Map(document.getElementById("form_map"), {
        center: mapCenter,
        zoom: 11,
    });
    if (!change && lngField.val().length) {
        formMarker = new google.maps.Marker({
            position: mapCenter,
            draggable: true,
        });
        formMarker.setMap(map);
        formMarker.addListener("dragend", () => {
            let newPos = formMarker.getPosition();
            latField.val(newPos.lat);
            lngField.val(newPos.lng);
        });
    }

    map.addListener("click", (e) => {
        placeMarkerAndPanTo(e.latLng, map);
        latField.val(e.latLng.lat);
        lngField.val(e.latLng.lng);
    });
}

function placeMarkerAndPanTo(latLng, map) {
    if (typeof formMarker !== 'undefined') { formMarker.setMap(null); }
    formMarker = new google.maps.Marker({
        position: latLng,
        draggable: true,
        animation: google.maps.Animation.DROP,
    });
    formMarker.setMap(map);
    map.panTo(latLng);
    formMarker.addListener("dragend", () => {
        let newPos = formMarker.getPosition();
        $('#objects-lat').val(newPos.lat);
        $('#objects-lng').val(newPos.lng);
    });
}

let iniFormMap = function () {
    let currentCity = $('#objects-city').val();
    $('#objects-city').on('change', function () {
        currentCity = $(this).val();
        setFormMap(cities[currentCity], true);
    });
    setFormMap(cities[currentCity]);
}
function iniViewMap() {
    let viewLat = parseFloat(viewMap.attr('data-lat'));
    let viewLng = parseFloat(viewMap.attr('data-lng'));
    let viewCenter = { lat: viewLat, lng: viewLng };
    map = new google.maps.Map(document.getElementById("view_map"), {
        center: viewCenter,
        zoom: 14,
    });
    new google.maps.Marker({
        position: viewCenter,
        map,
    });
}

let iniMap = function () {
    // Create the script tag, set the appropriate attributes
    var script = document.createElement('script');
    let map_api = 'AIzaSyB2k_D1CJhXxSm-4iKbz00RKxSYl9X50ME';
    script.src = 'https://maps.googleapis.com/maps/api/js?key=' + map_api + '&callback=initMap';
    script.async = true;

    // Attach your callback function to the `window` object
    window.initMap = function () {
        // JS API is loaded and available
        if (formMap.length) {
            iniFormMap();
        }
        if (viewMap.length) {
            iniViewMap();
        }
    };

    // Append the 'script' element to 'head'
    document.head.appendChild(script);
}

function imgSorted(e, params) {
    if (params.oldIndex !== params.newIndex && params.stack.length) {
        //console.log(params);
        $.ajax({
            type: 'POST',
            data: { model_id: params.stack[0].model_id, model_name: params.stack[0].model_name, stack: params.stack, _csrf: yii.getCsrfToken() },
            url: '/site/img-sort',
            //beforeSend: function () {},
            success: function (data) {
                //console.log(data);
            }
        });
    }
}