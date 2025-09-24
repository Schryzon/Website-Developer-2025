(function(){ // -> Auto invoke, style-matched to sign-up.js
  const EMAIL_RE = /^[a-z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-z0-9-]+(?:\.[a-z0-9-]+)+$/i;
  const PASS_MIN_LEN = 8;

  const form = document.getElementById("formloginVal");
  const emailEl = document.getElementById("emailLogin");
  const passEl  = document.getElementById("pwLogin");

  function validate_email(){
    const v = emailEl.value.trim();
    if (v.length === 0) {
      emailEl.setCustomValidity("Email is required.");
    } else if (!EMAIL_RE.test(v)) {
      emailEl.setCustomValidity("Enter a valid email, e.g. name+tag@domain.com or name@domain.com.");
    } else {
      emailEl.setCustomValidity("");
    }
  }

  function validate_password(){
    const v = passEl.value;
    if (v.length === 0) {
      passEl.setCustomValidity("Password cannot be empty.");
    } else {
      passEl.setCustomValidity("");
    }
  }

  emailEl.addEventListener("input", validate_email);
  passEl.addEventListener("input", validate_password);

  form.addEventListener("submit", function(e){
    validate_email();
    validate_password();

    if (!form.checkValidity()) {
      e.preventDefault();
      form.reportValidity();
      return;
    }

    // Do NOT prevent default here — let the form submit to login.php for server check.
    // If you prefer AJAX, we could switch to fetch() and handle JSON responses instead.
  });
})();
