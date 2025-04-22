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
    .add-route-btn {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    .add-route-btn:hover {
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
  </style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Sidebar -->
  <?php include('sidenavbar.php')?>

  <!-- Routes List -->
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Routes</h2>
      <button class="add-route-btn" onclick="openAddRouteModal()">
        <i class="fas fa-plus"></i> Add Route
      </button>
    </div>

    <table class="table table-bordered table-striped text-center">
      <thead class="table-dark">
        <tr>
          <th>City</th>
          <th>Address</th>
          <th>Bus Stop</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="routeTable">
        <!-- Data will be dynamically loaded -->
      </tbody>
    </table>
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
              <label for="city">City:</label>
              <input type="text" class="form-control" id="city" required>
              <div class="invalid-feedback">City name must contain only alphabets.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="busStop">Bus Stop:</label>
              <input type="text" class="form-control" id="busStop" required>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="address">Address:</label>
          <textarea class="form-control" id="address" rows="2" required></textarea>
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
              <label for="editCity">City:</label>
              <input type="text" class="form-control" id="editCity" required>
              <div class="invalid-feedback">City name must contain only alphabets.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="editBusStop">Bus Stop:</label>
              <input type="text" class="form-control" id="editBusStop" required>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="editAddress">Address:</label>
          <textarea class="form-control" id="editAddress" rows="2" required></textarea>
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
            <th>Bus Stop</th>
            <td id="viewBusStop"></td>
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

    // Load routes on page load
    window.onload = function () {
      loadRouteData();
      
      // Add input validation listeners
      document.getElementById("city").addEventListener("input", validateCity);
      document.getElementById("editCity").addEventListener("input", validateEditCity);
    };

    function loadRouteData() {
      const table = document.getElementById("routeTable");
      table.innerHTML = '';
      db.ref("routes").once("value", function (snapshot) {
        snapshot.forEach(function (childSnapshot) {
          const key = childSnapshot.key;
          const data = childSnapshot.val();
          appendRouteRow(data, key);
        });
      });
    }

    function appendRouteRow(data, key) {
      const table = document.getElementById("routeTable");
      const row = document.createElement("tr");
      row.setAttribute("data-key", key);
      row.innerHTML = `
        <td>${data.city}</td>
        <td>${data.address}</td>
        <td>${data.busStop}</td>
        <td class="btn-group">
          <button class="btn btn-sm btn-info" onclick="openViewRouteModal('${key}')">
            <i class="fas fa-eye"></i> View
          </button>
          <button class="btn btn-sm btn-warning" onclick="openEditRouteModal('${key}')">
            <i class="fas fa-edit"></i> Edit
          </button>
          <button class="btn btn-sm btn-danger" onclick="deleteRoute(this)">
            <i class="fas fa-trash"></i> Delete
          </button>
        </td>`;
      table.appendChild(row);
    }
    
    // Validation functions
    function validateCity() {
      const cityInput = document.getElementById("city");
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
    
    function validateEditCity() {
      const cityInput = document.getElementById("editCity");
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

    // Open Add Route Modal
    function openAddRouteModal() {
      document.getElementById("addRouteModal").style.display = "block";
      document.getElementById("addRouteForm").reset();
      
      // Clear validation states
      document.getElementById("city").classList.remove("is-invalid");
    }

    // Close Add Route Modal
    function closeAddRouteModal() {
      document.getElementById("addRouteModal").style.display = "none";
    }

    // Open Edit Route Modal
    function openEditRouteModal(key) {
      document.getElementById("editRouteModal").style.display = "block";
      document.getElementById("editRouteForm").reset();
      document.getElementById("editRouteKey").value = key;
      
      // Clear validation states
      document.getElementById("editCity").classList.remove("is-invalid");
      
      // Fetch current route data
      db.ref("routes/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        document.getElementById("editCity").value = data.city || '';
        document.getElementById("editAddress").value = data.address || '';
        document.getElementById("editBusStop").value = data.busStop || '';
      });
    }

    // Close Edit Route Modal
    function closeEditRouteModal() {
      document.getElementById("editRouteModal").style.display = "none";
    }

    // Open View Route Modal
    function openViewRouteModal(key) {
      document.getElementById("viewRouteModal").style.display = "block";
      
      // Fetch current route data
      db.ref("routes/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        document.getElementById("viewCity").textContent = data.city || '';
        document.getElementById("viewAddress").textContent = data.address || '';
        document.getElementById("viewBusStop").textContent = data.busStop || '';
      });
    }

    // Close View Route Modal
    function closeViewRouteModal() {
      document.getElementById("viewRouteModal").style.display = "none";
    }

    // Validate and Save Route Data from Modal
    function validateAndSaveRouteData() {
      // Run all validations
      const cityValid = validateCity();
      
      if (!cityValid) {
        return; // Stop if any validation fails
      }
      
      saveRouteData();
    }
    
    // Save Route Data from Modal
    function saveRouteData() {
      const city = document.getElementById("city").value.trim();
      const address = document.getElementById("address").value.trim();
      const busStop = document.getElementById("busStop").value.trim();

      // Basic validation
      if (!city || !address || !busStop) {
        alert("Please fill all fields");
        return;
      }

      const routeData = {
        city: city,
        address: address,
        busStop: busStop
      };

      const newRouteRef = db.ref("routes").push();
      newRouteRef.set(routeData, function (error) {
        if (!error) {
          alert("Route saved successfully!");
          closeAddRouteModal();
          appendRouteRow(routeData, newRouteRef.key);
        } else {
          alert("Error saving route.");
        }
      });
    }

    // Validate and Update Route Data from Edit Modal
    function validateAndUpdateRouteData() {
      // Run all validations
      const cityValid = validateEditCity();
      
      if (!cityValid) {
        return; // Stop if any validation fails
      }
      
      updateRouteData();
    }
    
    // Update Route Data from Edit Modal
    function updateRouteData() {
      const key = document.getElementById("editRouteKey").value;
      const city = document.getElementById("editCity").value.trim();
      const address = document.getElementById("editAddress").value.trim();
      const busStop = document.getElementById("editBusStop").value.trim();

      // Basic validation
      if (!city || !address || !busStop) {
        alert("Please fill all fields");
        return;
      }

      const updatedData = {
        city: city,
        address: address,
        busStop: busStop
      };

      db.ref("routes/" + key).set(updatedData, function (error) {
        if (!error) {
          alert("Route updated successfully!");
          closeEditRouteModal();
          // Update the row in the table
          const row = document.querySelector(`tr[data-key="${key}"]`);
          if (row) {
            row.cells[0].textContent = city;
            row.cells[1].textContent = address;
            row.cells[2].textContent = busStop;
          } else {
            // If row not found, reload all data
            loadRouteData();
          }
        } else {
          alert("Error updating route.");
        }
      });
    }

    function deleteRoute(button) {
      if (!confirm("Are you sure you want to delete this route?")) {
        return;
      }
      
      const row = button.closest("tr");
      const key = row.getAttribute("data-key");

      if (key) {
        db.ref("routes/" + key).remove(function (error) {
          if (!error) {
            row.remove();
            alert("Route deleted successfully!");
          } else {
            alert("Error deleting from database.");
          }
        });
      } else {
        row.remove(); // Only from UI if no key exists
      }
    }

    // Close the modals when clicking outside of them
    window.onclick = function(event) {
      const addModal = document.getElementById("addRouteModal");
      const editModal = document.getElementById("editRouteModal");
      const viewModal = document.getElementById("viewRouteModal");
      
      if (event.target == addModal) {
        closeAddRouteModal();
      }
      
      if (event.target == editModal) {
        closeEditRouteModal();
      }
      
      if (event.target == viewModal) {
        closeViewRouteModal();
      }
    }
    
    // Enforce alphabetic input for City field
    document.getElementById("city").addEventListener("keypress", function(e) {
      if (!/[A-Za-z\s]/.test(e.key)) {
        e.preventDefault();
      }
    });
    
    document.getElementById("editCity").addEventListener("keypress", function(e) {
      if (!/[A-Za-z\s]/.test(e.key)) {
        e.preventDefault();
      }
    });
  </script>
</body>
</html>