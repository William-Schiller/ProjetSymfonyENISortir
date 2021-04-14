window.onload = countDown();

function countDown() {
    let timeCountDown = (dateCountdown - Date.now());
    let countDownText = "";
    let x = "";
    if(timeCountDown>0){
        timeCountDown = Math.floor(timeCountDown/1000);
        x = Math.floor(timeCountDown / (60*60*24));
        timeCountDown = timeCountDown % (60*60*24);
        if(x != 0){
            countDownText += x;
            countDownText += " jour";
            countDownText += x>1 ? "s " : " ";
        }
        x = Math.floor(timeCountDown / (60*60));
        timeCountDown = timeCountDown % (60*60);
        if(x != 0){
            countDownText += x;
            countDownText += " heure";
            countDownText += x>1 ? "s " : " ";
        }
        x = Math.floor(timeCountDown / (60));
        timeCountDown = timeCountDown % (60);
        if(x != 0){
            countDownText += x;
            countDownText += " minute";
            countDownText += x>1 ? "s " : " ";
        }

        countDownText += timeCountDown + " seconde";
        countDownText += timeCountDown>1 ? "s" : "";
    }else {
        countDownText += "La sortie à déjà débutée !";
    }

    document.getElementById("idCountDown").innerText = countDownText;
    setTimeout(countDown, 1000);
}