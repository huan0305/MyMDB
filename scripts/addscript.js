/* 
Javascript validation for add movie page
*/

let movieInput2=document.querySelector('[name="' + "movieName" + '"]');
let movieError=document.createElement('p');
movieError.setAttribute("class", "warning");
document.querySelector('[name="' + "movieName" + '"]').parentNode.append(movieError);

let dateInput = document.querySelector('[name="' + "releaseDate" + '"]');
let dateError = document.createElement('p');
dateError.setAttribute("class", "warning");
document.querySelector('[name="' + "releaseDate" + '"]').parentNode.append(dateError);

let descriptionInput = document.querySelector('[name="' + "description" + '"]');
let descriptionError = document.createElement('p');
descriptionError.setAttribute("class", "warning");
document.querySelector('[name="' + "description" + '"]').parentNode.append(descriptionError);

let defaultMsg = "";
let movieErrorMsg = "Movie should be non-empty";
let dateErrorMsg = "Date should follow the format: YYYY-MM-DD";
let descriptionErrorMsg = "Description should be at least 10 characters."


// validate the user has entered a valid movie
function validateMovie() {
    let movie = movieInput2.value;
    if (movie.length >= 2) {
        error = defaultMsg;
    } else {
        error = movieErrorMsg;
    }
    return error;
}
// validate the user has selected a date in the proper format
function validateDate() {
    let date = dateInput.value;
    let regexp = /[0-9]{4}-[0-9]{2}-[0-9]{2}/;
    if (regexp.test(date)) {
        error = defaultMsg;
    } else {
        error = dateErrorMsg;
    }
    return error;
}

// validate the user has entered a description 
function validateDescription() {
    let description = descriptionInput.value;
    if (description.length >= 10) {
        error = defaultMsg;
    } else {
        error = descriptionErrorMsg;
    }
    return error;
}

// check if all the inputs are valid
function validate() {
	let valid = true;
    let movieValidation = validateMovie();
    if (movieValidation !== defaultMsg) {
        movieError.textContent = movieValidation;
        valid = false;
    }
    let dateValidation = validateDate();
    if (dateValidation !== defaultMsg) {
        dateError.textContent = dateValidation;
        valid = false;
    }
    let descriptionValidation = validateDescription();
    if (descriptionValidation != defaultMsg) {
        descriptionError.textContent = descriptionValidation;
        valid = false;
    }
    return valid;
}

// add an event listener if the user changes their input to a valid movie
movieInput2.addEventListener("blur",()=>{
    let x=validateMovie();
    if (x == defaultMsg) {
        movieError.textContent = defaultMsg;
    }
});


// add an event listener if the user changes their input to a valid date
dateInput.addEventListener("blur",()=>{
    let x=validateDate();
    if (x == defaultMsg) {
        dateError.textContent = defaultMsg;
    }
});

// add an event listener if the user changes their input to a valid description
descriptionInput.addEventListener("blur",()=>{
    let x=validateDescription();
    if (x == defaultMsg) {
        descriptionError.textContent = defaultMsg;
    }
});





