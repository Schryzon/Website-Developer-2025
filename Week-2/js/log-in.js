const emailLogin = /^[a-z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-z0-9-]+(?:\.[a-z0-9-]+)+$/i;

document.getElementById("formloginVal").addEventListener("submit", function(event) {
    event.preventDefault();
    let email_val = document.getElementById("emailLogin").value.trim();
    let pass_val = document.getElementById("pwLogin").value.trim();
    
    if (!emailLogin.test(email_val)) {
        alert("invalid email format!");
        return;
    }

    if (pass_val.length === 0) {
        alert("password cannot be empty");
        return;
    }

    alert("login successful");
} );