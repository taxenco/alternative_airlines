<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Search</title>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/alternativeAirlines.css') }}">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="search-container">
        <h1>Find Your Perfect Flight</h1>
        <form class="search-form">
            <div class="input-group">
                <i class="fas fa-plane-departure"></i>
                <select id="departure-airport" class="form-select" required>
                    <option value="" disabled selected>Select Departure Airport</option>
                </select>
            </div>
            <div class="input-group">
                <i class="fas fa-plane-arrival"></i>
                <select id="arrival-airport" class="form-select" required>
                    <option value="" disabled selected>Select Arrival Airport</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="departure-date" class="form-label">Departure Date</label>
                <div class="input-group">
                    <i class="fas fa-calendar-alt"></i>
                    <input type="date" id="departure-date" class="form-control" placeholder="Departure Date" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="return-date" class="form-label">Return Date (Optional)</label>
                <div class="input-group">
                    <i class="fas fa-calendar-alt"></i>
                    <input type="date" id="return-date" class="form-control" placeholder="Return Date">
                </div>
            </div>
            <div class="input-group">
                <i class="fas fa-users"></i>
                <select id="passenger" class="form-select" required>
                    <option value="1">1 Passenger</option>
                    <option value="2">2 Passengers</option>
                    <option value="3">3 Passengers</option>
                    <option value="4">4 Passengers</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <!-- Spinner Container -->
        <div class="spinner-container">
            <div class="spinner-border" role="status"></div>
            <div class="loading-text">Finding Flights...</div>
            <div class="loading-progress mt-2">
                <div class="loading-progress-bar"></div>
            </div>
        </div>
    </div>

    <!-- Modal Overlay -->
    <div class="modal-overlay">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Flight Search Results</h5>
                </div>
                <div class="modal-body">
                    <!-- Results will be injected here -->
                    <div id="flight-results-content" class="results-grid">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-close">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>



 <!-- Bootstrap JS and dependencies (Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    // List of airports with their names and codes
    const airports = [
        { name: "London Heathrow", code: "LHR" },
        { name: "Gatwick Airport", code: "LGW" },
        { name: "Manchester Airport", code: "MAN" },
        { name: "Edinburgh Airport", code: "EDI" },
        { name: "Birmingham Airport", code: "BHX" },
        { name: "Glasgow Airport", code: "GLA" },
        { name: "Bristol Airport", code: "BRS" },
        { name: "Liverpool John Lennon Airport", code: "LPL" },
        { name: "Newcastle Airport", code: "NCL" },
        { name: "London Stansted", code: "STN" },
        { name: "Charles de Gaulle Airport, Paris", code: "CDG" },
        { name: "Frankfurt Airport", code: "FRA" },
        { name: "Amsterdam Schiphol Airport", code: "AMS" },
        { name: "Madrid Barajas Airport", code: "MAD" },
        { name: "Barcelona El Prat Airport", code: "BCN" },
        { name: "Lisbon Airport", code: "LIS" },
        { name: "Rome Fiumicino Airport", code: "FCO" },
        { name: "Milan Malpensa Airport", code: "MXP" },
        { name: "Vienna International Airport", code: "VIE" },
        { name: "Brussels Airport", code: "BRU" },
        { name: "Munich Airport", code: "MUC" },
        { name: "Copenhagen Airport", code: "CPH" },
        { name: "Stockholm Arlanda Airport", code: "ARN" },
        { name: "Helsinki Airport", code: "HEL" },
        { name: "Zurich Airport", code: "ZRH" },
        { name: "Budapest Airport", code: "BUD" },
        { name: "Athens International Airport", code: "ATH" },
        { name: "Warsaw Chopin Airport", code: "WAW" },
        { name: "Prague Václav Havel Airport", code: "PRG" },
        { name: "Dublin Airport", code: "DUB" },
        { name: "Berlin Brandenburg Airport", code: "BER" },
    ];

    /**
     * Populates a given select element with airport options.
     * 
     * @param {jQuery} selectElement - The jQuery object representing the select element to populate.
     */
    function populateAirportDropdown(selectElement) {
        airports.forEach(function(airport) {
            const option = $('<option></option>')
                .attr('value', airport.code)
                .text(`${airport.name} (${airport.code})`);
            selectElement.append(option);
        });
    }

    // Populate the departure and arrival airport dropdowns
    populateAirportDropdown($('#departure-airport'));
    populateAirportDropdown($('#arrival-airport'));

    /**
     * Handles the form submission for searching flights.
     * 
     * @param {Event} e - The submit event.
     */
    $('.search-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Get form values
        const departureAirport = $('#departure-airport').val();
        const arrivalAirport = $('#arrival-airport').val();
        const departureDate = $('#departure-date').val();
        const returnDate = $('#return-date').val();
        const today = new Date().toISOString().split('T')[0];

        // Validate form input
        if (departureAirport === arrivalAirport) {
            alert('Departure and Arrival airports cannot be the same.');
            return;
        }

        if (departureDate < today) {
            alert('Departure Date cannot be in the past.');
            return;
        }

        if (returnDate) {
            if (returnDate < today) {
                alert('Return Date cannot be in the past.');
                return;
            }
            if (returnDate < departureDate) {
                alert('Return Date cannot be earlier than the Departure Date.');
                return;
            }
        }
        
        // Show loading spinner
        $('.search-container').addClass('spinner-active');
        $('.spinner-container').show();
        
        // Gather form data
        var formData = {
            originLocationCode: departureAirport,
            destinationLocationCode: arrivalAirport,
            departureDate: departureDate,
            returnDate: returnDate,
            adults: $('#passenger').val()
        };

        // Send AJAX POST request to search flights
        $.ajax({
            url: '/api/search-flights/',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token if using Laravel
            },
            success: function(response) {
                // Hide loading spinner
                $('.search-container').removeClass('spinner-active');
                $('.spinner-container').hide();

                // Display flight results or alert if no flights found
                if (response.data && response.data.length > 0) {
                    $('#flight-results-content').html(generateFlightResultsHTML(response.data));
                    $('.modal-overlay').fadeIn();
                } else {
                    alert("No flights found. Please try different search criteria.");
                }
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error('Error:', error);
                console.error('Status:', status);
                console.error('XHR:', xhr);

                // Hide loading spinner
                $('.search-container').removeClass('spinner-active');
                $('.spinner-container').hide();

                alert("An error occurred while searching for flights. Please try again later.");
            }
        });
    });

    /**
     * Generates HTML for flight results.
     * 
     * @param {Array} response - The flight data array.
     * @returns {string} - The generated HTML string.
     */
    function generateFlightResultsHTML(response) {
        console.log(response);
        let html = '';
    
        response.forEach(function(flight) {
            html += `
                <div class="result-item">
                    <h5>${flight.itinerary.segments[0].departure.city} (${flight.itinerary.segments[0].departure.airportCode}) to 
                        ${flight.itinerary.segments[flight.itinerary.segments.length - 1].arrival.city} (${flight.itinerary.segments[flight.itinerary.segments.length - 1].arrival.airportCode})
                    </h5>
                    <p>Duration: ${flight.itinerary.totalDuration}</p>
                    <p>Stops: ${flight.itinerary.stop}</p>
                    <p>Carrier: ${flight.itinerary.segments[0].carrier}</p>
                    <p>Price: ${flight.price.currency} ${flight.price.total}</p>
                    <button class="btn-book">Book Now</button>
                </div>`;
        });
    
        return html;
    }

    // Close the modal when the close button is clicked
    $('.btn-close').on('click', function() {
        $('.modal-overlay').fadeOut();
    });

    // Close the modal when clicking outside of it
    $(document).on('click', function(event) {
        if ($(event.target).closest('.modal-content').length === 0) {
            $('.modal-overlay').fadeOut();
        }
    });
});
    </script>