document.addEventListener("DOMContentLoaded", function () {
    let selectedCarId = null;
    let selectedDriverId = null;

    // Toggle car selection
    window.toggleCarSelection = function (carElement) {
        const carId = carElement.getAttribute("data-car-id");

        // Deselect previously selected car
        if (selectedCarId) {
            document.querySelector(`.car-item[data-car-id="${selectedCarId}"]`).classList.remove("selected");
        }

        // Select new car
        carElement.classList.add("selected");
        selectedCarId = carId;

        // Update hidden input
        document.getElementById("car-id").value = carId;
    };

    // Toggle driver selection
    window.toggleDriverSelection = function (driverElement) {
        const driverId = driverElement.getAttribute("data-driver-id");

        // Deselect previously selected driver
        if (selectedDriverId) {
            document.querySelector(`.driver-item[data-driver-id="${selectedDriverId}"]`).classList.remove("selected");
        }

        // Select new driver
        driverElement.classList.add("selected");
        selectedDriverId = driverId;

        // Update hidden input
        document.getElementById("driver-id").value = driverId;
    };

    // Modal functionality
    window.openModal = function (model, image, dimensions, engine, fuel, seating, transmission, horsepower, acceleration, speed) {
        document.getElementById("modalTitle").textContent = model;
        document.getElementById("modalImage").src = image;
        document.getElementById("modalDimensions").textContent = dimensions;
        document.getElementById("modalEngine").textContent = engine;
        document.getElementById("modalFuel").textContent = fuel;
        document.getElementById("modalSeating").textContent = seating;
        document.getElementById("modalTransmission").textContent = transmission;
        document.getElementById("modalHorsepower").textContent = horsepower;
        document.getElementById("modalAcceleration").textContent = acceleration;
        document.getElementById("modalSpeed").textContent = speed;

        // Display the modal
        document.getElementById("carModal").style.display = "block";
    };

    // Close modal functionality
    document.querySelector(".modal .close").addEventListener("click", function () {
        document.getElementById("carModal").style.display = "none";
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const driverCheckbox = document.getElementById('include-driver');
    const driverSelection = document.getElementById('driver-selection');
    const driverIdInput = document.getElementById('driver-id');
    
    // Initially hide the driver selection section
    driverSelection.style.display = 'none';

    // Show or hide the driver selection section when the checkbox is toggled
    driverCheckbox.addEventListener('change', function() {
        if (this.checked) {
            driverSelection.style.display = 'block';  // Show the section
        } else {
            driverSelection.style.display = 'none';   // Hide the section
            driverIdInput.value = '';                 // Clear the selected driver ID
        }
    });

    // Function to handle driver selection
    window.toggleDriverSelection = function(driverElement) {
        // Deselect any previously selected driver
        document.querySelectorAll('.driver-item').forEach(function(item) {
            item.classList.remove('selected');
        });

        // Select the clicked driver
        driverElement.classList.add('selected');

        // Get the driver ID and assign it to the hidden input field
        const driverId = driverElement.getAttribute('data-driver-id');
        driverIdInput.value = driverId;
    };
});



// JavaScript for handling car color selection and driver choice
document.addEventListener('DOMContentLoaded', function () {
    // Function to handle car selection and display color options
    function handleCarSelection(carId, colorOptions) {
        const colorSelect = document.getElementById('car-color');
        colorSelect.innerHTML = ''; // Clear existing options

        colorOptions.forEach(color => {
            const option = document.createElement('option');
            option.value = color;
            option.textContent = color;
            colorSelect.appendChild(option);
        });

        document.getElementById('color-selection').style.display = 'block';
    }

    // Example of how to call handleCarSelection, replace with your logic
    function toggleCarSelection(carElement) {
        const carId = carElement.getAttribute('data-car-id');
        // Fetch or generate color options based on carId
        // For demonstration, using static values
        const colorOptions = ['Red', 'Blue', 'Green']; // This should be dynamically fetched
        handleCarSelection(carId, colorOptions);
    }

    // Example modal opening (you should handle this with real data)
    function openModal(title, imageUrl, dimensions, engineType, fuelEfficiency, seatingCapacity, transmission, horsepower, acceleration, topSpeed) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('modalDimensions').textContent = dimensions;
        document.getElementById('modalEngine').textContent = engineType;
        document.getElementById('modalFuel').textContent = fuelEfficiency;
        document.getElementById('modalSeating').textContent = seatingCapacity;
        document.getElementById('modalTransmission').textContent = transmission;
        document.getElementById('modalHorsepower').textContent = horsepower;
        document.getElementById('modalAcceleration').textContent = acceleration;
        document.getElementById('modalSpeed').textContent = topSpeed;

        const modal = document.getElementById('carModal');
        modal.style.display = 'block';

        document.querySelector('.close').onclick = function() {
            modal.style.display = 'none';
        }
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const carSelection = document.querySelectorAll('.car-item');
    const driverCheckbox = document.getElementById('include-driver');
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    const startTimeInput = document.getElementById('start-time');
    const endTimeInput = document.getElementById('end-time');
    const totalPriceInput = document.getElementById('total-price');
    const totalPriceDisplay = document.getElementById('total-cost');

    let selectedCarRate = 0;
    let isDriverIncluded = false;

    function updateTotalPrice() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        const startTime = startTimeInput.value.split(':');
        const endTime = endTimeInput.value.split(':');

        const startDateTime = new Date(startDate.getFullYear(), startDate.getMonth(), startDate.getDate(), startTime[0], startTime[1]);
        const endDateTime = new Date(endDate.getFullYear(), endDate.getMonth(), endDate.getDate(), endTime[0], endTime[1]);

        const hours = Math.abs(endDateTime - startDateTime) / 36e5; // Calculate hours
        const driverFee = isDriverIncluded ? 500 : 0; // Example driver fee

        const totalPrice = (selectedCarRate * hours) + driverFee;
        totalPriceInput.value = totalPrice.toFixed(2);
        totalPriceDisplay.textContent = `₱${totalPrice.toFixed(2)}`;
    }

    // Add event listeners to car items
    carSelection.forEach(car => {
        car.addEventListener('click', () => {
            selectedCarRate = parseFloat(car.getAttribute('data-rate-per-hour'));
            updateTotalPrice();
        });
    });

    // Update total price when driver checkbox changes
    driverCheckbox.addEventListener('change', () => {
        isDriverIncluded = driverCheckbox.checked;
        updateTotalPrice();
    });

    // Update total price when dates or times change
    [startDateInput, endDateInput, startTimeInput, endTimeInput].forEach(input => {
        input.addEventListener('change', updateTotalPrice);
    });
});
