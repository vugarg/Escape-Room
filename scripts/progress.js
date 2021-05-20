var isRevealed = false;
function showPwd() {
    if (isRevealed == false) {
        document.getElementById("password").type = 'text';
        isRevealed = true;
    } else {
        document.getElementById("password").type = 'password';
        isRevealed = false;
    }
}

$(window).load(function() { 
    $(".loader-wrapper").fadeOut(1500); 
    });

let progress = document.getElementById('progressbar');
let totalHeight = document.body.scrollHeight - window.innerHeight;

window.onscroll= function(){
    let progressHeight = (window.pageYOffset / totalHeight) * 100;
    progress.style.height = progressHeight + "%";
}

function validate() {
    var name = document.forms["jsform"]["username"];
    var password = document.forms["jsform"]["password"];
    var repeat_password = document.forms["jsform"]["password-repeat"];

    if (name.value == "") {
        window.alert("Please enter your name.");
        name.focus();
        return false;
    }

    if (password.value == "") {
        window.alert(
          "Please enter your password.");
        password.focus();
        return false;
    }

    if (repeat_password.value == "") {
        window.alert(
          "Please enter your password again.");
        repeat_password.focus();
        return false;
    }

    return true;
}