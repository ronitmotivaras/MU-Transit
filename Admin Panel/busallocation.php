<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MU Transit - Bus Allocation</title>
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
    .add-allocation-btn {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    .add-allocation-btn:hover {
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
    .card {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }
    .card-header {
      border-radius: 10px 10px 0 0;
      font-weight: bold;
    }
    .allocation-filters {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
    }
    .student-selection {
      max-height: 200px;
      overflow-y: auto;
      border: 1px solid #ced4da;
      border-radius: 5px;
      padding: 10px;
    }
    .student-item {
      padding: 8px;
      border-bottom: 1px solid #eee;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .student-item:last-child {
      border-bottom: none;
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
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Sidebar -->
  <?php include('sidenavbar.php')?>

  <!-- Bus Allocation Content -->
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Bus Allocation</h2>
      <button class="add-allocation-btn" onclick="openAddAllocationModal()">
        <i class="fas fa-plus"></i> New Allocation
      </button>
    </div>

    <!-- Allocation Filters -->
    <div class="allocation-filters">
      <div class="row">
        <div class="col-md-3 mb-2">
          <label for="filterShift" class="form-label">Filter by Shift</label>
          <select class="form-select" id="filterShift" onchange="filterAllocations()">
            <option value="">All Shifts</option>
            <!-- Will be populated from database -->
          </select>
        </div>
        <div class="col-md-3 mb-2">
          <label for="filterCity" class="form-label">Filter by City</label>
          <select class="form-select" id="filterCity" onchange="filterAllocations()">
            <option value="">All Cities</option>
            <!-- Will be populated from database -->
          </select>
        </div>
        <div class="col-md-3 mb-2">
          <label for="filterBus" class="form-label">Filter by Bus</label>
          <select class="form-select" id="filterBus" onchange="filterAllocations()">
            <option value="">All Buses</option>
            <!-- Will be populated from database -->
          </select>
        </div>
        <div class="col-md-3 mb-2">
          <label class="form-label">&nbsp;</label>
          <button class="btn btn-primary form-control" onclick="resetFilters()">
            <i class="fas fa-sync-alt"></i> Reset Filters
          </button>
        </div>
      </div>
    </div>

    <!-- Bus Allocation List -->
    <div class="row" id="allocationCards">
      <!-- Allocation cards will be dynamically loaded here -->
    </div>
  </div>

  <!-- Add Allocation Modal -->
  <div id="addAllocationModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>New Bus Allocation</h3>
        <span class="close-btn" onclick="closeAddAllocationModal()">&times;</span>
      </div>
      <form id="addAllocationForm">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="shift">Shift:</label>
              <select class="form-select" id="shift" required onchange="loadTimingsByShift()">
                <option value="">Select Shift</option>
                <!-- Will be populated from database -->
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="time">Time:</label>
              <select class="form-select" id="time" required>
                <option value="">Select Time</option>
                <!-- Will be populated based on shift -->
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="city">City:</label>
              <select class="form-select" id="city" required onchange="loadPickupPointsByCity()">
                <option value="">Select City</option>
                <!-- Will be populated from database -->
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="pickupPoint">Pickup Point:</label>
              <select class="form-select" id="pickupPoint" required>
                <option value="">Select Pickup Point</option>
                <!-- Will be populated based on city -->
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="busNumber">Bus Number:</label>
              <select class="form-select" id="busNumber" required>
                <option value="">Select Bus</option>
                <!-- Will be populated from database -->
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="capacity">Capacity:</label>
              <input type="text" class="form-control" id="capacity" readonly>
            </div>
          </div>
        </div>
        
        <div class="form-group mt-3">
          <label for="studentSelection">Select Students:</label>
          <div class="input-group mb-2">
            <input type="text" class="form-control" id="studentSearch" placeholder="Search students...">
            <button class="btn btn-outline-secondary" type="button" onclick="searchStudents()">
              <i class="fas fa-search"></i>
            </button>
          </div>
          <div class="student-selection" id="studentSelection">
            <!-- Students will be listed here -->
          </div>
          <div class="d-flex justify-content-between mt-2">
            <small class="text-muted">Selected: <span id="selectedCount">0</span></small>
            <small class="text-danger" id="capacityWarning" style="display:none;">
              Warning: Selected students exceed bus capacity!
            </small>
          </div>
        </div>
        
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-secondary me-2" onclick="closeAddAllocationModal()">Cancel</button>
          <button type="button" class="btn btn-success" onclick="saveAllocation()">Save Allocation</button>
        </div>
      </form>
    </div>
  </div>

  <!-- View Allocation Students Modal -->
  <div id="viewStudentsModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Allocated Students</h3>
        <span class="close-btn" onclick="closeViewStudentsModal()">&times;</span>
      </div>
      <div class="student-details">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>GR No.</th>
              <th>Name</th>
              <th>Stream</th>
              <th>Phone No.</th>
            </tr>
          </thead>
          <tbody id="viewStudentsTable">
            <!-- Allocated students will be listed here -->
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-secondary" onclick="closeViewStudentsModal()">Close</button>
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

    // Global variables
    let selectedStudents = [];
    let currentBusCapacity = 0;

    // Load data on page load
    window.onload = function () {
      loadAllAllocations();
      loadShifts();
      loadCities();
      loadBuses();
      
      // Also load shifts, cities, and buses for filters
      loadDataForFilters();
    };

    function loadDataForFilters() {
      // Load shifts for filter
      db.ref("shifts").once("value", function(snapshot) {
        const filterDropdown = document.getElementById("filterShift");
        snapshot.forEach(function(childSnapshot) {
          const shift = childSnapshot.val().name;
          const option = document.createElement("option");
          option.value = shift;
          option.textContent = shift;
          filterDropdown.appendChild(option);
        });
      });
      
      // Load cities for filter
      db.ref("cities").once("value", function(snapshot) {
        const filterDropdown = document.getElementById("filterCity");
        snapshot.forEach(function(childSnapshot) {
          const city = childSnapshot.val().name;
          const option = document.createElement("option");
          option.value = city;
          option.textContent = city;
          filterDropdown.appendChild(option);
        });
      });
      
      // Load buses for filter
      db.ref("buses").once("value", function(snapshot) {
        const filterDropdown = document.getElementById("filterBus");
        snapshot.forEach(function(childSnapshot) {
          const busNumber = childSnapshot.val().busNumber;
          const option = document.createElement("option");
          option.value = busNumber;
          option.textContent = busNumber;
          filterDropdown.appendChild(option);
        });
      });
    }

    function loadAllAllocations() {
      const container = document.getElementById("allocationCards");
      container.innerHTML = '';
      
      db.ref("allocations").once("value", function(snapshot) {
        if (!snapshot.exists()) {
          container.innerHTML = '<div class="col-12 text-center"><p>No bus allocations found.</p></div>';
          return;
        }
        
        snapshot.forEach(function(childSnapshot) {
          const key = childSnapshot.key;
          const allocation = childSnapshot.val();
          createAllocationCard(allocation, key);
        });
      });
    }

    function createAllocationCard(allocation, key) {
      const container = document.getElementById("allocationCards");
      const col = document.createElement("div");
      col.className = "col-md-6 col-lg-4";
      col.setAttribute("data-shift", allocation.shift);
      col.setAttribute("data-city", allocation.city);
      col.setAttribute("data-bus", allocation.busNumber);
      
      // Count students
      const studentCount = allocation.students ? Object.keys(allocation.students).length : 0;
      
      col.innerHTML = `
        <div class="card">
          <div class="card-header bg-primary text-white">
            Bus ${allocation.busNumber} - ${allocation.shift} Shift
          </div>
          <div class="card-body">
            <h5 class="card-title">${allocation.city} - ${allocation.pickupPoint}</h5>
            <p class="card-text">
              <strong>Time:</strong> ${allocation.time}<br>
              <strong>Students:</strong> ${studentCount} / ${allocation.capacity}
            </p>
            <div class="btn-group">
              <button class="btn btn-sm btn-info" onclick="viewAllocatedStudents('${key}')">
                <i class="fas fa-users"></i> View Students
              </button>
              <button class="btn btn-sm btn-danger" onclick="deleteAllocation('${key}')">
                <i class="fas fa-trash"></i> Delete
              </button>
            </div>
          </div>
        </div>
      `;
      
      container.appendChild(col);
    }

    function viewAllocatedStudents(allocationKey) {
      const table = document.getElementById("viewStudentsTable");
      table.innerHTML = '';
      
      db.ref(`allocations/${allocationKey}/students`).once("value", function(snapshot) {
        if (!snapshot.exists()) {
          table.innerHTML = '<tr><td colspan="4" class="text-center">No students allocated to this bus.</td></tr>';
          return;
        }
        
        const studentIds = Object.keys(snapshot.val());
        
        // For each student ID, fetch student details
        studentIds.forEach(function(studentId) {
          db.ref(`students/${studentId}`).once("value", function(studentSnapshot) {
            if (studentSnapshot.exists()) {
              const student = studentSnapshot.val();
              const row = document.createElement("tr");
              row.innerHTML = `
                <td>${student.grNo}</td>
                <td>${student.name}</td>
                <td>${student.stream}</td>
                <td>${student.phoneNo}</td>
              `;
              table.appendChild(row);
            }
          });
        });
      });
      
      document.getElementById("viewStudentsModal").style.display = "block";
    }

    function closeViewStudentsModal() {
      document.getElementById("viewStudentsModal").style.display = "none";
    }

    function loadShifts() {
      const shiftDropdown = document.getElementById("shift");
      shiftDropdown.innerHTML = '<option value="">Select Shift</option>';
      
      db.ref("shifts").once("value", function(snapshot) {
        snapshot.forEach(function(childSnapshot) {
          const shift = childSnapshot.val().name;
          const option = document.createElement("option");
          option.value = shift;
          option.textContent = shift;
          shiftDropdown.appendChild(option);
        });
      });
    }

    function loadTimingsByShift() {
      const shift = document.getElementById("shift").value;
      const timeDropdown = document.getElementById("time");
      timeDropdown.innerHTML = '<option value="">Select Time</option>';
      
      if (!shift) return;
      
      db.ref("timings").orderByChild("shift").equalTo(shift).once("value", function(snapshot) {
        snapshot.forEach(function(childSnapshot) {
          const timing = childSnapshot.val();
          const option = document.createElement("option");
          option.value = timing.time;
          option.textContent = timing.time;
          timeDropdown.appendChild(option);
        });
      });
    }

    function loadCities() {
      const cityDropdown = document.getElementById("city");
      cityDropdown.innerHTML = '<option value="">Select City</option>';
      
      db.ref("cities").once("value", function(snapshot) {
        snapshot.forEach(function(childSnapshot) {
          const city = childSnapshot.val().name;
          const option = document.createElement("option");
          option.value = city;
          option.textContent = city;
          cityDropdown.appendChild(option);
        });
      });
    }

    function loadPickupPointsByCity() {
      const city = document.getElementById("city").value;
      const pickupDropdown = document.getElementById("pickupPoint");
      pickupDropdown.innerHTML = '<option value="">Select Pickup Point</option>';
      
      if (!city) return;
      
      db.ref("pickupPoints").orderByChild("city").equalTo(city).once("value", function(snapshot) {
        snapshot.forEach(function(childSnapshot) {
          const point = childSnapshot.val();
          const option = document.createElement("option");
          option.value = point.name;
          option.textContent = point.name;
          pickupDropdown.appendChild(option);
        });
      });
    }

    function loadBuses() {
      const busDropdown = document.getElementById("busNumber");
      busDropdown.innerHTML = '<option value="">Select Bus</option>';
      
      db.ref("buses").once("value", function(snapshot) {
        snapshot.forEach(function(childSnapshot) {
          const bus = childSnapshot.val();
          const option = document.createElement("option");
          option.value = bus.busNumber;
          option.setAttribute("data-capacity", bus.capacity);
          option.textContent = `${bus.busNumber} (${bus.capacity} seats)`;
          busDropdown.appendChild(option);
        });
      });
      
      // Add event listener to update capacity field when bus is selected
      document.getElementById("busNumber").addEventListener("change", function() {
        const selectedOption = this.options[this.selectedIndex];
        const capacity = selectedOption.getAttribute("data-capacity");
        document.getElementById("capacity").value = capacity || "";
        currentBusCapacity = capacity ? parseInt(capacity) : 0;
        
        // Update capacity warning if needed
        updateCapacityWarning();
      });
    }

    function searchStudents() {
      const searchTerm = document.getElementById("studentSearch").value.toLowerCase();
      const selection = document.getElementById("studentSelection");
      selection.innerHTML = '';
      
      // Get filter values
      const city = document.getElementById("city").value;
      
      db.ref("students").once("value", function(snapshot) {
        if (!snapshot.exists()) {
          selection.innerHTML = '<div class="text-center">No students found.</div>';
          return;
        }
        
        let foundStudents = false;
        
        snapshot.forEach(function(childSnapshot) {
          const key = childSnapshot.key;
          const student = childSnapshot.val();
          
          // Filter by city and search term
          if (student.city === city && 
              (student.name.toLowerCase().includes(searchTerm) || 
               student.grNo.toLowerCase().includes(searchTerm))) {
            
            foundStudents = true;
            
            // Check if already selected
            const isSelected = selectedStudents.some(s => s.id === key);
            
            const studentDiv = document.createElement("div");
            studentDiv.className = "student-item";
            studentDiv.innerHTML = `
              <div>
                <strong>${student.name}</strong> (${student.grNo})
                <small class="text-muted d-block">${student.stream}</small>
              </div>
              <div>
                <input type="checkbox" class="form-check-input" value="${key}" 
                  data-name="${student.name}" ${isSelected ? 'checked' : ''}
                  onchange="toggleStudentSelection(this)">
              </div>
            `;
            
            selection.appendChild(studentDiv);
          }
        });
        
        if (!foundStudents) {
          selection.innerHTML = '<div class="text-center">No matching students found.</div>';
        }
      });
    }

    function toggleStudentSelection(checkbox) {
      const studentId = checkbox.value;
      const studentName = checkbox.getAttribute("data-name");
      
      if (checkbox.checked) {
        // Add to selected students
        selectedStudents.push({
          id: studentId,
          name: studentName
        });
      } else {
        // Remove from selected students
        selectedStudents = selectedStudents.filter(student => student.id !== studentId);
      }
      
      // Update selected count display
      document.getElementById("selectedCount").textContent = selectedStudents.length;
      
      // Check if exceeding capacity
      updateCapacityWarning();
    }

    function updateCapacityWarning() {
      const warning = document.getElementById("capacityWarning");
      if (currentBusCapacity > 0 && selectedStudents.length > currentBusCapacity) {
        warning.style.display = "block";
      } else {
        warning.style.display = "none";
      }
    }

    function openAddAllocationModal() {
      document.getElementById("addAllocationModal").style.display = "block";
      document.getElementById("addAllocationForm").reset();
      document.getElementById("studentSelection").innerHTML = '';
      document.getElementById("selectedCount").textContent = "0";
      document.getElementById("capacityWarning").style.display = "none";
      selectedStudents = [];
      currentBusCapacity = 0;
    }

    function closeAddAllocationModal() {
      document.getElementById("addAllocationModal").style.display = "none";
    }

    function saveAllocation() {
      // Get form values
      const shift = document.getElementById("shift").value;
      const time = document.getElementById("time").value;
      const city = document.getElementById("city").value;
      const pickupPoint = document.getElementById("pickupPoint").value;
      const busNumber = document.getElementById("busNumber").value;
      const capacity = document.getElementById("capacity").value;
      
      // Basic validation
      if (!shift || !time || !city || !pickupPoint || !busNumber) {
        alert("Please fill all required fields");
        return;
      }
      
      if (selectedStudents.length === 0) {
        alert("Please select at least one student for allocation");
        return;
      }
      
      // Create allocation object
      const allocationData = {
        shift: shift,
        time: time,
        city: city,
        pickupPoint: pickupPoint,
        busNumber: busNumber,
        capacity: capacity,
        createdAt: new Date().toISOString()
      };
      
      // Add students
      allocationData.students = {};
      selectedStudents.forEach(student => {
        allocationData.students[student.id] = true;
      });
      
      // Save to Firebase
      const newAllocationRef = db.ref("allocations").push();
      newAllocationRef.set(allocationData, function(error) {
        if (error) {
          alert("Error saving allocation: " + error.message);
        } else {
          alert("Bus allocation saved successfully!");
          closeAddAllocationModal();
          createAllocationCard(allocationData, newAllocationRef.key);
        }
      });
    }

    function deleteAllocation(key) {
      if (!confirm("Are you sure you want to delete this bus allocation?")) {
        return;
      }
      
      db.ref("allocations/" + key).remove(function(error) {
        if (error) {
          alert("Error deleting allocation: " + error.message);
        } else {
          alert("Bus allocation deleted successfully!");
          loadAllAllocations(); // Reload all allocations
        }
      });
    }

    function filterAllocations() {
      const shift = document.getElementById("filterShift").value;
      const city = document.getElementById("filterCity").value;
      const bus = document.getElementById("filterBus").value;
      
      const allocations = document.querySelectorAll("#allocationCards > div");
      
      allocations.forEach(function(allocation) {
        const allocationShift = allocation.getAttribute("data-shift");
        const allocationCity = allocation.getAttribute("data-city");
        const allocationBus = allocation.getAttribute("data-bus");
        
        let shouldShow = true;
        
        if (shift && allocationShift !== shift) shouldShow = false;
        if (city && allocationCity !== city) shouldShow = false;
        if (bus && allocationBus !== bus) shouldShow = false;
        
        allocation.style.display = shouldShow ? "block" : "none";
      });
    }

    function resetFilters() {
      document.getElementById("filterShift").value = "";
      document.getElementById("filterCity").value = "";
      document.getElementById("filterBus").value = "";
      
      const allocations = document.querySelectorAll("#allocationCards > div");
      allocations.forEach(function(allocation) {
        allocation.style.display = "block";
      });
    }

    // Close the modals when clicking outside of them
    window.onclick = function(event) {
      const addModal = document.getElementById("addAllocationModal");
      const viewModal = document.getElementById("viewStudentsModal");
      
      if (event.target == addModal) {
        closeAddAllocationModal();
      }
      
      if (event.target == viewModal) {
        closeViewStudentsModal();
      }
    }
  </script>
</body>
</html>