window.onload = sendAjaxCity();

function sendAjaxCity() {
    axios.get(searchAddressURL, {
        params: {
            "userId": userId,
            "tripId": tripId
        }
    })
    .then(function (response) {
        let result = document.getElementById(idUploadButton);
        result.innerHTML = response.data;
    });
}