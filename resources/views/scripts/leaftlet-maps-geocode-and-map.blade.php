@if ($user->profile && $user->profile->location)
	
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
	integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
	crossorigin=""/>
	<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>

	<script type="text/javascript">
		var address = '{{$user->profile->location}}';
		const URL = 'https://nominatim.openstreetmap.org/search.php?q='+encodeURI(address)+'&polygon_geojson=1&format=jsonv2';
		const loader = document.getElementById("loader");
		loader.innerHTML = "<p>Loading...";
		fetch(URL)
		.then((response) => response.json())
		.then((data) => loader.innerHTML = MainUnit(data));

		const MainUnit = (data) => {
			const Data = data ;
			if(Data.length > 0){
				let l= Data[Data.length-1];  
				// SHOW LATITUDE AND LONGITUDE
				document.getElementById('latitude').innerHTML += l.lat;
				document.getElementById('longitude').innerHTML += l.lon; 
				loader.remove();
				show_map(l.lat, l.lon);
			}else{
				console.log('Location empty');
				loader.remove();
			}
		}
		
		function show_map (lat,lon) { 
			let clat = lat;
			let clon = lon;
			var map = L.map('map-canvas').setView([clat, clon], 14);

			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				maxZoom: 18,
				id: 'mapbox/streets-v11',
				tileSize: 512,
				zoomOffset: -1,
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(map);
			
			L.marker([clat,clon]).addTo(map)
				.bindPopup('<strong>{{$user->first_name}}</strong> <br />  {{$user->email}}')
				.openPopup();
		}
	</script>

@endif