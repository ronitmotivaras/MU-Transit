<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MU Transit - Admin Panel</title>
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
    .add-bus-btn {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    .add-bus-btn:hover {
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
      margin: 10% auto;
      padding: 20px;
      border-radius: 5px;
      width: 60%;
      max-width: 500px;
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
    .view-modal .modal-content {
      max-width: 700px;
    }
    .detail-item {
      margin-bottom: 15px;
    }
    .detail-label {
      font-weight: bold;
    }
  </style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Sidebar -->
  <?php include('sidenavbar.php')?>

  <!-- Bus List -->
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Buses</h2>
      <button class="add-bus-btn" onclick="openAddBusModal()">
        <i class="fas fa-plus"></i> Add Bus
      </button>
    </div>

    <table class="table table-bordered table-striped text-center">
      <thead class="table-dark">
        <tr>
          <th>Sr No.</th>
          <th>Bus No.</th>
          <th>City</th>
          <th>Route</th>
          <th>Seating Capacity</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="busTable">
        <!-- Data will be dynamically loaded -->
      </tbody>
    </table>
  </div>

  <!-- Add Bus Modal -->
  <div id="addBusModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Add New Bus</h3>
        <span class="close-btn" onclick="closeAddBusModal()">&times;</span>
      </div>
      <form id="addBusForm">
        <div class="form-group">
          <label for="busNum">Bus Number:</label>
          <input type="text" class="form-control" id="busNum" required>
        </div>
        <div class="form-group">
          <label for="city">City:</label>
          <select class="form-control" id="city" onchange="updateRouteDropdown('city', 'route')" required>
            <option value="">Select City</option>
            <!-- Cities will be loaded dynamically -->
          </select>
        </div>
        <div class="form-group">
          <label for="route">Route:</label>
          <select class="form-control" id="route" required>
            <option value="">Select Route</option>
            <!-- Routes will be loaded dynamically based on selected city -->
          </select>
        </div>
        <div class="form-group">
          <label for="seatingCapacity">Seating Capacity:</label>
          <input type="number" class="form-control" id="seatingCapacity" min="1" required>
        </div>
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-secondary me-2" onclick="closeAddBusModal()">Cancel</button>
          <button type="button" class="btn btn-success" onclick="saveBusData()">Save Bus</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Bus Modal -->
  <div id="editBusModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Edit Bus</h3>
        <span class="close-btn" onclick="closeEditBusModal()">&times;</span>
      </div>
      <form id="editBusForm">
        <input type="hidden" id="editBusKey">
        <input type="hidden" id="originalBusNum">
        <div class="form-group">
          <label for="editBusNum">Bus Number:</label>
          <input type="text" class="form-control" id="editBusNum" required>
        </div>
        <div class="form-group">
          <label for="editCity">City:</label>
          <select class="form-control" id="editCity" onchange="updateRouteDropdown('editCity', 'editRoute')" required>
            <option value="">Select City</option>
            <!-- Cities will be loaded dynamically -->
          </select>
        </div>
        <div class="form-group">
          <label for="editRoute">Route:</label>
          <select class="form-control" id="editRoute" required>
            <option value="">Select Route</option>
            <!-- Routes will be loaded dynamically based on selected city -->
          </select>
        </div>
        <div class="form-group">
          <label for="editSeatingCapacity">Seating Capacity:</label>
          <input type="number" class="form-control" id="editSeatingCapacity" min="1" required>
        </div>
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-secondary me-2" onclick="closeEditBusModal()">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="updateBusData()">Update Bus</button>
        </div>
      </form>
    </div>
  </div>

  <!-- View Bus Modal -->
  <div id="viewBusModal" class="modal view-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Bus Details</h3>
        <span class="close-btn" onclick="closeViewBusModal()">&times;</span>
      </div>
      <div id="busDetails" class="p-3">
        <!-- Bus details will be shown here -->
      </div>
      <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-secondary" onclick="closeViewBusModal()">Close</button>
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

    // Available cities from routes
    let availableCities = [];
    let existingBusNumbers = {};
    let routesByCity = {}; // Store routes organized by city

    // Load buses and route data on page load
    window.onload = function () {
      loadRouteData();
      loadBusData();
    };

    function loadRouteData() {
      db.ref("routes").once("value", function (snapshot) {
        availableCities = [];
        routesByCity = {};
        
        snapshot.forEach(function (childSnapshot) {
          const key = childSnapshot.key;
          const data = childSnapshot.val();
          
          // Extract city
          if (data.city) {
            if (!availableCities.includes(data.city)) {
              availableCities.push(data.city);
              routesByCity[data.city] = [];
            }
            
            // Store route info
            routesByCity[data.city].push({
              key: key,
              name: data.routeName || data.name || key
            });
          }
        });
        
        // Populate city dropdowns
        populateCityDropdowns();
      });
    }

    function populateCityDropdowns() {
      const cityDropdown = document.getElementById("city");
      const editCityDropdown = document.getElementById("editCity");
      
      // Clear existing options
      cityDropdown.innerHTML = '<option value="">Select City</option>';
      editCityDropdown.innerHTML = '<option value="">Select City</option>';
      
      // Add new options
      availableCities.forEach(city => {
        cityDropdown.innerHTML += `<option value="${city}">${city}</option>`;
        editCityDropdown.innerHTML += `<option value="${city}">${city}</option>`;
      });
    }

    function updateRouteDropdown(citySelectId, routeSelectId) {
      const citySelect = document.getElementById(citySelectId);
      const routeSelect = document.getElementById(routeSelectId);
      const selectedCity = citySelect.value;
      
      // Clear existing options
      routeSelect.innerHTML = '<option value="">Select Route</option>';
      
      // If city is selected and routes exist for that city
      if (selectedCity && routesByCity[selectedCity]) {
        // Add route options
        routesByCity[selectedCity].forEach(route => {
          routeSelect.innerHTML += `<option value="${route.key}">${route.name}</option>`;
        });
      }
    }

    function loadBusData() {
      const table = document.getElementById("busTable");
      table.innerHTML = '';
      let srNo = 1;
      existingBusNumbers = {};
      
      db.ref("buses").once("value", function (snapshot) {
        snapshot.forEach(function (childSnapshot) {
          const key = childSnapshot.key;
          const data = childSnapshot.val();
          
          // Store bus number to prevent duplicates
          const busNumber = data.busNum || data.busNo;
          if (busNumber) {
            existingBusNumbers[busNumber] = key;
          }
          
          appendBusRow(data, key, srNo++);
        });
      });
    }

    function appendBusRow(data, key, srNo) {
      const table = document.getElementById("busTable");
      const row = document.createElement("tr");
      row.setAttribute("data-key", key);
      row.innerHTML = `
        <td>${srNo}</td>
        <td>${data.busNum || data.busNo}</td>
        <td>${data.city || '-'}</td>
        <td>${data.routeName || (data.routeKey ? getRouteNameByKey(data.routeKey) : '-')}</td>
        <td>${data.seatingCapacity || '-'}</td>
        <td class="btn-group">
          <button class="btn btn-sm btn-info" onclick="openViewBusModal('${key}')">
            <i class="fas fa-eye"></i> View
          </button>
          <button class="btn btn-sm btn-warning" onclick="openEditBusModal('${key}')">
            <i class="fas fa-edit"></i> Edit
          </button>
          <button class="btn btn-sm btn-danger" onclick="deleteRow(this)">
            <i class="fas fa-trash"></i> Delete
          </button>
        </td>`;
      table.appendChild(row);
    }

    function getRouteNameByKey(routeKey) {
      // Search through all cities and their routes
      for (const city in routesByCity) {
        for (const route of routesByCity[city]) {
          if (route.key === routeKey) {
            return route.name;
          }
        }
      }
      return routeKey; // Return key if name not found
    }

    // Open Add Bus Modal
    function openAddBusModal() {
      document.getElementById("addBusModal").style.display = "block";
      document.getElementById("addBusForm").reset();
      // Reset route dropdown
      document.getElementById("route").innerHTML = '<option value="">Select Route</option>';
    }

    // Close Add Bus Modal
    function closeAddBusModal() {
      document.getElementById("addBusModal").style.display = "none";
    }

    // Open Edit Bus Modal
    function openEditBusModal(key) {
      document.getElementById("editBusModal").style.display = "block";
      document.getElementById("editBusForm").reset();
      document.getElementById("editBusKey").value = key;
      
      // Reset route dropdown
      document.getElementById("editRoute").innerHTML = '<option value="">Select Route</option>';
      
      // Fetch current bus data
      db.ref("buses/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        const busNum = data.busNum || data.busNo || '';
        
        document.getElementById("editBusNum").value = busNum;
        document.getElementById("originalBusNum").value = busNum; // Store original for comparison during update
        document.getElementById("editSeatingCapacity").value = data.seatingCapacity || '';
        
        if (data.city) {
          document.getElementById("editCity").value = data.city;
          // Update route dropdown based on selected city
          updateRouteDropdown('editCity', 'editRoute');
          
          // Select the current route if available
          if (data.routeKey) {
            setTimeout(() => {
              document.getElementById("editRoute").value = data.routeKey;
            }, 100); // Small delay to ensure dropdown is populated
          }
        }
      });
    }

    // Close Edit Bus Modal
    function closeEditBusModal() {
      document.getElementById("editBusModal").style.display = "none";
    }

    // Open View Bus Modal
    function openViewBusModal(key) {
      document.getElementById("viewBusModal").style.display = "block";
      const detailsContainer = document.getElementById("busDetails");
      detailsContainer.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
      
      // Fetch bus data
      db.ref("buses/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        const routeName = data.routeName || (data.routeKey ? getRouteNameByKey(data.routeKey) : '-');
        
        detailsContainer.innerHTML = `
          <div class="row">
            <div class="col-md-6">
              <div class="detail-item">
                <div class="detail-label">Bus Number:</div>
                <div>${data.busNum || data.busNo || '-'}</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">City:</div>
                <div>${data.city || '-'}</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Route:</div>
                <div>${routeName}</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Seating Capacity:</div>
                <div>${data.seatingCapacity || '-'}</div>
              </div>
            </div>
          </div>
        `;
      });
    }

    // Close View Bus Modal
    function closeViewBusModal() {
      document.getElementById("viewBusModal").style.display = "none";
    }

    // Check if bus number already exists
    function checkBusNumberExists(busNum, originalBusNum = null) {
      // If this is an edit and the bus number hasn't changed, it's OK
      if (originalBusNum && busNum === originalBusNum) {
        return false;
      }
      
      return existingBusNumbers.hasOwnProperty(busNum);
    }

    // Save Bus Data from Modal
    function saveBusData() {
      const busNum = document.getElementById("busNum").value.trim();
      const city = document.getElementById("city").value.trim();
      const routeKey = document.getElementById("route").value.trim();
      const seatingCapacity = document.getElementById("seatingCapacity").value.trim();

      // Basic validation
      if (!busNum || !city || !routeKey || !seatingCapacity) {
        alert("Please fill all required fields");
        return;
      }
      
      // Check if bus number already exists
      if (checkBusNumberExists(busNum)) {
        alert("Bus number already exists. Please use a unique bus number.");
        return;
      }

      // Get route name for display
      const routeName = getRouteNameByKey(routeKey);

      const busData = {
        busNum: busNum,
        city: city,
        routeKey: routeKey,
        routeName: routeName,
        seatingCapacity: parseInt(seatingCapacity)
      };

      // Use the bus number as the key to prevent duplicates
      const newBusRef = db.ref("buses").child(busNum.replace(/[.#$\/\[\]]/g, "_"));
      newBusRef.set(busData, function (error) {
        if (!error) {
          alert("Bus saved successfully!");
          closeAddBusModal();
          loadBusData(); // Reload all data to ensure correct Sr No.
        } else {
          alert("Error saving bus: " + error.message);
        }
      });
    }

    // Update Bus Data from Edit Modal
    function updateBusData() {
      const key = document.getElementById("editBusKey").value;
      const originalBusNum = document.getElementById("originalBusNum").value;
      const busNum = document.getElementById("editBusNum").value.trim();
      const city = document.getElementById("editCity").value.trim();
      const routeKey = document.getElementById("editRoute").value.trim();
      const seatingCapacity = document.getElementById("editSeatingCapacity").value.trim();

      // Basic validation
      if (!busNum || !city || !routeKey || !seatingCapacity) {
        alert("Please fill all required fields");
        return;
      }
      
      // Check if bus number already exists (and it's not this record)
      if (checkBusNumberExists(busNum, originalBusNum)) {
        alert("Bus number already exists. Please use a unique bus number.");
        return;
      }

      // Get route name for display
      const routeName = getRouteNameByKey(routeKey);

      const updatedData = {
        busNum: busNum,
        city: city,
        routeKey: routeKey,
        routeName: routeName,
        seatingCapacity: parseInt(seatingCapacity)
      };

      // If the bus number is changed, we need to delete the old record and create a new one
      if (busNum !== originalBusNum) {
        // First create the new record
        const newKey = busNum.replace(/[.#$\/\[\]]/g, "_");
        db.ref("buses/" + newKey).set(updatedData, function (error) {
          if (!error) {
            // Then delete the old record
            db.ref("buses/" + key).remove(function (error) {
              if (!error) {
                alert("Bus updated successfully!");
                closeEditBusModal();
                loadBusData(); // Reload all data
              } else {
                alert("Error removing old record: " + error.message);
              }
            });
          } else {
            alert("Error updating bus: " + error.message);
          }
        });
      } else {
        // If bus number hasn't changed, just update the existing record
        db.ref("buses/" + key).set(updatedData, function (error) {
          if (!error) {
            alert("Bus updated successfully!");
            closeEditBusModal();
            loadBusData(); // Reload all data
          } else {
            alert("Error updating bus: " + error.message);
          }
        });
      }
    }

    function deleteRow(button) {
      if (!confirm("Are you sure you want to delete this bus?")) {
        return;
      }
      
      const row = button.closest("tr");
      const key = row.getAttribute("data-key");

      if (key) {
        db.ref("buses/" + key).remove(function (error) {
          if (!error) {
            loadBusData(); // Reload all data to ensure correct Sr No.
            alert("Bus deleted successfully!");
          } else {
            alert("Error deleting from database: " + error.message);
          }
        });
      } else {
        row.remove(); // Only from UI if no key exists
      }
    }

    // Close the modals when clicking outside of them
    window.onclick = function(event) {
      const addModal = document.getElementById("addBusModal");
      const editModal = document.getElementById("editBusModal");
      const viewModal = document.getElementById("viewBusModal");
      
      if (event.target == addModal) {
        closeAddBusModal();
      }
      
      if (event.target == editModal) {
        closeEditBusModal();
      }
      
      if (event.target == viewModal) {
        closeViewBusModal();
      }
    }
  </script>
</body>
</html>