<script type="text/javascript">
     window.addEventListener('DOMContentLoaded', function () {
    // AUTOSUGGEST PART
    new Autosuggest('location', {
        delay: 500,
        clearButton: true,
        selectFirst: true,
        howManyCharacters: 2,
        // onSearch
        onSearch: (input) => {
        // api
        const api = `https://nominatim.openstreetmap.org/search?format=geojson&limit=5&city=${encodeURI(input)}&accept-language=id`;
        return new Promise((resolve) => {
            fetch(api)
            .then(response => response.json())
            .then(data => {
                resolve(data.features);
                console.dir(data.features);
            })
            .catch(error => {
                console.error(error);
            })
        })
        },
        // nominatim GeoJSON format
        onResults: (matches, input) => {
        const regex = new RegExp(input, 'i');
        return matches.map((element) => {
            return `
            <li class="loupe">
                <p>
                ${element.properties.display_name.replace(regex, (str) => `<b>${str}</b>`)}
                </p>
            </li> `;
        }).join('');
        },
    });
    });
</script>
