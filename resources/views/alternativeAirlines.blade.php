<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alternative Airlines - Flight Search</title>
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
    </style>
</head>
<body>
    <div class="search-container">
        <h1>Find Your Perfect Flight</h1>
        <form class="search-form">
            <div class="input-group">
                <i class="fas fa-plane-departure"></i>
                <input type="text" placeholder="Departure Airport" required>
            </div>
            <div class="input-group">
                <i class="fas fa-plane-arrival"></i>
                <input type="text" placeholder="Arrival Airport" required>
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
                <select required>
                    <option value="1">1 Passenger</option>
                    <option value="2">2 Passengers</option>
                    <option value="3">3 Passengers</option>
                    <option value="4">4 Passengers</option>
                </select>
            </div>
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies (Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
</body>
</html>
