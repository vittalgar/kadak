import './bootstrap';

// --- THE FIX IS HERE ---
// This code listens for the browser's "pageshow" event, which fires
// every time a user navigates to a page, including with the back button.
window.addEventListener('pageshow', function (event) {
    // The `persisted` property is true if the page is being loaded from a cache.
    if (event.persisted) {
        // If it's from a cache, force a full reload of the page.
        window.location.reload();
    }
});