<?php
  include '../conn.php';

  // Mulai session untuk notifikasi
  session_start();

  // Inisialisasi variabel notifikasi
  $error = $_SESSION['error'] ?? '';
  $success = $_SESSION['success'] ?? '';

  // Hapus notifikasi setelah ditampilkan
  unset($_SESSION['error'], $_SESSION['success']);

    if($_SERVER['REQUEST_METHOD']==='POST'){
        if (isset($_POST['submit_add'])) {
            //memasukkan data ke 4 tabel dengan query terpisah

            // pakai ini untuk bagian check in//
            try{
              $id_customer = htmlspecialchars($_POST['id_customer']);
              $check_in = htmlspecialchars($_POST['check_in']);
              $check_out = htmlspecialchars($_POST['check_out']);
              $room = htmlspecialchars($_POST['room']);
              $quantity = htmlspecialchars($_POST['quantity']); //sesuai pada front
              
              switch ($room){
                case "executive":
                  $total_price = htmlspecialchars("2000");
                  break;
                case "luxury":
                  $total_price = htmlspecialchars("55000");
                  break;
                case "presidential":
                  $total_price = htmlspecialchars("150000");
                  break;
              }
                $stmt = $pdo->prepare("INSERT INTO booking(id_customer, check_in, check_out, room, total_price) VALUES (:id_customer, :check_in, :check_out, :room, :total_price)");
                $stmt->bindParam(':id_customer', $id_customer);
                $stmt->bindParam(':check_in', $check_in);
                $stmt->bindParam(':check_out', $check_out);
                $stmt->bindParam(':room', $room);
                $stmt->bindParam(':total_price', $total_price);
              
                //jalankan kode
                $stmt->execute();
              // Pesan sukses
              $_SESSION['success'] = "New data added successfully!";
              //agar submit tidak diulangi ketika web di refresh
              header("Location: " . $_SERVER['PHP_SELF']);
              exit(); 
            }catch (PDOException $e) {
              $_SESSION['error'] = "Error adding data: " . $e->getMessage();
            }

            //pakai ini untuk bagian additional services
            //bisa juga kalian set untuk harga additional service
            //description bisa buat sendiri sesuai quantity
            //contoh description value ("spa selama {$quantity_addition}")
            try {
                $name = htmlspecialchars($_POST['name']);
                $description = htmlspecialchars($_POST['description']);
                $price = htmlspecialchars($_POST['price']);
                $quantity_addition = htmlspecialchars($_POST['quantity_addition']);
                // Prepare statement untuk memasukkan data
                $stmt = $pdo->prepare("INSERT INTO additional_service (name, description, price) VALUES (:name, :description, :price)");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);

                // Eksekusi query
                $stmt->execute();

                // Pesan sukses
                $_SESSION['success'] = "New service added successfully!";

            } catch (PDOException $e) {
                $_SESSION['error'] = "Error adding service: " . $e->getMessage();
            }

            // Redirect untuk mencegah form resubmission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();


            //pakai ini untuk mengisi tabel room sesuai pilihan user
            //pricenya bisa dipakai sesuai ketentuan pada line 26
            //sisanya tinggal improvisasi kode

            try{
                //htmlspecialchars memastikan data yang di input tidak berupa kode sql injection
                $room = htmlspecialchars($_POST['room']);
                $price = htmlspecialchars($_POST['price']);
                
                //prepare agar tidak terjadi SQL injection
                $stmt = $pdo->prepare("INSERT INTO room(room_type, price) VALUES (:room, :price)");
                $stmt->bindParam(':room', $room);
                $stmt->bindParam(':price', $price);
              
                //jalankan kode
                $stmt->execute();
        
                // Pesan sukses
                $_SESSION['success'] = "New data added successfully!";
                //agar submit tidak diulangi ketika web di refresh
                header("Location: " . $_SERVER['PHP_SELF']);
                exit(); 
              }catch (PDOException $e) {
                $_SESSION['error'] = "Error adding data: " . $e->getMessage();
            }

            //pakai ini untuk mengisi tabel payment
            //kolom "payment-name" akan diisi sesuai pilihan user pada <select>
            //status dan date silahkan isi sendiri melalui kode
            try{
                //htmlspecialchars memastikan data yang di input tidak berupa kode sql injection
                $name = htmlspecialchars($_POST['name']);
                $status = htmlspecialchars($_POST['status']);
                $date = htmlspecialchars($_POST['date']);
                
                //prepare agar tidak terjadi SQL injection
                $stmt = $pdo->prepare("INSERT INTO payment(name, status, date) VALUES (:name, :status, :date)");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':date', $date);
              
                //jalankan kode
                $stmt->execute();
        
                // Pesan sukses
                $_SESSION['success'] = "New data added successfully!";
                //agar submit tidak diulangi ketika web di refresh
                header("Location: " . $_SERVER['PHP_SELF']);
                exit(); 
              }catch (PDOException $e) {
                $_SESSION['error'] = "Error adding data: " . $e->getMessage();
            }
        }
        //untuk total price, bisa buat kodenya sendiri agar muncul di tampilan
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orelega+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Luxurious+Roman&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            background-color: #15274b;
            font-family: 'Luxurious Roman';
            margin-bottom: 0; /* Hapus jarak di bawah navbar */
            position: sticky;
            top: 0;
        }

        .navbar-brand {
            font-family: 'Plus Jakarta Sans', sans-serif; /* Ganti dengan font yang diinginkan */
            font-weight: 600;
        }
        
        .navbar .btn {
            font-family: 'Luxurious Roman', sans-serif ;
        }
        
        .navbar .btn-outline-light {
            border-color: white;
            color: white;
        }
        
        .navbar .btn-outline-light:hover {
            background-color: white;
            color: black;
        }

        .reservation-header {
            font-family: 'Luxurious Roman';
            font-weight: bold;
            margin-top: 30px;
            text-align: left;
            padding: 0 20px;
            font-size: 2rem;
            color: #001f54;
        }

        .reservation-form {
            padding: 20px;
        }

        .payment{
            border-radius: 100px;
        }

        .card-header {
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .card-body{
            font-size: 1.2rem;
            color: white;
        }

        .btn-primary {
            color: #15274b;
            background-color: #eae2be;
            border: none;
        }

        .btn-primary:hover {
            background-color: #eae2be;
        }

        .text-highlight {
            color: white;
            font-weight: bold;
        }

        .increment-buttons input {
            width: 60px;
            text-align: center;
        }

        .additional-service-item span.service-name {
        font-size: 1rem;
        color: black;
        font-family: 'Luxurious Roman', serif;
    }

        .additional-service-item span.service-price {
            font-size: 0.9rem;
            color: #6c757d; 
            font-family: 'Arial', sans-serif;
            display: block; 
            margin-top: 5px;
        }

        .btn-book-now {
            position: absolute;
            bottom: 10px;
            right: 15px;
            font-size: 0.9rem; 
            padding: 5px 15px; 
            background-color: #eae2be; 
            color: #15274b; 
            border: none;
            border-radius: 5px; 
        }

        .btn-book-now:hover {
            background-color: #d4c69d; 
        }

        .card.mb-4 {
            height: 80%
        }

        

            </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
        <a class="navbar-brand" href="index.html">MOONLIT HOTEL</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php#about">About Us</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php#rooms">Rooms & Suites</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php#facilities">Facilities</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php#additionalservices">Additional Services</a></li>
            <li class="nav-item"><a class="nav-link" href="reservasi.php">Booking</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php#contactus">Contact Us</a></li>
            </ul>
            <div class="d-flex ms-3">
            <button class="btn btn-outline-light me-2"  onclick="window.location.href='login.html';">Login</button>
            <button class="btn btn-primary" style="background-color: black; color: white;" onclick="window.location.href='signup.html';">Sign up</button>
            </div>
        </div>
        </div>
    </nav>

    <div class="reservation-header ">Reservation</div>

    <div class="reservation-form container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="checkIn" class="form-label">Check-In</label>
                    <input type="date" class="form-control" id="checkIn">
                </div>
                <div class="mb-3">
                    <label for="checkOut" class="form-label">Check-Out</label>
                    <input type="date" class="form-control" id="checkOut">
                </div>
                <div class="card">
                    <div class="card-header" style="background-color: #001f54; color: #eae2be; font-family: 'Luxurious Roman';">Rooms & Suites</div>
                    <div class="card-body" style="background-color: #001f54;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-family: 'Luxurious Roman';" >Executive Suite</span>
                            <div class="increment-buttons">
                                <button class="btn btn-sm btn-light">-</button>
                                <input type="text" value="0" readonly>
                                <button class="btn btn-sm btn-light">+</button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-family: 'Luxurious Roman';" >Luxury Suite</span>
                            <div class="increment-buttons">
                                <button class="btn btn-sm btn-light">-</button>
                                <input type="text" value="0" readonly>
                                <button class="btn btn-sm btn-light">+</button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="font-family: 'Luxurious Roman';" >Presidential Suite</span>
                            <div class="increment-buttons">
                                <button class="btn btn-sm btn-light">-</button>
                                <input type="text" value="0" readonly>
                                <button class="btn btn-sm btn-light">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="payment col-md-6">
                <div class="card mb-4">
                    <div class="card-header" style="background-color: #001f54; font-family: 'Luxurious Roman';">Total</div>
                    <div class="card-body" style="background-color: #001f54;">
                        <span class="text-highlight" style="font-family: 'Luxurious Roman';">IDR 0</span>
                    </div>
                    <div class="card-header" style="background-color: #001f54;">Payment Method</div>
                    <div class="card-body" style="background-color: #001f54;">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="cash" value="cash">
                            <label class="form-check-label" style="font-family: 'Luxurious Roman';" for="cash">Cash Payment</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" value="creditCard">
                            <label class="form-check-label" style="font-family: 'Luxurious Roman';" for="creditCard">Credit Card</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="bankTransfer" value="bankTransfer">
                            <label class="form-check-label" style="font-family: 'Luxurious Roman';" for="bankTransfer">Bank Transfer</label>
                        </div>
                        <button class="btn btn-book-now">Book Now</button>
                    </div>
                </div>
            </div>

        <div class="card mt-4" id="addition">
            <div class="card-header" style="background-color:white; color:#001f54; font-family: 'Luxurious Roman';">Additional Services</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="additional-service-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="service-name">Personalized In-Room Dining</span>
                                <span class="service-price">IDR 500,000/session</span>
                            </div>
                            <div class="increment-buttons">
                                <button class="btn btn-sm btn-light">-</button>
                                <input type="text" value="0" readonly>
                                <button class="btn btn-sm btn-light">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="additional-service-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="service-name">24/7 In-Room Spa Services</span>
                                <span class="service-price">IDR 1,000,000/session</span>
                            </div>
                            <div class="increment-buttons">
                                <button class="btn btn-sm btn-light">-</button>
                                <input type="text" value="0" readonly>
                                <button class="btn btn-sm btn-light">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="additional-service-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="service-name">Personal Fitness Trainer & Wellness Coach</span>
                                <span class="service-price">IDR 800,000/hour</span>
                            </div>
                            <div class="increment-buttons">
                                <button class="btn btn-sm btn-light">-</button>
                                <input type="text" value="0" readonly>
                                <button class="btn btn-sm btn-light">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="additional-service-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="service-name">Exclusive Kids Club with Personalized Activities</span>
                                <span class="service-price">IDR 400,000/day/child</span>
                            </div>
                            <div class="increment-buttons">
                                <button class="btn btn-sm btn-light">-</button>
                                <input type="text" value="0" readonly>
                                <button class="btn btn-sm btn-light">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const roomPrices = {
            executive: 3500000,
            luxury: 7500000,
            presidential: 15000000
        };
    
        const servicePrices = {
            dining: 500000,
            spa: 1000000,
            fitness: 800000,
            kidsClub: 400000
        };
    
        function updateValue(button, operation) {
            const input = button.parentElement.querySelector('input'); 
            let currentValue = parseInt(input.value); 
    
            if (operation === 'increment') {
                input.value = currentValue + 1;
            } else if (operation === 'decrement' && currentValue > 0) {
                input.value = currentValue - 1; 
            }
    
            updateTotal(); 
        }
    
        function calculateDays() {
            const checkInDate = new Date(document.getElementById('checkIn').value);
            const checkOutDate = new Date(document.getElementById('checkOut').value);
    
            if (checkInDate && checkOutDate && checkOutDate > checkInDate) {
                const timeDiff = checkOutDate - checkInDate;
                const days = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
                return days;
            }
            return 0; 
        }
    
        function updateTotal() {
            let total = 0;
    
            // harga kamar
            const executiveCount = parseInt(document.querySelector('.increment-buttons input').value);
            const luxuryCount = parseInt(document.querySelectorAll('.increment-buttons input')[1].value);
            const presidentialCount = parseInt(document.querySelectorAll('.increment-buttons input')[2].value);
    
            const days = calculateDays();
            total += executiveCount * roomPrices.executive * days;
            total += luxuryCount * roomPrices.luxury * days;
            total += presidentialCount * roomPrices.presidential * days;
    
            // harga layanan tambahan
            const diningCount = parseInt(document.querySelectorAll('.increment-buttons input')[3].value);
            const spaCount = parseInt(document.querySelectorAll('.increment-buttons input')[4].value);
            const fitnessCount = parseInt(document.querySelectorAll('.increment-buttons input')[5].value);
            const kidsClubCount = parseInt(document.querySelectorAll('.increment-buttons input')[6].value);
    
            total += diningCount * servicePrices.dining;
            total += spaCount * servicePrices.spa;
            total += fitnessCount * servicePrices.fitness;
            total += kidsClubCount * servicePrices.kidsClub;
    
            
            document.querySelector('.text-highlight').textContent = `IDR ${total.toLocaleString()}`;
        }
    
       
        document.querySelectorAll('.increment-buttons button').forEach(button => {
            button.addEventListener('click', function () {
                if (this.textContent === '+') {
                    updateValue(this, 'increment'); 
                } else if (this.textContent === '-') {
                    updateValue(this, 'decrement');
                }
            });
        });
    
       
        document.getElementById('checkIn').addEventListener('change', updateTotal);
        document.getElementById('checkOut').addEventListener('change', updateTotal);
    </script>
    
</body>
</html>
