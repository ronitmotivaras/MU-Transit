<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MU Transit - Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
    /* Custom styles for select2 */
    .select2-container {
      width: 100% !important;
    }
    .select2-selection--multiple {
      min-height: 38px !important;
      border: 1px solid #ced4da !important;
    }
    .routes-section {
      margin-top: 15px;
      display: none;
    }
    .route-badges {
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
      margin-top: 10px;
    }
    .route-badge {
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 4px;
      padding: 3px 8px;
      font-size: 14px;
    }
  </style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
          <th>Routes</th>
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
          <select class="form-control" id="city" required onchange="loadRoutesByCity()">
            <option value="">Select City</option>
            <!-- Cities will be loaded dynamically -->
          </select>
        </div>
        <div class="form-group routes-section" id="routesSection">
          <label for="routes">Routes:</label>
          <select class="form-control" id="routes" multiple="multiple">
            <!-- Routes will be loaded dynamically based on city -->
          </select>
          <small class="text-muted">You can select multiple routes</small>
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
          <select class="form-control" id="editCity" required onchange="loadEditRoutesByCity()">
            <option value="">Select City</option>
            <!-- Cities will be loaded dynamically -->
          </select>
        </div>
        <div class="form-group routes-section" id="editRoutesSection">
          <label for="editRoutes">Routes:</label>
          <select class="form-control" id="editRoutes" multiple="multiple">
            <!-- Routes will be loaded dynamically based on city -->
          </select>
          <small class="text-muted">You can select multiple routes</small>
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

    // Store cities data
    let citiesList = [];
    let existingBusNumbers = {};
    let routesByCityId = {};
    let routesData = {};

    // Log for debugging
    function logData(message, data) {
      console.log(message, data);
    }

    // Load data on page load
    window.onload = function () {
      loadCities();
      loadRoutes();
      loadBusData();
      
      // Initialize select2 for route selections
      $(document).ready(function() {
        $('#routes').select2({
          placeholder: "Select Routes",
          allowClear: true
        });
        
        $('#editRoutes').select2({
          placeholder: "Select Routes",
          allowClear: true
        });
      });
    };

    // Load all routes data for reference
    function loadRoutes() {
      routesByCityId = {};
      routesData = {};
      
      db.ref("routes").once("value", function (snapshot) {
        if (snapshot.exists()) {
          snapshot.forEach(function (childSnapshot) {
            const key = childSnapshot.key;
            const data = childSnapshot.val();
            
            // Store route data for reference
            routesData[key] = data;
            
            // Group routes by city ID
            const cityId = data.cityId;
            if (cityId) {
              if (!routesByCityId[cityId]) {
                routesByCityId[cityId] = [];
              }
              routesByCityId[cityId].push({
                key: key,
                route: data.route,
                address: data.address,
                time: data.time,
                travelTime: data.travelTime
              });
            }
          });
        }
        
        logData("Routes loaded by city:", routesByCityId);
      }).catch(error => {
        console.error("Error loading routes:", error);
      });
    }

    // Load routes by selected city for Add Bus Modal
    function loadRoutesByCity() {
      const citySelect = document.getElementById("city");
      const cityId = citySelect.value;
      const routesSection = document.getElementById("routesSection");
      const routesSelect = document.getElementById("routes");
      
      // Clear routes
      $('#routes').empty().trigger('change');
      
      if (!cityId) {
        routesSection.style.display = "none";
        return;
      }
      
      routesSection.style.display = "block";
      
      // Get routes for this city
      const cityRoutes = routesByCityId[cityId] || [];
      
      // Add options to select
      cityRoutes.forEach(route => {
        const option = new Option(route.route, route.key, false, false);
        $('#routes').append(option);
      });
      
      $('#routes').trigger('change');
    }

    // Load routes by selected city for Edit Bus Modal
    function loadEditRoutesByCity() {
      const citySelect = document.getElementById("editCity");
      const cityId = citySelect.value;
      const routesSection = document.getElementById("editRoutesSection");
      
      // Clear routes
      $('#editRoutes').empty().trigger('change');
      
      if (!cityId) {
        routesSection.style.display = "none";
        return;
      }
      
      routesSection.style.display = "block";
      
      // Get routes for this city
      const cityRoutes = routesByCityId[cityId] || [];
      
      // Add options to select
      cityRoutes.forEach(route => {
        const option = new Option(route.route, route.key, false, false);
        $('#editRoutes').append(option);
      });
      
      $('#editRoutes').trigger('change');
    }

    // Load cities from the cities database
    function loadCities() {
      db.ref("cities").once("value", function (snapshot) {
        citiesList = [];
        
        logData("Cities snapshot:", snapshot.val());
        
        if (snapshot.exists()) {
          snapshot.forEach(function (childSnapshot) {
            const key = childSnapshot.key;
            const data = childSnapshot.val();
            
            logData(`City data for ${key}:`, data);
            
            // Add city to list based on different possible data structures
            if (typeof data === 'object' && data !== null) {
              // If data is an object with name field
              if (data.name) {
                citiesList.push({
                  key: key,
                  name: data.name
                });
              } else {
                // Use the key as name if no name field
                citiesList.push({
                  key: key,
                  name: key
                });
              }
            } else if (typeof data === 'string') {
              // If data is stored as string value
              citiesList.push({
                key: key,
                name: data
              });
            } else {
              // Fallback to using key as name
              citiesList.push({
                key: key,
                name: key
              });
            }
          });
        } else {
          console.log("No cities found in database");
        }
        
        logData("Processed cities list:", citiesList);
        
        // Populate city dropdowns
        populateCityDropdowns();
      }).catch(error => {
        console.error("Error loading cities:", error);
      });
    }

    // Populate city dropdowns with data
    function populateCityDropdowns() {
      const cityDropdown = document.getElementById("city");
      const editCityDropdown = document.getElementById("editCity");
      
      // Clear existing options
      cityDropdown.innerHTML = '<option value="">Select City</option>';
      editCityDropdown.innerHTML = '<option value="">Select City</option>';
      
      // Add new options sorted alphabetically
      citiesList.sort((a, b) => a.name.localeCompare(b.name));
      
      citiesList.forEach(city => {
        cityDropdown.innerHTML += `<option value="${city.key}">${city.name}</option>`;
        editCityDropdown.innerHTML += `<option value="${city.key}">${city.name}</option>`;
      });
      
      logData("City dropdowns populated with:", citiesList.map(c => c.name));
    }

    function loadBusData() {
      const table = document.getElementById("busTable");
      table.innerHTML = '';
      let srNo = 1;
      existingBusNumbers = {};
      
      db.ref("buses").once("value", function (snapshot) {
        if (snapshot.exists()) {
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
        } else {
          table.innerHTML = '<tr><td colspan="6" class="text-center">No buses found</td></tr>';
          console.log("No buses found in database");
        }
      }).catch(error => {
        console.error("Error loading buses:", error);
        table.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error loading buses</td></tr>';
      });
    }

    function getRouteNamesFromIds(routeIds) {
      if (!routeIds || !Array.isArray(routeIds) || routeIds.length === 0) {
        return 'None';
      }
      
      // Get route names from route IDs
      const routeNames = routeIds.map(id => {
        const routeData = routesData[id];
        return routeData ? routeData.route : 'Unknown';
      }).filter(name => name !== 'Unknown');
      
      return routeNames.length > 0 ? routeNames.join(', ') : 'None';
    }

    function appendBusRow(data, key, srNo) {
      const table = document.getElementById("busTable");
      const row = document.createElement("tr");
      row.setAttribute("data-key", key);
      
      // Get routes display text
      const routesDisplay = getRouteNamesFromIds(data.routes);
      
      row.innerHTML = `
        <td>${srNo}</td>
        <td>${data.busNum || data.busNo}</td>
        <td>${data.cityName || data.city || '-'}</td>
        <td>${routesDisplay}</td>
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

    // Open Add Bus Modal
    function openAddBusModal() {
      document.getElementById("addBusModal").style.display = "block";
      document.getElementById("addBusForm").reset();
      document.getElementById("routesSection").style.display = "none";
      $('#routes').empty().trigger('change');
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
      document.getElementById("editRoutesSection").style.display = "none";
      $('#editRoutes').empty().trigger('change');
      
      // Fetch current bus data
      db.ref("buses/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        const busNum = data.busNum || data.busNo || '';
        
        document.getElementById("editBusNum").value = busNum;
        document.getElementById("originalBusNum").value = busNum; // Store original for comparison during update
        document.getElementById("editSeatingCapacity").value = data.seatingCapacity || '';
        
        // Set city value
        const cityKey = data.cityId || null;
        if (cityKey) {
          document.getElementById("editCity").value = cityKey;
          
          // Show routes section
          document.getElementById("editRoutesSection").style.display = "block";
          
          // Load routes for this city
          const cityRoutes = routesByCityId[cityKey] || [];
          cityRoutes.forEach(route => {
            const option = new Option(route.route, route.key, false, false);
            $('#editRoutes').append(option);
          });
          
          // Select current routes
          if (data.routes && Array.isArray(data.routes)) {
            $('#editRoutes').val(data.routes).trigger('change');
          }
        } else if (data.city) {
          // If no cityId but has city name, try to find matching city
          const cityObj = citiesList.find(c => c.name === data.city);
          if (cityObj) {
            document.getElementById("editCity").value = cityObj.key;
            loadEditRoutesByCity();
          }
        }
      }).catch(error => {
        console.error("Error loading bus for edit:", error);
        alert("Error loading bus details. Please try again.");
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
        if (snapshot.exists()) {
          const data = snapshot.val();
          
          // Get city name
          const cityName = data.cityName || data.city || '-';
          
          // Get route information
          let routesHtml = '<div>None</div>';
          if (data.routes && Array.isArray(data.routes) && data.routes.length > 0) {
            routesHtml = '<div class="route-badges">';
            
            data.routes.forEach(routeId => {
              const routeData = routesData[routeId];
              if (routeData) {
                routesHtml += `
                  <div class="route-badge">
                    ${routeData.route} (${routeData.time})
                  </div>
                `;
              }
            });
            
            routesHtml += '</div>';
          }
          
          detailsContainer.innerHTML = `
            <div class="row">
              <div class="col-md-6">
                <div class="detail-item">
                  <div class="detail-label">Bus Number:</div>
                  <div>${data.busNum || data.busNo || '-'}</div>
                </div>
                <div class="detail-item">
                  <div class="detail-label">City:</div>
                  <div>${cityName}</div>
                </div>
                <div class="detail-item">
                  <div class="detail-label">Seating Capacity:</div>
                  <div>${data.seatingCapacity || '-'}</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="detail-item">
                  <div class="detail-label">Routes:</div>
                  ${routesHtml}
                </div>
              </div>
            </div>
          `;
        } else {
          detailsContainer.innerHTML = '<div class="alert alert-warning">Bus details not found</div>';
        }
      }).catch(error => {
        console.error("Error loading bus details:", error);
        detailsContainer.innerHTML = '<div class="alert alert-danger">Error loading bus details</div>';
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
      const citySelect = document.getElementById("city");
      const cityId = citySelect.value.trim();
      const cityName = citySelect.options[citySelect.selectedIndex].text.trim();
      const seatingCapacity = document.getElementById("seatingCapacity").value.trim();
      const selectedRoutes = $('#routes').val() || [];

      // Basic validation
      if (!busNum || !cityId || !seatingCapacity) {
        alert("Please fill all required fields");
        return;
      }
      
      // Check if bus number already exists
      if (checkBusNumberExists(busNum)) {
        alert("Bus number already exists. Please use a unique bus number.");
        return;
      }

      const busData = {
        busNum: busNum,
        cityId: cityId,
        cityName: cityName,
        seatingCapacity: parseInt(seatingCapacity),
        routes: selectedRoutes
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
      const citySelect = document.getElementById("editCity");
      const cityId = citySelect.value.trim();
      const cityName = citySelect.options[citySelect.selectedIndex].text.trim();
      const seatingCapacity = document.getElementById("editSeatingCapacity").value.trim();
      const selectedRoutes = $('#editRoutes').val() || [];

      // Basic validation
      if (!busNum || !cityId || !seatingCapacity) {
        alert("Please fill all required fields");
        return;
      }
      
      // Check if bus number already exists (and it's not this record)
      if (checkBusNumberExists(busNum, originalBusNum)) {
        alert("Bus number already exists. Please use a unique bus number.");
        return;
      }

      const updatedData = {
        busNum: busNum,
        cityId: cityId,
        cityName: cityName,
        seatingCapacity: parseInt(seatingCapacity),
        routes: selectedRoutes
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