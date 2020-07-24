// A reference to Stripe.js initialized with your real test publishable API key.
var sessionId = document.querySelector("button").getAttribute('data-secret') || '';
var publicKey = document.querySelector("button").getAttribute('public-key') || '';
var stripe = Stripe(publicKey);

var checkoutButton = document.getElementById('checkout-button');

checkoutButton.addEventListener('click', function() {
  stripe.redirectToCheckout({
    // Make the id field from the Checkout Session creation API response
    // available to this file, so you can provide it as argument here
    // instead of the {{CHECKOUT_SESSION_ID}} placeholder.
    sessionId: sessionId
  }).then(function (result) {
    // If `redirectToCheckout` fails due to a browser or network
    // error, display the localized error message to your customer
    // using `result.error.message`.
	console.log(result.error.message);
  });
});