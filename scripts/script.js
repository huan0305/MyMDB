/* 
Javascript for MyMDB pages
*/

// Function that opens the mobile navigation overlay
function openNavOverlay() {
	document.getElementById("navOverlay").style.width = "100%";
	return false;
}

// Function that closes the mobile navigation overlay
function closeNavOverlay() {
	document.getElementById("navOverlay").style.width = "0%";
	return false;
}

// Get the login/register modal element
const authModal = document.getElementById("auth");

// Open login/registration form
function openAuth() {
	authModal.style.display = "block";
}

// Close login/registration form
function closeAuth() {
	authModal.style.display = "none";
}

// When the user clicks anywhere outside of the login/register dialog, close it
window.onclick = function(event) {
	if (event.target == authModal) {
		authModal.style.display = "none";
	}
}

// Handle the response to the XMLHttpRequest made to register
// a new user, reload the page on success
function handleRegistrationResponse() {
	if (this.readyState == 4 && this.status == 200) {
		if (this.getResponseHeader("yougotin") == null) {
			// Server should always responds with "yougotin" header for 
			// these requests, it's unexpected if they don't
			alert("Unexpected server response");
		} else if (this.getResponseHeader("yougotin") == "1") {
			// Success, reload the page
			window.location.reload();
		} else {
			// yougotin: 0, therefore failed to register
			alert("failed to register: " + this.responseText);
		}
	} else if (this.readyState == 4) {
		// Some other communication error.
		alert("Communication error!");
	}
}

// Instead of using regular form submission, use XMLHttpResponse to
// Submit registration form and reload on success.
function onRegister(ev) {
	// prevent normal form handling
	ev.preventDefault();

	// Prepare the http request
	const httpReq = new XMLHttpRequest();
	const url = 'registration.php';

	// Assemble the POST parameters
	const displayName = document.getElementById("displayName").value;
	const userName = document.getElementById("username").value;
	const passwd = document.getElementById("password").value;
	const email = document.getElementById("email").value;
	const params = "displayName="+displayName+"&username="+userName+"&password="+passwd+"&email="+email;

	// Asynchronous POST request
	httpReq.open('POST', url, true);

	// Send the proper header information along with the request
	httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	httpReq.onreadystatechange = handleRegistrationResponse;
	httpReq.send(params);

	return false;
}

// Handler for login requests
function handleLoginResponse() {
	if (this.readyState == 4 && this.status == 200) {
		if (this.getResponseHeader("yougotin") == null) {
			// Server should always responds with "yougotin" header for 
			// these requests, it's unexpected if they don't
			alert("Unexpected server response");
		} else if (this.getResponseHeader("yougotin") == "1") {
			// Success, reload the page
			window.location.reload(); // reload the page
		} else {
			// Log in failed
			alert("username or password wrong!");
		}
	} else if (this.readyState == 4) {
		// Unexpected communication error
		alert("Communication error!");
	}
}
// Handler for login requests
function onLogin(ev) {
	// Do not do regular form event processing
	ev.preventDefault();

	// Gather the post parameters
	const httpReq = new XMLHttpRequest();
	const url = 'login.php';
	const userName = document.getElementById("username").value;
	const passwd = document.getElementById("password").value;
	const params = "username="+userName+"&password="+passwd;

	// Do an asynchronous POST request
	httpReq.open('POST', url, true);

	// Send the proper header information along with the request
	httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

	// Set the handler
	httpReq.onreadystatechange = handleLoginResponse;

	// Send the request
	httpReq.send(params);

	return false;
}

// Swap between login and register forms
function swapAuth(ev) {
	// Don't treat this like a regular link
	ev.preventDefault();

	// Find all the HTML elements needed
	const authForm = ev.target.previousElementSibling;
	const user = authForm.querySelector("#username");
	const regFields = authForm.getElementsByClassName("regField");
	const pass = authForm.querySelector("#password");
	const submitButton = authForm.querySelector("input[type=\"submit\"]");

	// If it's set to switch to login
	if (ev.target.innerText == "Login") {
		// Set the event handler
		authForm.onsubmit = onLogin;
		// Set the new text for swapping
		ev.target.innerText = "Register";
		// Set the submit button text
		submitButton.value = "Login";
		// Reset all registration fields to their defaults.
		Array.prototype.map.call(
			regFields,
			(f) => { f.style.display = null;});
	} else { // Else we switch to a registration form
		// Set the event handler
		authForm.onsubmit = onRegister;
		// Set the text for the swap element
		ev.target.innerText = "Login";
		// Set the text on the submit button
		submitButton.value = "Register";
		// Make all registration fields visible
		Array.prototype.map.call(
			regFields,
			(f) => { f.style.display = "inline";});
	}
	return false;
}

// Parse the URL and return the GET parameter
// with the movie_id
function getMovieId() {
	// Get the query string from the URL
	var queryString = window.location.search;
	// Match the url parameter for the movie_id
	var regex = /movie_id=([0-9]+)/;
	var match = regex.exec(queryString);
	if (match) {
		return match[1];
	} else {
		return null;
	}
}

// Initialize the last fetch to a 0 timestamp
var lastReviewFetch = new Date(0);
// Interval ID for refreshReviews automatic calling
var reviewRefreshId;
function refreshReviews() {
	// Use userReviews to check if we're on the movie page
	const reviews = document.getElementById("userReviews");
	if (reviews == null) {
		// Don't set up interval calls on non movie pages
		return;
	} else if (reviewRefreshId == null) {
		// if it's our first call, set up the interval call.
		reviewRefreshId = setInterval(function(){
			refreshReviews();
		}, 2000);
	}

	// Set up a XML HTTP Request to get new reviews
	const httpReq = new XMLHttpRequest();
	const url = 'reviews.php';
	const movieId = getMovieId();
	// Need to divide JavaScript timestamp by 1000 because it's millisecond instead of microseconds
	const params = "?movie_id="+movieId+"&from_time="+lastReviewFetch.valueOf()/1000;

	// Define our handler
	httpReq.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			// If request completed sucessfully
			if (this.responseText.length > 3) {
				// If non empty JSON response, parse the new reviews
				const newReviews = JSON.parse(this.responseText);

				// Loop over each and create the elements to display them
				for (const newReviewObj of newReviews) {
					const newDiv = document.createElement("div");
					const newStars = document.createElement("span");
					const newSubject = document.createElement("span");
					const newTime = document.createElement("span");
					const newName = document.createElement("p");
					const reviewText = document.createElement("p");

					// Display the star rating
					newStars.innerText = "✭".repeat(newReviewObj['stars']) + " ";

					// Create the subject line
					if (newReviewObj['subject'] != null)
						newSubject.innerText = newReviewObj['subject'];
					else
						newSubject.innerText = " Subject ";

					// Create the date to show time of review
					const timestamp = newReviewObj['timestamp'] * 1000;
					const date = new Date(timestamp);
					if (timestamp > lastReviewFetch.valueOf())
						lastReviewFetch = date;
					newTime.innerText = " " + date.toLocaleString();

					// Add the author's displayname
					newName.innerText = "by " + newReviewObj['displayname'];
					newSubject.innerText = newReviewObj['subject'];
					reviewText.innerText = newReviewObj['reviewtext'];

					// Add all elements to the dom
					newDiv.appendChild(newStars);
					newDiv.appendChild(newSubject);
					newDiv.appendChild(newTime);
					newDiv.appendChild(newName);
					newDiv.appendChild(reviewText);

					// Add classes for styling
					newDiv.classList.add("userReviewsStyle");
					newTime.classList.add("timeStyle");

					reviews.after(newDiv);
				}
			}
		} else if (this.readyState == 4) {
			// Alert for debugging
			alert("Communication error!");
		}
	};
	// Async GET request
	httpReq.open('GET', url+params, true);
	// Send the request
	httpReq.send();
}
refreshReviews(); // Always call once for set up

// Event handler for sending a review to the server
function submitReview(ev) {
	// Prevent normal submission
	ev.preventDefault();

	// Gather all the form inputs
	const reviewForm = ev.target;
	const subject = reviewForm.querySelector("#subject").value;
	const reviewtext = reviewForm.querySelector("#movie_review").value;
	const starrating = reviewForm.querySelector("#star_rating").value;

	// Create the request
	const httpReq = new XMLHttpRequest();
	const url = 'reviews.php';
	const params = "movie_id="+getMovieId()+"&subject="+subject+"&reviewtext="+reviewtext+"&starrating="+starrating;
	httpReq.open('POST', url, true);

	// Send the proper header information along with the request
	httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

	// Handler for server response
	httpReq.onreadystatechange = () => {
		if (httpReq.readyState == 4 && httpReq.status == 200) {
			// If succesful transmission, replace the submission form with message
			const reviewForm = document.querySelector("form[action=\"reviews.php\"]");
			reviewForm.outerHTML = "<p>Review submitted!</p>";
		} else if (httpReq.readyState == 4) {
			// Alert for debugging
			alert("Communication error!");
		}
	};

	// Send the request
	httpReq.send(params);

	return false;
}

// Slideshow FUNCTIONALITY for movie_page
let slideIndex = 1;
if (document.getElementsByClassName("slides").length) {
  showSlides(slideIndex);
}

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

// Set the current slide to the one given by the index
function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("slides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {
	  slideIndex = 1;
  }
  if (n < 1) {
	  slideIndex = slides.length;
  }
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
}

// END OF Slideshow FUNCTIONALITY

// Begin index page search functions

function movieSearch(ev) {
	// Do not treat this a normal form submission
	ev.preventDefault();

	if (!validateindex()) {
		return false;
	}

	// Create the request object
	const httpReq = new XMLHttpRequest();
	const url = 'search.php';
	let params = "?";

	// Gather the inputs
	var searchMovieInput = document.getElementById('searchMovie');
	var searchGenreInput = document.getElementById('searchGenre');
	var movie = searchMovieInput.value;
	// Determine whether we're doing a movie search or genre search
	if (movie) {
		params += "movie_search=" + searchMovieInput.value;
	} else {
		// convert genre to numeric id
		let number = +searchGenreInput.value;
		if (isNaN(number)) // Ensure its numeric
			return;
		params += "genre=" + number;
	}

	// Handle server response
	httpReq.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			// Replace the search results with the response html
			let results = document.getElementById('searchResults');
			results.outerHTML = this.responseText;
		} else if (this.readyState == 4) {
			// Alert to debug unusual outcome
			alert("Communication error!");
		}
	};

	// Do a async GET request
	httpReq.open('GET', url+params, true);
	httpReq.send();
	return false;
}

function clearSearchMovie() {
	// Get the searchMovie input element
	var searchMovieInput = document.getElementById('searchMovie');
	// Clear the input value
	searchMovieInput.value = '';
}

// End of index search