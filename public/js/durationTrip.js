function postDuration(){
    let textDuration = "";
    let duration = document.getElementById("create_trip_duration").value;
    if (duration >= 24){
        let dayDuration = Math.floor(duration/24);
        duration = duration % 24;
        textDuration = dayDuration;
        textDuration += " jour";
        textDuration += dayDuration>1 ? "s" : "" ;
        if(duration>0){
            textDuration += " ";
            textDuration += duration>0 ? duration : "";
            textDuration += duration>1 ? " heures" : " heure";
        }
    } else {
        if (duration >0) {
            textDuration = duration;
            textDuration += duration>1 ? " heures" : " heure";
        }
    }

    document.getElementById("idDurationConv").innerHTML = textDuration;
}