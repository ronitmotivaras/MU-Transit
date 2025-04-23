<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MU Transit - Routes Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: white;
      display: flex;
    }
    .sidebar {
      width: 250px;
      height: 100vh;
      position: fixed;
      background-color: #212529;
      padding-top: 20px;
      color: white;
      overflow-y: auto;
    }
    .sidebar h3 {
      text-align: center;
    }
    .sidebar a {
      color: white;
      padding: 12px 15px;
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }
    .sidebar a:hover {
      background-color: #343a40;
    }
    .content {
      flex-grow: 1;
      margin-left: 250px;
      padding: 20px;
      width: calc(100% - 250px);
      background-color: white;
    }
    .btn-group {
      display: flex;
      justify-content: center;
      gap: 5px;
    }
    .add-btn {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    .add-btn:hover {
      background-color: #218838;
    }
    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
    }
    .modal-content {
      background-color: white;
      margin: 5% auto;
      padding: 20px;
      border-radius: 5px;
      width: 70%;
      max-width: 600px;
    }
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #dee2e6;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }
    .close-btn {
      font-size: 24px;
      cursor: pointer;
    }
    .form-group {
      margin-bottom: 15px;
    }
    table {
      table-layout: fixed;
    }
    td {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .invalid-feedback {
      display: none;
      color: #dc3545;
      font-size: 14px;
    }
    .is-invalid {
      border-color: #dc3545;
    }
    .is-invalid + .invalid-feedback {
      display: block;
    }
    /* Fix for the view modal to show full address */
    .route-details td {
      white-space: normal;
      word-wrap: break-word;
    }
    /* Section separators */
    .section-separator {
      margin: 30px 0;
      border-top: 1px solid #dee2e6;
      padding-top: 20px;
    }
  </style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Sidebar -->
  <?php include('sidenavbar.php')?>

  <!-- Main Content -->
  <div class="content">
    <!-- Cities Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Cities</h2>
      <button class="add-btn" onclick="openAddCityModal()">
        <i class="fas fa-plus"></i> Add City
      </button>
    </div>

    <table class="table table-bordered table-striped text-center">
      <thead class="table-dark">
        <tr>
          <th width="10%">Sr. No</th>
          <th>City</th>
          <th width="20%">Actions</th>
        </tr>
      </thead>
      <tbody id="cityTable">
        <!-- City data will be dynamically loaded -->
      </tbody>
    </table>

    <!-- Routes Section -->
    <div class="section-separator">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Routes</h2>
        <button class="add-btn" onclick="openAddRouteModal()">
          <i class="fas fa-plus"></i> Add Route
        </button>
      </div>

      <table class="table table-bordered table-striped text-center">
        <thead class="table-dark">
          <tr>
            <th width="7%">Sr. No</th>
            <th>City</th>
            <th>Route</th>
            <th>Time</th>
            <th>Travel Time</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="routeTable">
          <!-- Route data will be dynamically loaded -->
        </tbody>
      </table>
    </div>
  </div>

  <!-- Add City Modal -->
  <div id="addCityModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Add New City</h3>
        <span class="close-btn" onclick="closeAddCityModal()">&times;</span>
      </div>
      <form id="addCityForm">
        <div class="form-group">
          <label for="cityName">City Name:</label>
          <input type="text" class="form-control" id="cityName" required>
          <div class="invalid-feedback">City name must contain only alphabets.</div>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-secondary me-2" onclick="closeAddCityModal()">Cancel</button>
          <button type="button" class="btn btn-success" onclick="validateAndSaveCityData()">Save City</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit City Modal -->
  <div id="editCityModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Edit City</h3>
        <span class="close-btn" onclick="closeEditCityModal()">&times;</span>
      </div>
      <form id="editCityForm">
        <input type="hidden" id="editCityKey">
        <div class="form-group">
          <label for="editCityName">City Name:</label>
          <input type="text" class="form-control" id="editCityName" required>
          <div class="invalid-feedback">City name must contain only alphabets.</div>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-secondary me-2" onclick="closeEditCityModal()">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="validateAndUpdateCityData()">Update City</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Add Route Modal -->
  <div id="addRouteModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Add New Route</h3>
        <span class="close-btn" onclick="closeAddRouteModal()">&times;</span>
      </div>
      <form id="addRouteForm">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="citySelect">City:</label>
              <select class="form-control" id="citySelect" required>
                <option value="">Select City</option>
                <!-- City options will be loaded dynamically -->
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="route">Route:</label>
              <input type="text" class="form-control" id="route" required>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="address">Address:</label>
          <textarea class="form-control" id="address" rows="2" required></textarea>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="time">Time:</label>
              <input type="time" class="form-control" id="time" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="travelTime">Travel Time (minutes):</label>
              <input type="number" class="form-control" id="travelTime" min="1" required>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-secondary me-2" onclick="closeAddRouteModal()">Cancel</button>
          <button type="button" class="btn btn-success" onclick="validateAndSaveRouteData()">Save Route</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Route Modal -->
  <div id="editRouteModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Edit Route</h3>
        <span class="close-btn" onclick="closeEditRouteModal()">&times;</span>
      </div>
      <form id="editRouteForm">
        <input type="hidden" id="editRouteKey">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="editCitySelect">City:</label>
              <select class="form-control" id="editCitySelect" required>
                <option value="">Select City</option>
                <!-- City options will be loaded dynamically -->
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="editRoute">Route:</label>
              <input type="text" class="form-control" id="editRoute" required>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="editAddress">Address:</label>
          <textarea class="form-control" id="editAddress" rows="2" required></textarea>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="editTime">Time:</label>
              <input type="time" class="form-control" id="editTime" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="editTravelTime">Travel Time (minutes):</label>
              <input type="number" class="form-control" id="editTravelTime" min="1" required>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-secondary me-2" onclick="closeEditRouteModal()">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="validateAndUpdateRouteData()">Update Information</button>
        </div>
      </form>
    </div>
  </div>

  <!-- View Route Modal -->
  <div id="viewRouteModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Route Details</h3>
        <span class="close-btn" onclick="closeViewRouteModal()">&times;</span>
      </div>
      <div class="route-details">
        <table class="table table-bordered">
          <tr>
            <th width="30%">City</th>
            <td id="viewCity"></td>
          </tr>
          <tr>
            <th>Address</th>
            <td id="viewAddress"></td>
          </tr>
          <tr>
            <th>Route</th>
            <td id="viewRoute"></td>
          </tr>
          <tr>
            <th>Time</th>
            <td id="viewTime"></td>
          </tr>
          <tr>
            <th>Travel Time</th>
            <td id="viewTravelTime"></td>
          </tr>
        </table>
      </div>
      <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-secondary" onclick="closeViewRouteModal()">Close</button>
      </div>
    </div>
  </div>

  <!-- Firebase -->
  <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>

  <script>
    // Firebase config
    const firebaseConfig = {
      databaseURL: "https://mu-transit-86f0a-default-rtdb.firebaseio.com/"
    };
    firebase.initializeApp(firebaseConfig);
    const db = firebase.database();

    // Load data on page load
    window.onload = function () {
      loadCityData();
      loadRouteData();
      
      // Add input validation listeners
      document.getElementById("cityName").addEventListener("input", validateCityName);
      document.getElementById("editCityName").addEventListener("input", validateEditCityName);
    };

    // ------------------- City Functions -------------------
    
    function loadCityData() {
      const table = document.getElementById("cityTable");
      table.innerHTML = '';
      let serialNum = 1;
      
      db.ref("cities").once("value", function (snapshot) {
        if (snapshot.exists()) {
          snapshot.forEach(function (childSnapshot) {
            const key = childSnapshot.key;
            const data = childSnapshot.val();
            appendCityRow(data, key, serialNum);
            serialNum++;
          });
        } else {
          // Create cities node if it doesn't exist
          db.ref("cities").set({});
        }
        
        // Update city dropdowns
        updateCityDropdowns();
      });
    }

    function appendCityRow(data, key, serialNum) {
      const table = document.getElementById("cityTable");
      const row = document.createElement("tr");
      row.setAttribute("data-key", key);
      
      row.innerHTML = `
        <td>${serialNum}</td>
        <td>${data.name}</td>
        <td class="btn-group">
          <button class="btn btn-sm btn-warning" onclick="openEditCityModal('${key}')">
            <i class="fas fa-edit"></i> Edit
          </button>
          <button class="btn btn-sm btn-danger" onclick="deleteCity('${key}')">
            <i class="fas fa-trash"></i> Delete
          </button>
        </td>`;
      table.appendChild(row);
    }
    
    function updateCityDropdowns() {
      const citySelects = [
        document.getElementById("citySelect"),
        document.getElementById("editCitySelect")
      ];
      
      // Clear existing options except the first one
      citySelects.forEach(select => {
        while (select.options.length > 1) {
          select.remove(1);
        }
      });
      
      // Add city options
      db.ref("cities").once("value", function (snapshot) {
        snapshot.forEach(function (childSnapshot) {
          const key = childSnapshot.key;
          const data = childSnapshot.val();
          
          citySelects.forEach(select => {
            const option = document.createElement("option");
            option.value = key;
            option.textContent = data.name;
            select.appendChild(option);
          });
        });
      });
    }
    
    function validateCityName() {
      const cityInput = document.getElementById("cityName");
      const value = cityInput.value.trim();
      const isValid = /^[A-Za-z\s]+$/.test(value);
      
      if (value && !isValid) {
        cityInput.classList.add("is-invalid");
        return false;
      } else {
        cityInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateEditCityName() {
      const cityInput = document.getElementById("editCityName");
      const value = cityInput.value.trim();
      const isValid = /^[A-Za-z\s]+$/.test(value);
      
      if (value && !isValid) {
        cityInput.classList.add("is-invalid");
        return false;
      } else {
        cityInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function openAddCityModal() {
      document.getElementById("addCityModal").style.display = "block";
      document.getElementById("addCityForm").reset();
      
      // Clear validation states
      document.getElementById("cityName").classList.remove("is-invalid");
    }
    
    function closeAddCityModal() {
      document.getElementById("addCityModal").style.display = "none";
    }
    
    function openEditCityModal(key) {
      document.getElementById("editCityModal").style.display = "block";
      document.getElementById("editCityForm").reset();
      document.getElementById("editCityKey").value = key;
      
      // Clear validation states
      document.getElementById("editCityName").classList.remove("is-invalid");
      
      // Fetch current city data
      db.ref("cities/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        document.getElementById("editCityName").value = data.name || '';
      });
    }
    
    function closeEditCityModal() {
      document.getElementById("editCityModal").style.display = "none";
    }
    
    function validateAndSaveCityData() {
      // Run validation
      const nameValid = validateCityName();
      
      if (!nameValid) {
        return; // Stop if validation fails
      }
      
      saveCityData();
    }
    
    function saveCityData() {
      const cityName = document.getElementById("cityName").value.trim();
      
      // Basic validation
      if (!cityName) {
        alert("Please enter a city name");
        return;
      }
      
      const cityData = {
        name: cityName
      };
      
      const newCityRef = db.ref("cities").push();
      newCityRef.set(cityData, function (error) {
        if (!error) {
          alert("City saved successfully!");
          closeAddCityModal();
          loadCityData();
        } else {
          alert("Error saving city.");
        }
      });
    }
    
    function validateAndUpdateCityData() {
      // Run validation
      const nameValid = validateEditCityName();
      
      if (!nameValid) {
        return; // Stop if validation fails
      }
      
      updateCityData();
    }
    
    function updateCityData() {
      const key = document.getElementById("editCityKey").value;
      const cityName = document.getElementById("editCityName").value.trim();
      
      // Basic validation
      if (!cityName) {
        alert("Please enter a city name");
        return;
      }
      
      const updatedData = {
        name: cityName
      };
      
      db.ref("cities/" + key).set(updatedData, function (error) {
        if (!error) {
          alert("City updated successfully!");
          closeEditCityModal();
          loadCityData();
          
          // Also update city name in routes
          updateCityNameInRoutes(key, cityName);
        } else {
          alert("Error updating city.");
        }
      });
    }
    
    function updateCityNameInRoutes(cityKey, cityName) {
      // Update the city name display in routes table
      db.ref("routes").orderByChild("cityId").equalTo(cityKey).once("value", function(snapshot) {
        snapshot.forEach(function(childSnapshot) {
          const routeKey = childSnapshot.key;
          db.ref("routes/" + routeKey).update({
            cityName: cityName
          });
        });
        
        // Reload routes to reflect changes
        loadRouteData();
      });
    }
    
    function deleteCity(key) {
      if (!confirm("Are you sure you want to delete this city? All routes associated with this city will also be deleted.")) {
        return;
      }
      
      // Check if there are routes using this city
      db.ref("routes").orderByChild("cityId").equalTo(key).once("value", function(snapshot) {
        // Delete associated routes first
        if (snapshot.exists()) {
          snapshot.forEach(function(childSnapshot) {
            const routeKey = childSnapshot.key;
            db.ref("routes/" + routeKey).remove();
          });
        }
        
        // Then delete the city
        db.ref("cities/" + key).remove(function (error) {
          if (!error) {
            alert("City and associated routes deleted successfully!");
            loadCityData();
            loadRouteData();
          } else {
            alert("Error deleting city.");
          }
        });
      });
    }

    // ------------------- Route Functions -------------------
    
    function loadRouteData() {
      const table = document.getElementById("routeTable");
      table.innerHTML = '';
      let serialNum = 1;
      
      db.ref("routes").once("value", function (snapshot) {
        if (snapshot.exists()) {
          snapshot.forEach(function (childSnapshot) {
            const key = childSnapshot.key;
            const data = childSnapshot.val();
            appendRouteRow(data, key, serialNum);
            serialNum++;
          });
        } else {
          // Create routes node if it doesn't exist
          db.ref("routes").set({});
        }
      });
    }

    function appendRouteRow(data, key, serialNum) {
      const table = document.getElementById("routeTable");
      const row = document.createElement("tr");
      row.setAttribute("data-key", key);
      
      // Format time for display
      const displayTime = data.time || 'N/A';
      
      // Format travel time for display
      const travelTime = data.travelTime ? data.travelTime + ' minutes' : 'N/A';
      
      row.innerHTML = `
        <td>${serialNum}</td>
        <td>${data.cityName || 'N/A'}</td>
        <td>${data.route}</td>
        <td>${displayTime}</td>
        <td>${travelTime}</td>
        <td class="btn-group">
          <button class="btn btn-sm btn-info" onclick="openViewRouteModal('${key}')">
            <i class="fas fa-eye"></i> View
          </button>
          <button class="btn btn-sm btn-warning" onclick="openEditRouteModal('${key}')">
            <i class="fas fa-edit"></i> Edit
          </button>
          <button class="btn btn-sm btn-danger" onclick="deleteRoute('${key}')">
            <i class="fas fa-trash"></i> Delete
          </button>
        </td>`;
      table.appendChild(row);
    }
    
    function openAddRouteModal() {
      document.getElementById("addRouteModal").style.display = "block";
      document.getElementById("addRouteForm").reset();
      updateCityDropdowns();
    }

    function closeAddRouteModal() {
      document.getElementById("addRouteModal").style.display = "none";
    }

    function openEditRouteModal(key) {
      document.getElementById("editRouteModal").style.display = "block";
      document.getElementById("editRouteForm").reset();
      document.getElementById("editRouteKey").value = key;
      updateCityDropdowns();
      
      // Fetch current route data
      db.ref("routes/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        
        // Set selected city
        if (data.cityId) {
          document.getElementById("editCitySelect").value = data.cityId;
        }
        
        document.getElementById("editRoute").value = data.route || '';
        document.getElementById("editAddress").value = data.address || '';
        document.getElementById("editTime").value = data.time || '';
        document.getElementById("editTravelTime").value = data.travelTime || '';
      });
    }

    function closeEditRouteModal() {
      document.getElementById("editRouteModal").style.display = "none";
    }

    function openViewRouteModal(key) {
      document.getElementById("viewRouteModal").style.display = "block";
      
      // Fetch current route data
      db.ref("routes/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        document.getElementById("viewCity").textContent = data.cityName || 'N/A';
        
        // Display full address without truncation
        const addressElement = document.getElementById("viewAddress");
        addressElement.textContent = data.address || '';
        
        document.getElementById("viewRoute").textContent = data.route || '';
        document.getElementById("viewTime").textContent = data.time || 'N/A';
        document.getElementById("viewTravelTime").textContent = data.travelTime ? data.travelTime + ' minutes' : 'N/A';
      });
    }

    function closeViewRouteModal() {
      document.getElementById("viewRouteModal").style.display = "none";
    }

    function validateAndSaveRouteData() {
      saveRouteData();
    }
    
    function saveRouteData() {
      const citySelect = document.getElementById("citySelect");
      const cityId = citySelect.value;
      const cityName = citySelect.options[citySelect.selectedIndex].text;
      const route = document.getElementById("route").value.trim();
      const address = document.getElementById("address").value.trim();
      const time = document.getElementById("time").value.trim();
      const travelTime = document.getElementById("travelTime").value.trim();

      // Basic validation
      if (!cityId || !route || !address || !time || !travelTime) {
        alert("Please fill all fields");
        return;
      }

      const routeData = {
        cityId: cityId,
        cityName: cityName,
        route: route,
        address: address,
        time: time,
        travelTime: travelTime
      };

      const newRouteRef = db.ref("routes").push();
      newRouteRef.set(routeData, function (error) {
        if (!error) {
          alert("Route saved successfully!");
          closeAddRouteModal();
          loadRouteData();
        } else {
          alert("Error saving route.");
        }
      });
    }

    function validateAndUpdateRouteData() {
      updateRouteData();
    }
    
    function updateRouteData() {
      const key = document.getElementById("editRouteKey").value;
      const citySelect = document.getElementById("editCitySelect");
      const cityId = citySelect.value;
      const cityName = citySelect.options[citySelect.selectedIndex].text;
      const route = document.getElementById("editRoute").value.trim();
      const address = document.getElementById("editAddress").value.trim();
      const time = document.getElementById("editTime").value.trim();
      const travelTime = document.getElementById("editTravelTime").value.trim();

      // Basic validation
      if (!cityId || !route || !address || !time || !travelTime) {
        alert("Please fill all fields");
        return;
      }

      const updatedData = {
        cityId: cityId,
        cityName: cityName,
        route: route,
        address: address,
        time: time,
        travelTime: travelTime
      };

      db.ref("routes/" + key).set(updatedData, function (error) {
        if (!error) {
          alert("Route updated successfully!");
          closeEditRouteModal();
          loadRouteData();
        } else {
          alert("Error updating route.");
        }
      });
    }

    function deleteRoute(key) {
      if (!confirm("Are you sure you want to delete this route?")) {
        return;
      }
      
      db.ref("routes/" + key).remove(function (error) {
        if (!error) {
          alert("Route deleted successfully!");
          loadRouteData();
        } else {
          alert("Error deleting from database.");
        }
      });
    }

    // Close the modals when clicking outside of them
    window.onclick = function(event) {
      const addCityModal = document.getElementById("addCityModal");
      const editCityModal = document.getElementById("editCityModal");
      const addRouteModal = document.getElementById("addRouteModal");
      const editRouteModal = document.getElementById("editRouteModal");
      const viewRouteModal = document.getElementById("viewRouteModal");
      
      if (event.target == addCityModal) {
        closeAddCityModal();
      }
      
      if (event.target == editCityModal) {
        closeEditCityModal();
      }
      
      if (event.target == addRouteModal) {
        closeAddRouteModal();
      }
      
      if (event.target == editRouteModal) {
        closeEditRouteModal();
      }
      
      if (event.target == viewRouteModal) {
        closeViewRouteModal();
      }
    }
    
    // Enforce alphabetic input for City fields
    document.getElementById("cityName").addEventListener("keypress", function(e) {
      if (!/[A-Za-z\s]/.test(e.key)) {
        e.preventDefault();
      }
    });
    
    document.getElementById("editCityName").addEventListener("keypress", function(e) {
      if (!/[A-Za-z\s]/.test(e.key)) {
        e.preventDefault();
      }
    });
  </script>
</body>
</html>