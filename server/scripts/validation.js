//define a global variables
const emailErrorMsg = "✖ Email address should be non-empty with the format xyz@xyz.xyz.";
const usernameErrorMsg = "✖ User name should be non-empty, and less than 20 characters in length.";
const passwordErrorMsg = "✖ Password should be atleast 6 characters: 1 uppercase, 1 lowercase.";
const retypePasswordErrorMsg = "✖ Please retype password.";
const termsErrorMsg = "✖ Please accept the terms and conditions.";

const validatedFields = {
	"email":{selector:"#email", validate:validateEmail, errorText:emailErrorMsg},
	"login":{selector:"#login", validate:validateUsername, errorText:usernameErrorMsg},
	"password":{selector:"#pass", validate:validatePassword, errorText:passwordErrorMsg},
	"retypepassword":{selector:"#pass2", validate:validateRepeatPwd, errorText:retypePasswordErrorMsg},
	"terms":{selector:"#terms", validate:validateTerms, errorText:termsErrorMsg}
};

for (const k in validatedFields) {
	const field = validatedFields[k];
	field.input = document.querySelector(field.selector);

	if (field.input == null)
		continue; // Not present on this page

	// create paragraph to display the error Msg returned by validateEmail() function 
	// and assign this paragraph to the class warning to style the error Msg
	switch (field.input.type) {
		case 'checkbox':
			var tag = 'span';
			break;
		default:
			var tag = 'p';
			break;
	}
	field.errorEl = document.createElement(tag);
	field.errorEl.setAttribute("class","warning");
	field.errorEl.style.display = 'none';
	
	
	field.input.after(field.errorEl);
}

function validateEmail(input) {
	// Get the email testbox value
    let email = input.value;
	
	// A valid email has the structure (xyx@xyz.xyz).
    let regexp = /^\S+@\S+\.\S+$/;
	
	// return whether the entered email matches the regexp
    return regexp.test(email);
}

function validateUsername(input) {
	// Login name should be non-empty and less than 30 characters long.

	// Get username textbox value
    let username = input.value;
	/* Regular expression:
	 * 	- Match begin and end of string for strict matching
	 *	  of the username (i.e. no leading/trailing characters)
	 *  - Then match 1 to 29 alphabetic characters.
	 */
    const regexp = /^[a-zA-Z]{1,29}$/;
	
	// return whether the entered email matches the regular expr.
    return regexp.test(username);
}

function validatePassword(passwordInput1) {
	// Password should be at least 8 characters long.
	// Ensure that both the password fields have the same value and are not blank.
	let pass = passwordInput1.value;

	/* Regular expression:
	 * 	- Match begin and end of string for strict matching
	 *	  of the username (i.e. no leading/trailing characters)
	 *  - Then match 8 non-whitespace characters.
	 */
	const regexp = /^\S{8,}$/;
	
    return regexp.test(pass);
}
function validateRepeatPwd(passwordInput2) {
	// Ensure that both the password fields have the same value and are not blank.
	// Get the first password textbox value
	const pass1 = validatedFields["password"].input.value;
	// Get the second password textbox value
	const pass2 = passwordInput2.value;
	return pass2 && (pass1 === pass2);
}

function validateTerms(termsInput) {
	// Ensure that the terms checkbox is checked
	return termsInput.checked;
}

// Set or reset the field error depending on the second parameter
function fieldError(field, set) {
	
	// Derive event type from field type
	switch (field.input.type) {
		case 'text':
		case 'password':
			var changeEv = 'blur';
			break;
		case 'checkbox':
			var changeEv = 'change';
			break;
		default:
			var changeEv = 'input';
			break;
	}
	// function to reset the error if the field becomes valid
	const reValidate = function() {
		if (field.validate(field.input))
			fieldError(field, false);
	};
	
	if (set) {
		// Set the error text
		field.errorEl.textContent = field.errorText;
		// Set the display setting to default
		field.errorEl.style.display = null;
		// Set the style of the input element
		switch (field.input.type) {
			case "text":
			case "password":
				field.input.classList.add("warning");
				break;
		}
		// Add event listener that clears the error once the field is valid
		field.input.addEventListener(changeEv, reValidate);
	} else {
		// If an error was set, remove the event listener
		if (field.errorEl.textContent != "")
			field.input.removeEventListener(changeEv, reValidate);
		// Unset the error message
		field.errorEl.textContent = "";
		// Remove the error text from the layout
		field.errorEl.style.display = 'none';
		// Reset the style of the input element
		switch (field.input.type) {
			case "text":
			case "password":
				field.input.classList.remove("warning");
				break;
		}
	}
}

// event handler for form submission
function validate() {
    // Whether entire form is valid
	let valid = true;
	
	for (const k in validatedFields) {
		const field = validatedFields[k];
		
		if (!field.validate(field.input)) { // If field is not valid
			fieldError(field, true); // set the error message
			valid = false;   // set the entire form as invalid
		}
	}
    
	if (valid) {
		// convert the login name to all lower-case alphabetic characters
		let usernameInput = validatedFields["login"].input;
		usernameInput.value = usernameInput.value.toLowerCase();
	}
	return valid;
};