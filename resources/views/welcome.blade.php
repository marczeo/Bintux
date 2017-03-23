@extends('layouts.app')

@section('scriptsTop')
<style type="text/css">
#wrapper {
    min-height: 100%;
    height: 100%;
    width: 100%;
    position: absolute;
    top: 0px;
    left: 0;
    display: inline-block;
}
#main-wrapper {
    height: 100%;
    overflow-y: auto;
    padding: 50px 0 0px 0;
}
#main {
    position: relative;
    height: 100%;
    overflow-y: auto;
}
#sidebar-wrapper {
    height: 100%;
    padding: 54px 0 0px 0;
    position: fixed;
    background-color: #222;
}
#sidebar {
    position: relative;
    height: 100%;
    overflow:hidden;
}
#sidebar .list-group-item {
        border-radius: 0;
        border-left: 0;
        border-right: 0;
        border-top: 0;
}
@media (max-width: 992px) {
    body {
        padding-top: 0px;
    }
}
@media (min-width: 992px) {
    #main-wrapper {
        float:right;
    }
}
@media (max-width: 992px) {
    #main-wrapper {
        padding-top: 0px;
    }
}
@media (max-width: 992px) {
    #sidebar-wrapper {
        position: static;
        height:auto;
        max-height: 300px;
  		border-right:0;
	}
}
.footer {
    background-color:#ffffff;
	bottom:0;
  	position:fixed;
    padding:10px;
}

</style>
@endsection

@section('content')
<!--<div class="jumbotron-fluid">
    <div id="map" style="height: 800px;"></div>
</div>-->
<!---------------->
<div id="wrapper">
  <div id="sidebar-wrapper" class="col-md-2">
            <div id="sidebar">
                <ul class="nav list-group">
                    <li>
                    	<form class="col-xs-12" role="search" method="POST" action="route/search" onsubmit="return submit_form(this);">
                    	{{ csrf_field() }}
                        {{ method_field('POST') }}
                    		<div class="input-group">
                    		<input type="text" class="form-control" placeholder="Origin" name="origin">
                    			<div class="input-group-btn">
                    				<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                    			</div>
                    		</div>
                    	</form>
                    </li>
                    <li>
                        <form class="col-xs-12" role="search">
                    		<div class="input-group">
                    			<input type="text" class="form-control" placeholder="Destination" name="destination">
                    			<div class="input-group-btn">
                    				<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                    			</div>
                    		</div>
                    	</form>
                    </li>
                </ul>
            </div>
        </div>
        <div id="main-wrapper" class="col-md-10 pull-right">
            <div id="main">
              <div id="map" style="height: 100%;"></div>
            </div>
        </div>
</div>
@endsection
@section('scriptsBottom')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCk3aVE_atNGMx06dHKbmU6RMCgAOMMWEQ&signed_in=true&libraries=geometry"></script>
    <script src="/js/map_index.js"></script>
    <script type="text/javascript">
    	/**
 * Submit form
 * @param {Object} form
 * @return {boolean}
 */
function submit_form(form) {
	//var serializeArray = $(form).serialize();
	var serializeArray = new FormData(form);

	$.ajax({
		url: form.action,
		type: form.method,
		data : serializeArray,
		cache:false,
		contentType: false,
		processData: false,
		success: function(data){
			for (i=0; i<polis.length; i++) 
{                           
  polis[i].setMap(null); //or line[i].setVisible(false);
}
			$parseData=JSON.parse(data);
      $.each($parseData.data, function(i, item) {
        var instring = google.maps.geometry.encoding.decodePath(item.encodepath);
        var routeCoordinates = Array();
        var points = instring;

        for (i = 0; i < points.length; i++) {
          var p = new google.maps.LatLng(points[i][0], points[i][1]);
          routeCoordinates.push(p);
        }
        var lineSymbol = {
          path: google.maps.SymbolPath.FORWARD_OPEN_ARROW,
          scale: 2.2,
          strokeColor: "#FFF",
          strokeOpacity: 1
        };

        var routePath = new google.maps.Polyline({
          path: points,
          interpolate: true,
          icons: [{
            icon: lineSymbol,
            offset: '50%',
            repeat: '240px'
          }],
          strokeColor: item.color,
          strokeOpacity: 0.7,
          strokeWeight: 8
        });
        google.maps.event.addListener(routePath, 'mouseover', function(event) {
          infowindow.open(map);
          infowindow.setContent(item.code);
          infowindow.setPosition(event.latLng);
        });
        google.maps.event.addListener(routePath, 'mouseout', function() {
            infowindow.close();
        });
        routePath.setMap(map);
        polis.push(routePath);

      });
		},
		error: function (response) {
			console.log("fail");
			console.log(response);
		}
	});
	return false;
}
    </script>
@endsection