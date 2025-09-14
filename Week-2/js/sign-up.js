(function(){ // -> Auto invoke
  /** Regexes (clarity-first, no RFC headache) */
  // Name: only letters (Unicode) + spaces. No digits, no specials.
  const NAME_RE = /^[\p{L} ]+$/u;

  // Email: must have local@domain.tld (allows + and other legal chars), requires at least one dot in domain.
  // Blocks `a@a`, accepts `a+b@a.com`.
  const EMAIL_RE = /^[a-z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-z0-9-]+(?:\.[a-z0-9-]+)+$/i;

  // Password: ≥8 chars, ≥1 lower, ≥1 upper, ≥1 special (non-alnum). No digit requirement.
  const PASS_RE = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[^A-Za-z0-9]).{8,}$/;

  const form = document.getElementById("signup-form");
  const nameEl = document.getElementById("name");
  const emailEl = document.getElementById("email");
  const passEl = document.getElementById("password");

  function validate_name(){
    const v = nameEl.value.trim();
    if(v.length === 0){
      nameEl.setCustomValidity("Name is required.");
    }else if( !NAME_RE.test(v) ){
      nameEl.setCustomValidity("Name can only contain letters and spaces (no numbers or special characters).");
    }else{
      nameEl.setCustomValidity("");
    }
  }

  function validate_email(){
    const v = emailEl.value.trim();
    if(v.length === 0){
      emailEl.setCustomValidity("Email is required.");
    }else if( !EMAIL_RE.test(v) ){
      emailEl.setCustomValidity("Enter a valid email like name+tag@domain.com or name@domain.com.");
    }else{
      emailEl.setCustomValidity("");
    }
  }

  function validate_password(){
    const v = passEl.value;
    if(v.length === 0){
      passEl.setCustomValidity("Password is required.");
    }else if(!PASS_RE.test(v)){
      passEl.setCustomValidity("Password must be at least 8 characters with 1 uppercase, 1 lowercase, and 1 special character.");
    }else{
      passEl.setCustomValidity("");
    }
  }

  // Live validation
  nameEl.addEventListener("input", validate_name);
  emailEl.addEventListener("input", validate_email);
  passEl.addEventListener("input", validate_password);

  // On submit
  form.addEventListener("submit", function(e){
    validate_name();
    validate_email();
    validate_password();
    if(!form.checkValidity()){
      e.preventDefault();
      form.reportValidity(); // show native messages
    }
  });
})();
