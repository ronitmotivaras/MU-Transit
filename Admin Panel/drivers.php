<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MU Transit - Driver Admin Panel</title>
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
    .add-driver-btn {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    .add-driver-btn:hover {
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

  <!-- Driver List -->
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Drivers</h2>
      <button class="add-driver-btn" onclick="openAddDriverModal()">
        <i class="fas fa-plus"></i> Add Driver
      </button>
    </div>

    <table class="table table-bordered table-striped text-center">
      <thead class="table-dark">
        <tr>
          <th>Driver ID</th>
          <th>Driver Name</th>
          <th>City</th>
          <th>Address</th>
          <th>Phone No.</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="driverTable">
        <!-- Data will be dynamically loaded -->
      </tbody>
    </table>
  </div>

  <!-- Add Driver Modal -->
  <div id="addDriverModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Add New Driver</h3>
        <span class="close-btn" onclick="closeAddDriverModal()">&times;</span>
      </div>
      <form id="addDriverForm">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="driverId">Driver ID:</label>
              <input type="text" class="form-control" id="driverId" required maxlength="4" readonly>
              <small class="text-muted">Auto-generated ID</small>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="driverName">Driver Name:</label>
              <input type="text" class="form-control" id="driverName" required>
              <div class="invalid-feedback">Name must contain only alphabets.</div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="city">City:</label>
              <input type="text" class="form-control" id="city" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="phoneNo">Phone No.:</label>
              <input type="tel" class="form-control" id="phoneNo" required maxlength="10">
              <div class="invalid-feedback">Phone number must be exactly 10 digits.</div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="address">Address:</label>
              <textarea class="form-control" id="address" rows="2" required></textarea>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-secondary me-2" onclick="closeAddDriverModal()">Cancel</button>
          <button type="button" class="btn btn-success" onclick="validateAndSaveDriverData()">Save Driver</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Driver Modal -->
  <div id="editDriverModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Edit Driver</h3>
        <span class="close-btn" onclick="closeEditDriverModal()">&times;</span>
      </div>
      <form id="editDriverForm">
        <input type="hidden" id="editDriverKey">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="editDriverId">Driver ID:</label>
              <input type="text" class="form-control" id="editDriverId" required maxlength="4" readonly>
              <small class="text-muted">ID cannot be changed</small>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="editDriverName">Driver Name:</label>
              <input type="text" class="form-control" id="editDriverName" required>
              <div class="invalid-feedback">Name must contain only alphabets.</div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="editCity">City:</label>
              <input type="text" class="form-control" id="editCity" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="editPhoneNo">Phone No.:</label>
              <input type="tel" class="form-control" id="editPhoneNo" required maxlength="10">
              <div class="invalid-feedback">Phone number must be exactly 10 digits.</div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="editAddress">Address:</label>
              <textarea class="form-control" id="editAddress" rows="2" required></textarea>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-secondary me-2" onclick="closeEditDriverModal()">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="validateAndUpdateDriverData()">Update Information</button>
        </div>
      </form>
    </div>
  </div>

  <!-- View Driver Modal -->
  <div id="viewDriverModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Driver Details</h3>
        <span class="close-btn" onclick="closeViewDriverModal()">&times;</span>
      </div>
      <div class="driver-details">
        <table class="table table-bordered">
          <tr>
            <th width="30%">Driver ID</th>
            <td id="viewDriverId"></td>
          </tr>
          <tr>
            <th>Driver Name</th>
            <td id="viewDriverName"></td>
          </tr>
          <tr>
            <th>City</th>
            <td id="viewCity"></td>
          </tr>
          <tr>
            <th>Address</th>
            <td id="viewAddress"></td>
          </tr>
          <tr>
            <th>Phone No.</th>
            <td id="viewPhoneNo"></td>
          </tr>
        </table>
      </div>
      <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-secondary" onclick="closeViewDriverModal()">Close</button>
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

    // Load drivers on page load
    window.onload = function () {
      loadDriverData();
      
      // Add input validation listeners
      document.getElementById("driverName").addEventListener("input", validateDriverName);
      document.getElementById("phoneNo").addEventListener("input", validatePhoneNo);
      
      document.getElementById("editDriverName").addEventListener("input", validateEditDriverName);
      document.getElementById("editPhoneNo").addEventListener("input", validateEditPhoneNo);
    };

    function loadDriverData() {
      const table = document.getElementById("driverTable");
      table.innerHTML = '';
      db.ref("drivers").once("value", function (snapshot) {
        snapshot.forEach(function (childSnapshot) {
          const key = childSnapshot.key;
          const data = childSnapshot.val();
          appendDriverRow(data, key);
        });
      });
    }

    function appendDriverRow(data, key) {
      const table = document.getElementById("driverTable");
      const row = document.createElement("tr");
      row.setAttribute("data-key", key);
      row.innerHTML = `
        <td>${data.driverId}</td>
        <td>${data.driverName}</td>
        <td>${data.city}</td>
        <td>${data.address}</td>
        <td>${data.phoneNo}</td>
        <td class="btn-group">
          <button class="btn btn-sm btn-info" onclick="openViewDriverModal('${key}')">
            <i class="fas fa-eye"></i> View
          </button>
          <button class="btn btn-sm btn-warning" onclick="openEditDriverModal('${key}')">
            <i class="fas fa-edit"></i> Edit
          </button>
          <button class="btn btn-sm btn-danger" onclick="deleteDriver(this)">
            <i class="fas fa-trash"></i> Delete
          </button>
        </td>`;
      table.appendChild(row);
    }
    
    // Get the next driver ID
    async function getNextDriverId() {
      let highestNum = 0;
      
      try {
        const snapshot = await db.ref("drivers").once("value");
        snapshot.forEach(function(childSnapshot) {
          const data = childSnapshot.val();
          if (data.driverId && data.driverId.startsWith('D')) {
            // Extract the numeric part
            const numPart = parseInt(data.driverId.substring(1), 10);
            if (!isNaN(numPart) && numPart > highestNum) {
              highestNum = numPart;
            }
          }
        });
        
        // Increment and format with leading zeros
        highestNum++;
        return 'D' + String(highestNum).padStart(3, '0');
      } catch (error) {
        console.error("Error getting next driver ID:", error);
        return 'D001'; // Default if error occurs
      }
    }
    
    // Validation functions
    function validateDriverName() {
      const nameInput = document.getElementById("driverName");
      const value = nameInput.value.trim();
      const isValid = /^[A-Za-z\s]+$/.test(value);
      
      if (value && !isValid) {
        nameInput.classList.add("is-invalid");
        return false;
      } else {
        nameInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validatePhoneNo() {
      const phoneInput = document.getElementById("phoneNo");
      const value = phoneInput.value.trim();
      const isValid = /^\d{10}$/.test(value);
      
      if (value && !isValid) {
        phoneInput.classList.add("is-invalid");
        return false;
      } else {
        phoneInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateEditDriverName() {
      const nameInput = document.getElementById("editDriverName");
      const value = nameInput.value.trim();
      const isValid = /^[A-Za-z\s]+$/.test(value);
      
      if (value && !isValid) {
        nameInput.classList.add("is-invalid");
        return false;
      } else {
        nameInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateEditPhoneNo() {
      const phoneInput = document.getElementById("editPhoneNo");
      const value = phoneInput.value.trim();
      const isValid = /^\d{10}$/.test(value);
      
      if (value && !isValid) {
        phoneInput.classList.add("is-invalid");
        return false;
      } else {
        phoneInput.classList.remove("is-invalid");
        return true;
      }
    }

    // Open Add Driver Modal with auto-generated ID
    async function openAddDriverModal() {
      document.getElementById("addDriverModal").style.display = "block";
      document.getElementById("addDriverForm").reset();
      
      // Clear validation states
      document.getElementById("driverName").classList.remove("is-invalid");
      document.getElementById("phoneNo").classList.remove("is-invalid");
      
      // Get and set the next driver ID
      const nextDriverId = await getNextDriverId();
      document.getElementById("driverId").value = nextDriverId;
    }

    // Close Add Driver Modal
    function closeAddDriverModal() {
      document.getElementById("addDriverModal").style.display = "none";
    }

    // Open Edit Driver Modal
    function openEditDriverModal(key) {
      document.getElementById("editDriverModal").style.display = "block";
      document.getElementById("editDriverForm").reset();
      document.getElementById("editDriverKey").value = key;
      
      // Clear validation states
      document.getElementById("editDriverName").classList.remove("is-invalid");
      document.getElementById("editPhoneNo").classList.remove("is-invalid");
      
      // Fetch current driver data
      db.ref("drivers/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        document.getElementById("editDriverId").value = data.driverId || '';
        document.getElementById("editDriverName").value = data.driverName || '';
        document.getElementById("editAddress").value = data.address || '';
        document.getElementById("editCity").value = data.city || '';
        document.getElementById("editPhoneNo").value = data.phoneNo || '';
      });
    }

    // Close Edit Driver Modal
    function closeEditDriverModal() {
      document.getElementById("editDriverModal").style.display = "none";
    }

    // Open View Driver Modal
    function openViewDriverModal(key) {
      document.getElementById("viewDriverModal").style.display = "block";
      
      // Fetch current driver data
      db.ref("drivers/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        document.getElementById("viewDriverId").textContent = data.driverId || '';
        document.getElementById("viewDriverName").textContent = data.driverName || '';
        document.getElementById("viewAddress").textContent = data.address || '';
        document.getElementById("viewCity").textContent = data.city || '';
        document.getElementById("viewPhoneNo").textContent = data.phoneNo || '';
      });
    }

    // Close View Driver Modal
    function closeViewDriverModal() {
      document.getElementById("viewDriverModal").style.display = "none";
    }

    // Validate and Save Driver Data from Modal
    function validateAndSaveDriverData() {
      // Run all validations
      const nameValid = validateDriverName();
      const phoneValid = validatePhoneNo();
      
      if (!nameValid || !phoneValid) {
        return; // Stop if any validation fails
      }
      
      saveDriverData();
    }
    
    // Save Driver Data from Modal
    function saveDriverData() {
      const driverId = document.getElementById("driverId").value.trim();
      const driverName = document.getElementById("driverName").value.trim();
      const address = document.getElementById("address").value.trim();
      const city = document.getElementById("city").value.trim();
      const phoneNo = document.getElementById("phoneNo").value.trim();

      // Basic validation
      if (!driverId || !driverName || !address || !city || !phoneNo) {
        alert("Please fill all fields");
        return;
      }

      const driverData = {
        driverId: driverId,
        driverName: driverName,
        address: address,
        city: city,
        phoneNo: phoneNo
      };

      const newDriverRef = db.ref("drivers").push();
      newDriverRef.set(driverData, function (error) {
        if (!error) {
          alert("Driver saved successfully!");
          closeAddDriverModal();
          appendDriverRow(driverData, newDriverRef.key);
        } else {
          alert("Error saving driver.");
        }
      });
    }

    // Validate and Update Driver Data from Edit Modal
    function validateAndUpdateDriverData() {
      // Run all validations
      const nameValid = validateEditDriverName();
      const phoneValid = validateEditPhoneNo();
      
      if (!nameValid || !phoneValid) {
        return; // Stop if any validation fails
      }
      
      updateDriverData();
    }
    
    // Update Driver Data from Edit Modal
    function updateDriverData() {
      const key = document.getElementById("editDriverKey").value;
      const driverId = document.getElementById("editDriverId").value.trim();
      const driverName = document.getElementById("editDriverName").value.trim();
      const address = document.getElementById("editAddress").value.trim();
      const city = document.getElementById("editCity").value.trim();
      const phoneNo = document.getElementById("editPhoneNo").value.trim();

      // Basic validation
      if (!driverId || !driverName || !address || !city || !phoneNo) {
        alert("Please fill all fields");
        return;
      }

      const updatedData = {
        driverId: driverId,
        driverName: driverName,
        address: address,
        city: city,
        phoneNo: phoneNo
      };

      db.ref("drivers/" + key).set(updatedData, function (error) {
        if (!error) {
          alert("Driver updated successfully!");
          closeEditDriverModal();
          // Update the row in the table
          const row = document.querySelector(`tr[data-key="${key}"]`);
          if (row) {
            row.cells[0].textContent = driverId;
            row.cells[1].textContent = driverName;
            row.cells[2].textContent = city;
            row.cells[3].textContent = address;
            row.cells[4].textContent = phoneNo;
          } else {
            // If row not found, reload all data
            loadDriverData();
          }
        } else {
          alert("Error updating driver.");
        }
      });
    }

    function deleteDriver(button) {
      if (!confirm("Are you sure you want to delete this driver?")) {
        return;
      }
      
      const row = button.closest("tr");
      const key = row.getAttribute("data-key");

      if (key) {
        db.ref("drivers/" + key).remove(function (error) {
          if (!error) {
            row.remove();
            alert("Driver deleted successfully!");
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
      const addModal = document.getElementById("addDriverModal");
      const editModal = document.getElementById("editDriverModal");
      const viewModal = document.getElementById("viewDriverModal");
      
      if (event.target == addModal) {
        closeAddDriverModal();
      }
      
      if (event.target == editModal) {
        closeEditDriverModal();
      }
      
      if (event.target == viewModal) {
        closeViewDriverModal();
      }
    }
    
    // Allow only numbers for phone number input
    document.getElementById("phoneNo").addEventListener("keypress", function(e) {
      if (!/\d/.test(e.key)) {
        e.preventDefault();
      }
    });
    
    document.getElementById("editPhoneNo").addEventListener("keypress", function(e) {
      if (!/\d/.test(e.key)) {
        e.preventDefault();
      }
    });
  </script>
</body>
</html>