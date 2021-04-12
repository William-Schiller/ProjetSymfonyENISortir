let inputCityEvent = document.getElementById("create_trip_city");

window.onload = sendAjaxCity();
inputCityEvent.addEventListener("change", sendAjaxCity);

function sendAjaxCity() {
    let keyword = inputCityEvent.value;
    axios.get(searchAddressURL, {
        params: {
            "keyword": keyword
        }
    })
    .then(function (response) {
        console.log(keyword);
        let result = document.getElementById("create_trip_adress");
        result.innerHTML = response.data;
    });
}