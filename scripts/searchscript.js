/* 
Javascript validation for search form on index page
*/

let movieNameInput=document.querySelector('[name="' + "movie_search" + '"]');
let movieNameError=document.createElement('p');
movieNameError.setAttribute("class", "warning");
movieNameInput.parentNode.append(movieNameError);
let movieNameErrorMsg = "Please enter a movie name or select a genre";
let defaultMsg = "";
let genreArray = document.getElementById("searchGenre");


// validate that the user has either entered a movie or selected a genre
function validateMovieName() {

let selectedOption = genreArray.value;
let defaultOption = genreArray.options[0].value;

    let movie = movieNameInput.value;
    if ( movie.length >= 2 || selectedOption !== defaultOption) {
        error = defaultMsg;
    } else {
        error = movieNameErrorMsg;
    }
    return error;
}


function validateindex() {

    let valid = true;
    let movieNameValidation = validateMovieName();

    if (movieNameValidation !== defaultMsg) {
        movieNameError.textContent = movieNameValidation;
        valid = false;
    }
    return valid;

}

// add an event listener if the user changes their input to a correct movie
movieNameInput.addEventListener("onchange",()=>{
    let x=validateMovieName();
    if (x == defaultMsg) {
        movieNameError.textContent = defaultMsg;
    }
});

// add an event listener if user changes their selection to select a valid genre
genreArray.addEventListener("onchange",()=>{
    let x=validateMovieName();
    if (x == defaultMsg) {
        movieNameError.textContent = defaultMsg;
    }
});