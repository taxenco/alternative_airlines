# Flight Search Web Application

## Overview

This project is a simple flight search web application that leverages the Amadeus Flight Low-fare Search API to allow users to search for flights based on their input criteria. Upon submitting the search form, the application fetches and displays the results.

## Technologies Used

- **Back-end**:
  - **Framework**: Laravel
  - **Language**: PHP 8.2
  - **API Integration**: Amadeus Flight Low-fare Search API
  - **Service Provider**: A custom service provider is used to handle all Amadeus API requests, ensuring clean separation of concerns. The service is utilized within the controller to process flight search queries.
  - **Caching**: A caching mechanism is implemented to refresh every 15 minutes, reducing duplicate API calls and improving performance.
  - **Unit Testing**: Unit tests are included to verify the functionality of the application. However, due to reliance on the third-party Amadeus API, some issues may arise during testing, particularly in cases where API requests are made.

- **Front-end**:
  - **Languages**: HTML, CSS, JavaScript
  - **Libraries/Frameworks**:
    - **jQuery**: For handling events and AJAX requests.
    - **Bootstrap 5**: For responsive design and layout.
    - **Font Awesome**: For icons.

## Features

- **Flight Search Form**:
  - Users can input departure and destination locations, travel dates, and the number of passengers.
  - The form includes input validation, such as preventing selection of the same departure and arrival airports, and ensuring that dates are not in the past.

- **Search Results Display**:
  - Fetches flight data from the Amadeus API and displays a list of available flights matching the user’s criteria.
  - Results include key details like departure and arrival cities, duration, number of stops, carrier, and price.
  - A "Book Now" button is included for each flight result (linking to booking functionality can be added in future iterations).

- **User Feedback**:
  - A loading spinner and progress bar are shown while the search is being processed.
  - Modal overlays are used to display search results in a user-friendly manner.

- **Caching**:
  - API responses are cached for 15 minutes to avoid duplicate calls to the Amadeus API and to improve response times for identical queries within that time frame.

- **Unit Testing**:
  - The application includes unit tests to ensure the core functionality works as expected. However, due to the integration with the third-party Amadeus API, some tests may encounter issues, such as failures when live API calls are required during the test execution.

## Installation and Setup

### Prerequisites

- PHP 8.2 or higher
- Composer (for managing PHP dependencies)
- Laravel (already included in the project)

### Steps to Set Up the Project

1. **Clone the Repository**:
   ```bash
   git clone <repository-url>
   cd <project-directory>
   ```

2. **Install PHP Dependencies**:
   ```bash
   composer install
   ```

3. **Set Up Environment Variables**:
   - Copy the `.env.example` file to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Update the `.env` file with your Amadeus API credentials:
     ```
     AMADEUS_API_KEY=your_api_key_here
     AMADEUS_API_SECRET=your_api_secret_here
     ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Run Migrations** (if any):
   ```bash
   php artisan migrate
   ```

6. **Serve the Application**:
   ```bash
   php artisan serve
   ```

7. **Access the Application**:
   - Open your web browser and go to `http://localhost:8000`.

## Usage

1. **Flight Search**:
   - On the homepage, fill in the flight search form with your desired travel details, including departure and arrival airports, travel dates, and the number of passengers.
   - Click "Search" to submit the form.

2. **View Search Results**:
   - The application will query the Amadeus API (utilizing the service provider and cache) and display a list of available flights that match your search criteria in a modal overlay.
   - If no flights are found, an alert will be shown.

3. **Book Flights**:
   - Although the "Book Now" button is currently a placeholder, it can be linked to a booking page or external booking service in future development stages.

## Additional Information

- **Code Quality**:
  - The code focuses on functionality and structure, with a basic yet responsive and user-friendly interface.

- **Further Development**:
  - Potential improvements include adding filters, sorting options, enhanced error handling, add more unit tests, and user authentication for personalized experiences.

## Contact

For any questions regarding this project or the task, please contact Carlos at [carlos.beltran.exposito@gmail.com](mailto:carlos.beltran.exposito@gmail.com).