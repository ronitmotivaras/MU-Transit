<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MU Transit - Student Admin Panel</title>
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
    .add-student-btn {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    .add-student-btn:hover {
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

  <!-- Student List -->
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Students</h2>
      <button class="add-student-btn" onclick="openAddStudentModal()">
        <i class="fas fa-plus"></i> Add Student
      </button>
    </div>

    <table class="table table-bordered table-striped text-center">
      <thead class="table-dark">
        <tr>
          <th>Sr No.</th>
          <th>GR No.</th>
          <th>Name</th>
          <th>Stream</th>
          <th>City</th>
          <th>Fees Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="studentTable">
        <!-- Data will be dynamically loaded -->
      </tbody>
    </table>
  </div>

  <!-- Add Student Modal -->
  <div id="addStudentModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Add New Student</h3>
        <span class="close-btn" onclick="closeAddStudentModal()">&times;</span>
      </div>
      <form id="addStudentForm">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="grNo">GR No.:</label>
              <input type="text" class="form-control" id="grNo" required maxlength="6">
              <div class="invalid-feedback">GR No. must be 6 digits only.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="name">Name:</label>
              <input type="text" class="form-control" id="name" required>
              <div class="invalid-feedback">Name must contain only alphabets.</div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="stream">Stream:</label>
              <select class="form-control" id="stream" required onchange="handleStreamChange()">
                <option value="">Select Stream</option>
                <!-- Stream options will be loaded dynamically -->
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="department">Department:</label>
              <select class="form-control" id="department" disabled>
                <option value="">Select Department</option>
                <!-- Department options will be loaded dynamically -->
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="phoneNo">Phone No.:</label>
              <input type="tel" class="form-control" id="phoneNo" required maxlength="10">
              <div class="invalid-feedback">Phone number must be exactly 10 digits.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="city">City:</label>
              <select class="form-control" id="city" required onchange="loadRoutesByCity()">
                <option value="">Select City</option>
                <!-- City options will be loaded dynamically -->
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="route">Route:</label>
              <select class="form-control" id="route" required>
                <option value="">Select Route</option>
                <!-- Route options will be loaded based on city -->
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="address">Address:</label>
              <textarea class="form-control" id="address" rows="2" required></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="feesStatus">Fees Status:</label>
              <select class="form-control" id="feesStatus" required>
                <option value="Pending">Pending</option>
                <option value="Partial">Partial</option>
              </select>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-secondary me-2" onclick="closeAddStudentModal()">Cancel</button>
          <button type="button" class="btn btn-success" onclick="validateAndSaveStudentData()">Save Student</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Student Modal -->
  <div id="editStudentModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Edit Student</h3>
        <span class="close-btn" onclick="closeEditStudentModal()">&times;</span>
      </div>
      <form id="editStudentForm">
        <input type="hidden" id="editStudentKey">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="editGrNo">GR No.:</label>
              <input type="text" class="form-control" id="editGrNo" required maxlength="6">
              <div class="invalid-feedback">GR No. must be 6 digits only.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="editName">Name:</label>
              <input type="text" class="form-control" id="editName" required>
              <div class="invalid-feedback">Name must contain only alphabets.</div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="editStream">Stream:</label>
              <select class="form-control" id="editStream" required onchange="handleEditStreamChange()">
                <option value="">Select Stream</option>
                <!-- Stream options will be loaded dynamically -->
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="editDepartment">Department:</label>
              <select class="form-control" id="editDepartment" disabled>
                <option value="">Select Department</option>
                <!-- Department options will be loaded dynamically -->
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="editPhoneNo">Phone No.:</label>
              <input type="tel" class="form-control" id="editPhoneNo" required maxlength="10">
              <div class="invalid-feedback">Phone number must be exactly 10 digits.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="editCity">City:</label>
              <select class="form-control" id="editCity" required onchange="loadEditRoutesByCity()">
                <option value="">Select City</option>
                <!-- City options will be loaded dynamically -->
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="editRoute">Route:</label>
              <select class="form-control" id="editRoute" required>
                <option value="">Select Route</option>
                <!-- Route options will be loaded based on city -->
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="editAddress">Address:</label>
              <textarea class="form-control" id="editAddress" rows="2" required></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="editFeesStatus">Fees Status:</label>
              <select class="form-control" id="editFeesStatus" required>
                <option value="Paid">Paid</option>
                <option value="Pending">Pending</option>
                <option value="Partial">Partial</option>
              </select>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-secondary me-2" onclick="closeEditStudentModal()">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="validateAndUpdateStudentData()">Update Information</button>
        </div>
      </form>
    </div>
  </div>

  <!-- View Student Modal -->
  <div id="viewStudentModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Student Details</h3>
        <span class="close-btn" onclick="closeViewStudentModal()">&times;</span>
      </div>
      <div class="student-details">
        <table class="table table-bordered">
          <tr>
            <th width="30%">GR No.</th>
            <td id="viewGrNo"></td>
          </tr>
          <tr>
            <th>Name</th>
            <td id="viewName"></td>
          </tr>
          <tr>
            <th>Stream</th>
            <td id="viewStream"></td>
          </tr>
          <tr>
            <th>Department</th>
            <td id="viewDepartment"></td>
          </tr>
          <tr>
            <th>Address</th>
            <td id="viewAddress"></td>
          </tr>
          <tr>
            <th>City</th>
            <td id="viewCity"></td>
          </tr>
          <tr>
            <th>Route</th>
            <td id="viewRoute"></td>
          </tr>
          <tr>
            <th>Phone No.</th>
            <td id="viewPhoneNo"></td>
          </tr>
          <tr>
            <th>Fees Status</th>
            <td id="viewFeesStatus"></td>
          </tr>
        </table>
      </div>
      <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-secondary" onclick="closeViewStudentModal()">Close</button>
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

    // Store data for relationships
    let streams = [];
    let departments = {};
    let cities = [];
    let routes = [];
    let cityRoutes = {};

    // Load students on page load
    window.onload = function () {
      loadStudentData();
      loadStreams();
      loadCities();
      loadRoutes();
      
      // Add input validation listeners
      document.getElementById("grNo").addEventListener("input", validateGrNo);
      document.getElementById("name").addEventListener("input", validateName);
      document.getElementById("phoneNo").addEventListener("input", validatePhoneNo);
      
      document.getElementById("editGrNo").addEventListener("input", validateEditGrNo);
      document.getElementById("editName").addEventListener("input", validateEditName);
      document.getElementById("editPhoneNo").addEventListener("input", validateEditPhoneNo);
    };

    // Load Stream data from database
    function loadStreams() {
      db.ref("streams").once("value", function(snapshot) {
        const streamSelect = document.getElementById("stream");
        const editStreamSelect = document.getElementById("editStream");
        
        // Clear existing options except the first one
        streamSelect.innerHTML = '<option value="">Select Stream</option>';
        editStreamSelect.innerHTML = '<option value="">Select Stream</option>';
        
        streams = [];
        snapshot.forEach(function(childSnapshot) {
          const streamData = childSnapshot.val();
          const streamId = childSnapshot.key;
          
          // Store stream data
          streams.push({
            id: streamId,
            name: streamData.name,
            hasDepartments: streamData.hasDepartments || false,
            departments: streamData.departments || []
          });
          
          // Add to dropdown
          const option = document.createElement("option");
          option.value = streamId;
          option.textContent = streamData.name;
          streamSelect.appendChild(option);
          
          // Add to edit dropdown
          const editOption = document.createElement("option");
          editOption.value = streamId;
          editOption.textContent = streamData.name;
          editStreamSelect.appendChild(editOption);
          
          // Store departments for later use
          if (streamData.departments) {
            departments[streamId] = streamData.departments;
          }
        });
      });
    }
    
    // Handle stream change to enable/disable department selection
    function handleStreamChange() {
      const streamSelect = document.getElementById("stream");
      const departmentSelect = document.getElementById("department");
      const selectedStreamId = streamSelect.value;
      
      departmentSelect.innerHTML = '<option value="">Select Department</option>';
      
      if (!selectedStreamId) {
        departmentSelect.disabled = true;
        return;
      }
      
      // Find selected stream
      const selectedStream = streams.find(stream => stream.id === selectedStreamId);
      
      if (selectedStream && selectedStream.hasDepartments && selectedStream.departments) {
        // Enable department selection and populate options
        departmentSelect.disabled = false;
        
        // Add department options
        selectedStream.departments.forEach(dept => {
          const option = document.createElement("option");
          option.value = dept.id;
          option.textContent = dept.name;
          departmentSelect.appendChild(option);
        });
      } else {
        departmentSelect.disabled = true;
      }
    }
    
    // Handle stream change in edit modal
    function handleEditStreamChange() {
      const streamSelect = document.getElementById("editStream");
      const departmentSelect = document.getElementById("editDepartment");
      const selectedStreamId = streamSelect.value;
      
      departmentSelect.innerHTML = '<option value="">Select Department</option>';
      
      if (!selectedStreamId) {
        departmentSelect.disabled = true;
        return;
      }
      
      // Find selected stream
      const selectedStream = streams.find(stream => stream.id === selectedStreamId);
      
      if (selectedStream && selectedStream.hasDepartments && selectedStream.departments) {
        // Enable department selection and populate options
        departmentSelect.disabled = false;
        
        // Add department options
        selectedStream.departments.forEach(dept => {
          const option = document.createElement("option");
          option.value = dept.id;
          option.textContent = dept.name;
          departmentSelect.appendChild(option);
        });
      } else {
        departmentSelect.disabled = true;
      }
    }
    
    // Load Cities from database
    function loadCities() {
      db.ref("cities").once("value", function(snapshot) {
        const citySelect = document.getElementById("city");
        const editCitySelect = document.getElementById("editCity");
        
        // Clear existing options except the first one
        citySelect.innerHTML = '<option value="">Select City</option>';
        editCitySelect.innerHTML = '<option value="">Select City</option>';
        
        cities = [];
        snapshot.forEach(function(childSnapshot) {
          const cityData = childSnapshot.val();
          const cityId = childSnapshot.key;
          
          // Store city data
          cities.push({
            id: cityId,
            name: cityData.name
          });
          
          // Add to dropdown
          const option = document.createElement("option");
          option.value = cityId;
          option.textContent = cityData.name;
          citySelect.appendChild(option);
          
          // Add to edit dropdown
          const editOption = document.createElement("option");
          editOption.value = cityId;
          editOption.textContent = cityData.name;
          editCitySelect.appendChild(editOption);
        });
      });
    }

    // Load Routes from database
    function loadRoutes() {
      db.ref("routes").once("value", function(snapshot) {
        routes = [];
        cityRoutes = {};
        
        snapshot.forEach(function(childSnapshot) {
          const routeData = childSnapshot.val();
          const routeId = childSnapshot.key;
          
          // Store route data
          routes.push({
            id: routeId,
            name: routeData.name,
            cityId: routeData.cityId
          });
          
          // Group routes by city
          if (routeData.cityId) {
            if (!cityRoutes[routeData.cityId]) {
              cityRoutes[routeData.cityId] = [];
            }
            cityRoutes[routeData.cityId].push({
              id: routeId,
              name: routeData.name
            });
          }
        });
      });
    }
    
    // Load routes based on selected city
    function loadRoutesByCity() {
      const citySelect = document.getElementById("city");
      const routeSelect = document.getElementById("route");
      const selectedCityId = citySelect.value;
      
      // Clear existing options
      routeSelect.innerHTML = '<option value="">Select Route</option>';
      
      if (!selectedCityId || !cityRoutes[selectedCityId]) {
        return;
      }
      
      // Add routes for selected city
      cityRoutes[selectedCityId].forEach(route => {
        const option = document.createElement("option");
        option.value = route.id;
        option.textContent = route.name;
        routeSelect.appendChild(option);
      });
    }
    
    // Load routes based on selected city for edit modal
    function loadEditRoutesByCity() {
      const citySelect = document.getElementById("editCity");
      const routeSelect = document.getElementById("editRoute");
      const selectedCityId = citySelect.value;
      
      // Clear existing options
      routeSelect.innerHTML = '<option value="">Select Route</option>';
      
      if (!selectedCityId || !cityRoutes[selectedCityId]) {
        return;
      }
      
      // Add routes for selected city
      cityRoutes[selectedCityId].forEach(route => {
        const option = document.createElement("option");
        option.value = route.id;
        option.textContent = route.name;
        routeSelect.appendChild(option);
      });
    }

    function loadStudentData() {
      const table = document.getElementById("studentTable");
      table.innerHTML = '';
      let serialNumber = 1; // Initialize serial number counter
      db.ref("students").once("value", function (snapshot) {
        snapshot.forEach(function (childSnapshot) {
          const key = childSnapshot.key;
          const data = childSnapshot.val();
          appendStudentRow(data, key, serialNumber++);
        });
      });
    }

    function appendStudentRow(data, key, serialNumber) {
      const table = document.getElementById("studentTable");
      const row = document.createElement("tr");
      row.setAttribute("data-key", key);
      
      // Set fees status to "Pending" if not defined
      const feesStatus = data.feesStatus || "Pending";
      
      // Create fee status badge with color
      let feesStatusBadge;
      if (feesStatus === "Paid") {
        feesStatusBadge = `<span class="badge bg-success">${feesStatus}</span>`;
      } else if (feesStatus === "Partial") {
        feesStatusBadge = `<span class="badge bg-warning text-dark">${feesStatus}</span>`;
      } else {
        feesStatusBadge = `<span class="badge bg-danger">${feesStatus}</span>`;
      }
      
      // Get stream name if we have the ID
      let streamName = data.stream;
      if (streams.length > 0) {
        const streamObj = streams.find(s => s.id === data.streamId);
        if (streamObj) {
          streamName = streamObj.name;
        }
      }
      
      // Get city name if we have the ID
      let cityName = data.city;
      if (cities.length > 0) {
        const cityObj = cities.find(c => c.id === data.cityId);
        if (cityObj) {
          cityName = cityObj.name;
        }
      }
      
      row.innerHTML = `
        <td>${serialNumber}</td>
        <td>${data.grNo}</td>
        <td>${data.name}</td>
        <td>${streamName}</td>
        <td>${cityName}</td>
        <td>${feesStatusBadge}</td>
        <td class="btn-group">
          <button class="btn btn-sm btn-info" onclick="openViewStudentModal('${key}')">
            <i class="fas fa-eye"></i> View
          </button>
          <button class="btn btn-sm btn-warning" onclick="openEditStudentModal('${key}')">
            <i class="fas fa-edit"></i> Edit
          </button>
          <button class="btn btn-sm btn-danger" onclick="deleteStudent(this)">
            <i class="fas fa-trash"></i> Delete
          </button>
        </td>`;
      table.appendChild(row);
    }
    
    // Validation functions
    function validateGrNo() {
      const grNoInput = document.getElementById("grNo");
      const value = grNoInput.value.trim();
      const isValid = /^\d{6}$/.test(value);
      
      if (value && !isValid) {
        grNoInput.classList.add("is-invalid");
        return false;
      } else {
        grNoInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateName() {
      const nameInput = document.getElementById("name");
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
    
    function validateEditGrNo() {
      const grNoInput = document.getElementById("editGrNo");
      const value = grNoInput.value.trim();
      const isValid = /^\d{6}$/.test(value);
      
      if (value && !isValid) {
        grNoInput.classList.add("is-invalid");
        return false;
      } else {
        grNoInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateEditName() {
      const nameInput = document.getElementById("editName");
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

    // Open Add Student Modal
    function openAddStudentModal() {
      document.getElementById("addStudentModal").style.display = "block";
      document.getElementById("addStudentForm").reset();
      
      // Clear validation states
      document.getElementById("grNo").classList.remove("is-invalid");
      document.getElementById("name").classList.remove("is-invalid");
      document.getElementById("phoneNo").classList.remove("is-invalid");
      
      // Disable department by default
      document.getElementById("department").disabled = true;
    }

    // Close Add Student Modal
    function closeAddStudentModal() {
      document.getElementById("addStudentModal").style.display = "none";
    }

    // Open Edit Student Modal
    function openEditStudentModal(key) {
      document.getElementById("editStudentModal").style.display = "block";
      document.getElementById("editStudentForm").reset();
      document.getElementById("editStudentKey").value = key;
      
      // Clear validation states
      document.getElementById("editGrNo").classList.remove("is-invalid");
      document.getElementById("editName").classList.remove("is-invalid");
      document.getElementById("editPhoneNo").classList.remove("is-invalid");
      
      // Fetch current student data
      db.ref("students/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        document.getElementById("editGrNo").value = data.grNo || '';
        document.getElementById("editName").value = data.name || '';
        document.getElementById("editAddress").value = data.address || '';
        document.getElementById("editPhoneNo").value = data.phoneNo || '';
        
        // Set stream if available
        if (data.streamId) {
          document.getElementById("editStream").value = data.streamId;
          handleEditStreamChange();
          
          // Set department if available
          if (data.departmentId) {
            document.getElementById("editDepartment").value = data.departmentId;
          }
        }
        
        // Set city if available
        if (data.cityId) {
          document.getElementById("editCity").value = data.cityId;
          loadEditRoutesByCity();
          
          // Set route if available
          setTimeout(() => {
            if (data.routeId) {
              document.getElementById("editRoute").value = data.routeId;
            }
          }, 500);
          }
        
        // Set fee status if available, default to Pending
        const selectElement = document.getElementById("editFeesStatus");
        const feesStatus = data.feesStatus || "Pending";
        
        for (let i = 0; i < selectElement.options.length; i++) {
          if (selectElement.options[i].value === feesStatus) {
            selectElement.selectedIndex = i;
            break;
          }
        }
      });
    }

    // Close Edit Student Modal
    function closeEditStudentModal() {
      document.getElementById("editStudentModal").style.display = "none";
    }

    // Open View Student Modal
    function openViewStudentModal(key) {
      document.getElementById("viewStudentModal").style.display = "block";
      
      // Fetch current student data
      db.ref("students/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        document.getElementById("viewGrNo").textContent = data.grNo || '';
        document.getElementById("viewName").textContent = data.name || '';
        document.getElementById("viewAddress").textContent = data.address || '';
        document.getElementById("viewPhoneNo").textContent = data.phoneNo || '';
        document.getElementById("viewFeesStatus").textContent = data.feesStatus || 'Pending';
        
        // Display stream name
        let streamName = "Not specified";
        if (data.streamId && streams.length > 0) {
          const streamObj = streams.find(s => s.id === data.streamId);
          if (streamObj) {
            streamName = streamObj.name;
          }
        }
        document.getElementById("viewStream").textContent = streamName;
        
        // Display department name
        let departmentName = "Not specified";
        if (data.streamId && data.departmentId && departments[data.streamId]) {
          const deptObj = departments[data.streamId].find(d => d.id === data.departmentId);
          if (deptObj) {
            departmentName = deptObj.name;
          }
        }
        document.getElementById("viewDepartment").textContent = departmentName;
        
        // Display city name
        let cityName = "Not specified";
        if (data.cityId && cities.length > 0) {
          const cityObj = cities.find(c => c.id === data.cityId);
          if (cityObj) {
            cityName = cityObj.name;
          }
        }
        document.getElementById("viewCity").textContent = cityName;
        
        // Display route name
        let routeName = "Not specified";
        if (data.routeId && routes.length > 0) {
          const routeObj = routes.find(r => r.id === data.routeId);
          if (routeObj) {
            routeName = routeObj.name;
          }
        }
        document.getElementById("viewRoute").textContent = routeName;
      });
    }

    // Close View Student Modal
    function closeViewStudentModal() {
      document.getElementById("viewStudentModal").style.display = "none";
    }

    // Validate and Save Student Data from Modal
    function validateAndSaveStudentData() {
      // Run all validations
      const grNoValid = validateGrNo();
      const nameValid = validateName();
      const phoneValid = validatePhoneNo();
      
      if (!grNoValid || !nameValid || !phoneValid) {
        return; // Stop if any validation fails
      }
      
      saveStudentData();
    }
    
    // Save Student Data from Modal
    function saveStudentData() {
      const grNo = document.getElementById("grNo").value.trim();
      const name = document.getElementById("name").value.trim();
      const streamId = document.getElementById("stream").value;
      const departmentId = document.getElementById("department").disabled ? "" : document.getElementById("department").value;
      const address = document.getElementById("address").value.trim();
      const cityId = document.getElementById("city").value;
      const routeId = document.getElementById("route").value;
      const phoneNo = document.getElementById("phoneNo").value.trim();
      const feesStatus = document.getElementById("feesStatus").value;

      // Basic validation
      if (!grNo || !name || !streamId || !address || !cityId || !routeId || !phoneNo) {
        alert("Please fill all required fields");
        return;
      }

      // Get stream and city names for display
      let streamName = "";
      if (streams.length > 0) {
        const streamObj = streams.find(s => s.id === streamId);
        if (streamObj) {
          streamName = streamObj.name;
        }
      }
      
      let cityName = "";
      if (cities.length > 0) {
        const cityObj = cities.find(c => c.id === cityId);
        if (cityObj) {
          cityName = cityObj.name;
        }
      }

      const studentData = {
        grNo: grNo,
        name: name,
        streamId: streamId,
        stream: streamName,
        departmentId: departmentId,
        address: address,
        cityId: cityId,
        city: cityName,
        routeId: routeId,
        phoneNo: phoneNo,
        feesStatus: feesStatus
      };

      const newStudentRef = db.ref("students").push();
      newStudentRef.set(studentData, function (error) {
        if (!error) {
          alert("Student saved successfully!");
          closeAddStudentModal();
          loadStudentData(); // Reload all data to get correct serial numbers
        } else {
          alert("Error saving student.");
        }
      });
    }

    // Validate and Update Student Data from Edit Modal
    function validateAndUpdateStudentData() {
      // Run all validations
      const grNoValid = validateEditGrNo();
      const nameValid = validateEditName();
      const phoneValid = validateEditPhoneNo();
      
      if (!grNoValid || !nameValid || !phoneValid) {
        return; // Stop if any validation fails
      }
      
      updateStudentData();
    }
    
    // Update Student Data from Edit Modal
    function updateStudentData() {
      const key = document.getElementById("editStudentKey").value;
      const grNo = document.getElementById("editGrNo").value.trim();
      const name = document.getElementById("editName").value.trim();
      const streamId = document.getElementById("editStream").value;
      const departmentId = document.getElementById("editDepartment").disabled ? "" : document.getElementById("editDepartment").value;
      const address = document.getElementById("editAddress").value.trim();
      const cityId = document.getElementById("editCity").value;
      const routeId = document.getElementById("editRoute").value;
      const phoneNo = document.getElementById("editPhoneNo").value.trim();
      const feesStatus = document.getElementById("editFeesStatus").value;

      // Basic validation
      if (!grNo || !name || !streamId || !address || !cityId || !routeId || !phoneNo) {
        alert("Please fill all required fields");
        return;
      }

      // Get stream and city names for display
      let streamName = "";
      if (streams.length > 0) {
        const streamObj = streams.find(s => s.id === streamId);
        if (streamObj) {
          streamName = streamObj.name;
        }
      }
      
      let cityName = "";
      if (cities.length > 0) {
        const cityObj = cities.find(c => c.id === cityId);
        if (cityObj) {
          cityName = cityObj.name;
        }
      }

      const updatedData = {
        grNo: grNo,
        name: name,
        streamId: streamId,
        stream: streamName,
        departmentId: departmentId,
        address: address,
        cityId: cityId,
        city: cityName,
        routeId: routeId,
        phoneNo: phoneNo,
        feesStatus: feesStatus
      };

      db.ref("students/" + key).set(updatedData, function (error) {
        if (!error) {
          alert("Student updated successfully!");
          closeEditStudentModal();
          // Reload all data since we need to maintain serial numbers
          loadStudentData();
        } else {
          alert("Error updating student.");
        }
      });
    }

    function deleteStudent(button) {
      if (!confirm("Are you sure you want to delete this student?")) {
        return;
      }
      
      const row = button.closest("tr");
      const key = row.getAttribute("data-key");

      if (key) {
        db.ref("students/" + key).remove(function (error) {
          if (!error) {
            alert("Student deleted successfully!");
            // Reload all data to renumber serial numbers
            loadStudentData();
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
      const addModal = document.getElementById("addStudentModal");
      const editModal = document.getElementById("editStudentModal");
      const viewModal = document.getElementById("viewStudentModal");
      
      if (event.target == addModal) {
        closeAddStudentModal();
      }
      
      if (event.target == editModal) {
        closeEditStudentModal();
      }
      
      if (event.target == viewModal) {
        closeViewStudentModal();
      }
    }
    
    // Enforce numeric input for GR No and Phone No
    document.getElementById("grNo").addEventListener("keypress", function(e) {
      if (!/\d/.test(e.key)) {
        e.preventDefault();
      }
    });
    
    document.getElementById("phoneNo").addEventListener("keypress", function(e) {
      if (!/\d/.test(e.key)) {
        e.preventDefault();
      }
    });
    
    document.getElementById("editGrNo").addEventListener("keypress", function(e) {
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