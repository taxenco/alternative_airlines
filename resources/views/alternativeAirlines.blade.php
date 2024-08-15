<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Search</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            background-color: #f7f7f7;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .search-container {
            max-width: 700px;
            margin: auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 30px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .search-container h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .search-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .search-form .input-group {
            display: flex;
            align-items: center;
            position: relative;
        }

        .search-form input,
        .search-form select {
            padding: 15px 20px;
            border: 1px solid #ddd;
            border-radius: 50px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }

        .search-form .input-group i {
            position: absolute;
            left: 15px;
            color: #aaa;
        }

        .search-form input[type="date"],
        .search-form input[type="text"],
        .search-form select {
            padding-left: 50px;
        }

        .search-form button {
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            align-self: center;
            width: 200px;
        }

        .search-form button:hover {
            background-color: #0056b3;
        }

        /* Spinner Styles */
        .spinner-container {
            display: none; /* Hidden by default */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 100;
            text-align: center;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
            color: #007bff;
            animation: spin 1s linear infinite;
            margin-bottom: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            font-size: 1.2rem;
            color: #007bff;
            font-weight: bold;
        }

        .loading-progress {
            width: 100%;
            height: 8px;
            background-color: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
        }

        .loading-progress-bar {
            width: 50%;
            height: 100%;
            background-color: #007bff;
            animation: loading 1.5s ease-in-out infinite;
        }

        @keyframes loading {
            0% { width: 0%; }
            100% { width: 100%; }
        }

        /* Dim background when spinner is active */
        .search-container.spinner-active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            z-index: 90;
            border-radius: 30px;
        }

        /* Modal Styles */

        .flightResultsModal {
            display: none;
        }

        .modal-header {
            border-bottom: none;
            justify-content: center;
        }

        .modal-title {
            font-weight: bold;
            font-size: 24px;
        }

        .modal-body {
            text-align: center;
        }

        .modal-content {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-footer {
            border-top: none;
            justify-content: center;
        }

        .modal-footer button {
            border-radius: 50px;
            padding: 10px 30px;
        }

        .result-item {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .result-item:hover {
            box-shadow: 0px 8px 12px rgba(0, 0, 0, 0.15);
        }

        .result-item h5 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #007bff;
        }

        .result-item p {
            font-size: 14px;
            margin-bottom: 5px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <h1>Find Your Perfect Flight</h1>
        <form class="search-form">
            <div class="input-group">
                <i class="fas fa-plane-departure"></i>
                <select id="departure-airport" required>
                    <option value="" disabled selected>Select Departure Airport</option>
                </select>
            </div>
            <div class="input-group">
                <i class="fas fa-plane-arrival"></i>
                <select id="arrival-airport" required>
                    <option value="" disabled selected>Select Arrival Airport</option>
                </select>
            </div>
            <div class="input-group">
                <i class="fas fa-calendar-alt"></i>
                <input type="date" placeholder="Departure Date" required>
            </div>
            <div class="input-group">
                <i class="fas fa-calendar-alt"></i>
                <input type="date" placeholder="Return Date">
            </div>
            <div class="input-group">
                <i class="fas fa-users"></i>
                <select id="passenger" required>
                    <option value="1">1 Passenger</option>
                    <option value="2">2 Passengers</option>
                    <option value="3">3 Passengers</option>
                    <option value="4">4 Passengers</option>
                </select>
            </div>
            <button type="submit">Search</button>
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

    <!-- Modal -->
    <div class="modal fade flightResultsModal" id="flightResultsModal" tabindex="-1" aria-labelledby="flightResultsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="flightResultsModalLabel">Flight Search Results</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Results will be injected here -->
                    <div id="flight-results-content">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <script>
$(document).ready(function() {
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

    function populateAirportDropdown(selectElement) {
        airports.forEach(function(airport) {
            const option = $('<option></option>').attr('value', airport.code).text(`${airport.name} (${airport.code})`);
            selectElement.append(option);
        });
    }

    populateAirportDropdown($('#departure-airport'));
    populateAirportDropdown($('#arrival-airport'));

    $('.search-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the form from submitting the traditional way
        
        // Show spinner
        $('.search-container').addClass('spinner-active');
        $('.spinner-container').show();
        
        // Gather form data
        var formData = {
            originLocationCode: $('#departure-airport').val(),
            destinationLocationCode: $('#arrival-airport').val(),
            departureDate: $('input[placeholder="Departure Date"]').val(),
            returnDate: $('input[placeholder="Return Date"]').val(),
            adults: $('#passenger').val()
        };

        // Send AJAX POST request
        $.ajax({
            url: '/api/search-flights/',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add this if you're using Laravel's CSRF protection
            },
            success: function(response) {
                // Hide spinner
                $('.search-container').removeClass('spinner-active');
                $('.spinner-container').hide();
                // Populate modal with results and show it
                if (response.data && response.data.length > 0) {
                    $('#flight-results-content').html(generateFlightResultsHTML(response.data));
                    $('.flightResultsModal').css('display', 'block').modal('show');

                } else {
                    alert("No flights found. Please try different search criteria.");
                }
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error('Error:', error);
                console.error('Status:', status);
                console.error('XHR:', xhr);

                // Hide spinner
                $('.search-container').removeClass('spinner-active');
                $('.spinner-container').hide();

                alert("An error occurred while searching for flights. Please try again later.");
            }
        });
    });

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
                </div>`;
        });
    
    return html;
}
    
});
    </script>
</body>
</html>
