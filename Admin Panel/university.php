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

/* Updated Tab styles */
.nav-tabs {
  border-bottom: 2px solid #e9ecef;
  margin-bottom: 20px;
  display: flex;
  gap: 5px;
}

.nav-tabs .nav-item {
  margin-bottom: -2px;
}

.nav-tabs .nav-link {
  border: none;
  color: #6c757d;
  font-weight: 500;
  padding: 12px 20px;
  border-radius: 6px 6px 0 0;
  transition: all 0.3s ease;
  position: relative;
}

.nav-tabs .nav-link:hover {
  color: #212529;
  background-color: #f8f9fa;
  border-color: transparent;
}

.nav-tabs .nav-link.active {
  color: #0d6efd;
  background-color: #f0f7ff;
  border-color: transparent;
}

.nav-tabs .nav-link.active::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 3px;
  background-color: #0d6efd;
  border-radius: 3px 3px 0 0;
}

.nav-tabs .nav-link i {
  margin-right: 8px;
}

.tab-content {
  padding: 20px 0;
}
  </style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Sidebar -->
  <?php include('sidenavbar.php')?>

  <!-- Main Content -->
  <div class="content">
    <!-- Navigation tabs with icons -->
<ul class="nav nav-tabs" id="adminTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="streams-tab" data-bs-toggle="tab" data-bs-target="#streams" type="button" role="tab" aria-controls="streams" aria-selected="true">
      <i class="fas fa-stream"></i> Streams
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="shifts-tab" data-bs-toggle="tab" data-bs-target="#shifts" type="button" role="tab" aria-controls="shifts" aria-selected="false">
      <i class="fas fa-clock"></i> Shifts
    </button>
  </li>
</ul>
    
    <!-- Tab contents -->
    <div class="tab-content" id="adminTabsContent">
      <!-- Streams Tab -->
      <div class="tab-pane fade show active" id="streams" role="tabpanel" aria-labelledby="streams-tab">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2>Streams</h2>
          <button class="add-btn" onclick="openAddStreamModal()">
            <i class="fas fa-plus"></i> Add Stream
          </button>
        </div>
    
        <table class="table table-bordered table-striped text-center">
          <thead class="table-dark">
            <tr>
              <th width="10%">Sr No</th>
              <th width="35%">Stream Name</th>
              <th width="35%">Department</th>
              <th width="20%">Actions</th>
            </tr>
          </thead>
          <tbody id="streamTable">
            <!-- Data will be dynamically loaded -->
          </tbody>
        </table>
      </div>
      
      <!-- Shifts Tab -->
      <div class="tab-pane fade" id="shifts" role="tabpanel" aria-labelledby="shifts-tab">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2>Shifts</h2>
          <button class="add-btn" onclick="openAddShiftModal()">
            <i class="fas fa-plus"></i> Add Shift
          </button>
        </div>
    
        <table class="table table-bordered table-striped text-center">
          <thead class="table-dark">
            <tr>
              <th width="5%">Sr No</th>
              <th width="25%">Shift Name</th>
              <th width="20%">Start Time</th>
              <th width="20%">End Time</th>
              <th width="15%">Actions</th>
            </tr>
          </thead>
          <tbody id="shiftTable">
            <!-- Data will be dynamically loaded -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Add Stream Modal -->
  <div id="addStreamModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Add New Stream</h3>
        <span class="close-btn" onclick="closeAddStreamModal()">&times;</span>
      </div>
      <form id="addStreamForm">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="streamName">Stream Name:</label>
              <input type="text" class="form-control" id="streamName" required>
              <div class="invalid-feedback">Stream name is required.</div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="department">Department (Optional):</label>
              <input type="text" class="form-control" id="department">
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-secondary me-2" onclick="closeAddStreamModal()">Cancel</button>
          <button type="button" class="btn btn-success" onclick="validateAndSaveStreamData()">Save Stream</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Stream Modal -->
  <div id="editStreamModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Edit Stream</h3>
        <span class="close-btn" onclick="closeEditStreamModal()">&times;</span>
      </div>
      <form id="editStreamForm">
        <input type="hidden" id="editStreamKey">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="editStreamName">Stream Name:</label>
              <input type="text" class="form-control" id="editStreamName" required>
              <div class="invalid-feedback">Stream name is required.</div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="editDepartment">Department (Optional):</label>
              <input type="text" class="form-control" id="editDepartment">
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-secondary me-2" onclick="closeEditStreamModal()">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="validateAndUpdateStreamData()">Update Information</button>
        </div>
      </form>
    </div>
  </div>

  <!-- View Stream Modal -->
  <div id="viewStreamModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Stream Details</h3>
        <span class="close-btn" onclick="closeViewStreamModal()">&times;</span>
      </div>
      <div class="stream-details">
        <table class="table table-bordered">
          <tr>
            <th width="30%">Stream Name</th>
            <td id="viewStreamName"></td>
          </tr>
          <tr>
            <th>Department</th>
            <td id="viewDepartment"></td>
          </tr>
        </table>
      </div>
      <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-secondary" onclick="closeViewStreamModal()">Close</button>
      </div>
    </div>
  </div>

  <!-- Add Shift Modal -->
  <div id="addShiftModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Add New Shift</h3>
        <span class="close-btn" onclick="closeAddShiftModal()">&times;</span>
      </div>
      <form id="addShiftForm">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="shiftName">Shift Name:</label>
              <input type="text" class="form-control" id="shiftName" required>
              <div class="invalid-feedback">Shift name is required.</div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="startTime">Start Time:</label>
              <input type="time" class="form-control" id="startTime" required>
              <div class="invalid-feedback">Start time is required.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="endTime">End Time:</label>
              <input type="time" class="form-control" id="endTime" required>
              <div class="invalid-feedback">End time is required.</div>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-secondary me-2" onclick="closeAddShiftModal()">Cancel</button>
          <button type="button" class="btn btn-success" onclick="validateAndSaveShiftData()">Save Shift</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Shift Modal -->
  <div id="editShiftModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Edit Shift</h3>
        <span class="close-btn" onclick="closeEditShiftModal()">&times;</span>
      </div>
      <form id="editShiftForm">
        <input type="hidden" id="editShiftKey">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="editShiftName">Shift Name:</label>
              <input type="text" class="form-control" id="editShiftName" required>
              <div class="invalid-feedback">Shift name is required.</div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="editStartTime">Start Time:</label>
              <input type="time" class="form-control" id="editStartTime" required>
              <div class="invalid-feedback">Start time is required.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="editEndTime">End Time:</label>
              <input type="time" class="form-control" id="editEndTime" required>
              <div class="invalid-feedback">End time is required.</div>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-secondary me-2" onclick="closeEditShiftModal()">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="validateAndUpdateShiftData()">Update Information</button>
        </div>
      </form>
    </div>
  </div>

  <!-- View Shift Modal -->
  <div id="viewShiftModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Shift Details</h3>
        <span class="close-btn" onclick="closeViewShiftModal()">&times;</span>
      </div>
      <div class="shift-details">
        <table class="table table-bordered">
          <tr>
            <th width="30%">Shift Name</th>
            <td id="viewShiftName"></td>
          </tr>
          <tr>
            <th>Start Time</th>
            <td id="viewStartTime"></td>
          </tr>
          <tr>
            <th>End Time</th>
            <td id="viewEndTime"></td>
          </tr>
        </table>
      </div>
      <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-secondary" onclick="closeViewShiftModal()">Close</button>
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
      loadStreamData();
      loadShiftData();
      
      // Add input validation listeners for streams
      document.getElementById("streamName").addEventListener("input", validateStreamName);
      document.getElementById("editStreamName").addEventListener("input", validateEditStreamName);
      
      // Add input validation listeners for shifts
      document.getElementById("shiftName").addEventListener("input", validateShiftName);
      document.getElementById("startTime").addEventListener("input", validateStartTime);
      document.getElementById("endTime").addEventListener("input", validateEndTime);
      document.getElementById("editShiftName").addEventListener("input", validateEditShiftName);
      document.getElementById("editStartTime").addEventListener("input", validateEditStartTime);
      document.getElementById("editEndTime").addEventListener("input", validateEditEndTime);
    };

    // ============= STREAMS FUNCTIONALITY =============
    function loadStreamData() {
      const table = document.getElementById("streamTable");
      table.innerHTML = '';
      db.ref("streams").once("value", function (snapshot) {
        let counter = 1;
        snapshot.forEach(function (childSnapshot) {
          const key = childSnapshot.key;
          const data = childSnapshot.val();
          appendStreamRow(data, key, counter);
          counter++;
        });
      });
    }

    function appendStreamRow(data, key, counter) {
      const table = document.getElementById("streamTable");
      const row = document.createElement("tr");
      row.setAttribute("data-key", key);
      
      row.innerHTML = `
        <td>${counter}</td>
        <td>${data.streamName}</td>
        <td>${data.department || '-'}</td>
        <td class="btn-group">
          <button class="btn btn-sm btn-info" onclick="openViewStreamModal('${key}')">
            <i class="fas fa-eye"></i> View
          </button>
          <button class="btn btn-sm btn-warning" onclick="openEditStreamModal('${key}')">
            <i class="fas fa-edit"></i> Edit
          </button>
          <button class="btn btn-sm btn-danger" onclick="deleteStream(this)">
            <i class="fas fa-trash"></i> Delete
          </button>
        </td>`;
      table.appendChild(row);
    }
    
    // Stream Validation functions
    function validateStreamName() {
      const streamNameInput = document.getElementById("streamName");
      const value = streamNameInput.value.trim();
      
      if (!value) {
        streamNameInput.classList.add("is-invalid");
        return false;
      } else {
        streamNameInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateEditStreamName() {
      const streamNameInput = document.getElementById("editStreamName");
      const value = streamNameInput.value.trim();
      
      if (!value) {
        streamNameInput.classList.add("is-invalid");
        return false;
      } else {
        streamNameInput.classList.remove("is-invalid");
        return true;
      }
    }

    // Open Add Stream Modal
    function openAddStreamModal() {
      document.getElementById("addStreamModal").style.display = "block";
      document.getElementById("addStreamForm").reset();
      
      // Clear validation states
      document.getElementById("streamName").classList.remove("is-invalid");
    }

    // Close Add Stream Modal
    function closeAddStreamModal() {
      document.getElementById("addStreamModal").style.display = "none";
    }

    // Open Edit Stream Modal
    function openEditStreamModal(key) {
      document.getElementById("editStreamModal").style.display = "block";
      document.getElementById("editStreamForm").reset();
      document.getElementById("editStreamKey").value = key;
      
      // Clear validation states
      document.getElementById("editStreamName").classList.remove("is-invalid");
      
      // Fetch current stream data
      db.ref("streams/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        document.getElementById("editStreamName").value = data.streamName || '';
        document.getElementById("editDepartment").value = data.department || '';
      });
    }

    // Close Edit Stream Modal
    function closeEditStreamModal() {
      document.getElementById("editStreamModal").style.display = "none";
    }

    // Open View Stream Modal
    function openViewStreamModal(key) {
      document.getElementById("viewStreamModal").style.display = "block";
      
      // Fetch current stream data
      db.ref("streams/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        document.getElementById("viewStreamName").textContent = data.streamName || '';
        document.getElementById("viewDepartment").textContent = data.department || '-';
      });
    }

    // Close View Stream Modal
    function closeViewStreamModal() {
      document.getElementById("viewStreamModal").style.display = "none";
    }

    // Validate and Save Stream Data from Modal
    function validateAndSaveStreamData() {
      const streamNameInput = document.getElementById("streamName");
      
      // Run validation
      const nameValid = validateStreamName();
      
      if (!nameValid) {
        return; // Stop if validation fails
      }
      
      saveStreamData();
    }
    
    // Save Stream Data from Modal
    function saveStreamData() {
      const streamName = document.getElementById("streamName").value.trim();
      const department = document.getElementById("department").value.trim();

      const streamData = {
        streamName: streamName,
        department: department
      };

      const newStreamRef = db.ref("streams").push();
      newStreamRef.set(streamData, function (error) {
        if (!error) {
          alert("Stream saved successfully!");
          closeAddStreamModal();
          loadStreamData(); // Reload all data to ensure correct serial numbers
        } else {
          alert("Error saving stream.");
        }
      });
    }

    // Validate and Update Stream Data from Edit Modal
    function validateAndUpdateStreamData() {
      const streamNameInput = document.getElementById("editStreamName");
      
      // Run validation
      const nameValid = validateEditStreamName();
      
      if (!nameValid) {
        return; // Stop if validation fails
      }
      
      updateStreamData();
    }
    
    // Update Stream Data from Edit Modal
    function updateStreamData() {
      const key = document.getElementById("editStreamKey").value;
      const streamName = document.getElementById("editStreamName").value.trim();
      const department = document.getElementById("editDepartment").value.trim();

      const updatedData = {
        streamName: streamName,
        department: department
      };

      db.ref("streams/" + key).set(updatedData, function (error) {
        if (!error) {
          alert("Stream updated successfully!");
          closeEditStreamModal();
          loadStreamData(); // Reload all data to ensure correct serial numbers
        } else {
          alert("Error updating stream.");
        }
      });
    }

    function deleteStream(button) {
      if (!confirm("Are you sure you want to delete this stream?")) {
        return;
      }
      
      const row = button.closest("tr");
      const key = row.getAttribute("data-key");

      if (key) {
        db.ref("streams/" + key).remove(function (error) {
          if (!error) {
            loadStreamData(); // Reload all data to ensure correct serial numbers
            alert("Stream deleted successfully!");
          } else {
            alert("Error deleting from database.");
          }
        });
      } else {
        row.remove(); // Only from UI if no key exists
      }
    }
    
    // ============= SHIFTS FUNCTIONALITY =============
    function loadShiftData() {
      const table = document.getElementById("shiftTable");
      table.innerHTML = '';
      db.ref("shifts").once("value", function (snapshot) {
        let counter = 1;
        snapshot.forEach(function (childSnapshot) {
          const key = childSnapshot.key;
          const data = childSnapshot.val();
          appendShiftRow(data, key, counter);
          counter++;
        });
      });
    }

    function appendShiftRow(data, key, counter) {
      const table = document.getElementById("shiftTable");
      const row = document.createElement("tr");
      row.setAttribute("data-key", key);
      
      // Format the time for display
      const formattedStartTime = formatTime(data.startTime);
      const formattedEndTime = formatTime(data.endTime);
      
      row.innerHTML = `
        <td>${counter}</td>
        <td>${data.shiftName}</td>
        <td>${formattedStartTime}</td>
        <td>${formattedEndTime}</td>
        <td class="btn-group">
          <button class="btn btn-sm btn-info" onclick="openViewShiftModal('${key}')">
            <i class="fas fa-eye"></i> View
          </button>
          <button class="btn btn-sm btn-warning" onclick="openEditShiftModal('${key}')">
            <i class="fas fa-edit"></i> Edit
          </button>
          <button class="btn btn-sm btn-danger" onclick="deleteShift(this)">
            <i class="fas fa-trash"></i> Delete
          </button>
        </td>`;
      table.appendChild(row);
    }
    
    // Format time for display (HH:MM)
    function formatTime(timeString) {
      if (!timeString) return '-';
      return timeString;
    }
    
    // Shift Validation functions
    function validateShiftName() {
      const shiftNameInput = document.getElementById("shiftName");
      const value = shiftNameInput.value.trim();
      
      if (!value) {
        shiftNameInput.classList.add("is-invalid");
        return false;
      } else {
        shiftNameInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateStartTime() {
      const startTimeInput = document.getElementById("startTime");
      const value = startTimeInput.value.trim();
      
      if (!value) {
        startTimeInput.classList.add("is-invalid");
        return false;
      } else {
        startTimeInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateEndTime() {
      const endTimeInput = document.getElementById("endTime");
      const value = endTimeInput.value.trim();
      
      if (!value) {
        endTimeInput.classList.add("is-invalid");
        return false;
      } else {
        endTimeInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateEditShiftName() {
      const shiftNameInput = document.getElementById("editShiftName");
      const value = shiftNameInput.value.trim();
      
      if (!value) {
        shiftNameInput.classList.add("is-invalid");
        return false;
      } else {
        shiftNameInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateEditStartTime() {
      const startTimeInput = document.getElementById("editStartTime");
      const value = startTimeInput.value.trim();
      
      if (!value) {
        startTimeInput.classList.add("is-invalid");
        return false;
      } else {
        startTimeInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateEditEndTime() {
      const endTimeInput = document.getElementById("editEndTime");
      const value = endTimeInput.value.trim();
      
      if (!value) {
        endTimeInput.classList.add("is-invalid");
        return false;
      } else {
        endTimeInput.classList.remove("is-invalid");
        return true;
      }
    }

    // Open Add Shift Modal
    function openAddShiftModal() {
      document.getElementById("addShiftModal").style.display = "block";
      document.getElementById("addShiftForm").reset();
      
      // Clear validation states
      document.getElementById("shiftName").classList.remove("is-invalid");
      document.getElementById("startTime").classList.remove("is-invalid");
      document.getElementById("endTime").classList.remove("is-invalid");
    }

    // Close Add Shift Modal
    function closeAddShiftModal() {
      document.getElementById("addShiftModal").style.display = "none";
    }

    // Open Edit Shift Modal
    function openEditShiftModal(key) {
      document.getElementById("editShiftModal").style.display = "block";
      document.getElementById("editShiftForm").reset();
      document.getElementById("editShiftKey").value = key;
      
      // Clear validation states
      document.getElementById("editShiftName").classList.remove("is-invalid");
      document.getElementById("editStartTime").classList.remove("is-invalid");
      document.getElementById("editEndTime").classList.remove("is-invalid");
      
      // Fetch current shift data
      db.ref("shifts/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        document.getElementById("editShiftName").value = data.shiftName || '';
        document.getElementById("editStartTime").value = data.startTime || '';
        document.getElementById("editEndTime").value = data.endTime || '';
      });
    }

    // Close Edit Shift Modal
    function closeEditShiftModal() {
      document.getElementById("editShiftModal").style.display = "none";
    }

    // Open View Shift Modal
    function openViewShiftModal(key) {
      document.getElementById("viewShiftModal").style.display = "block";
      
      // Fetch current shift data
      db.ref("shifts/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        document.getElementById("viewShiftName").textContent = data.shiftName || '';
        document.getElementById("viewStartTime").textContent = formatTime(data.startTime) || '-';
        document.getElementById("viewEndTime").textContent = formatTime(data.endTime) || '-';
      });
    }

    // Close View Shift Modal
    function closeViewShiftModal() {
      document.getElementById("viewShiftModal").style.display = "none";
    }

    // Validate and Save Shift Data from Modal
    function validateAndSaveShiftData() {
      // Run validation
      const nameValid = validateShiftName();
      const startTimeValid = validateStartTime();
      const endTimeValid = validateEndTime();
      
      if (!nameValid || !startTimeValid || !endTimeValid) {
        return; // Stop if validation fails
      }
      
      saveShiftData();
    }
    
    // Save Shift Data from Modal
    function saveShiftData() {
      const shiftName = document.getElementById("shiftName").value.trim();
      const startTime = document.getElementById("startTime").value.trim();
      const endTime = document.getElementById("endTime").value.trim();

      const shiftData = {
        shiftName: shiftName,
        startTime: startTime,
        endTime: endTime
      };

      const newShiftRef = db.ref("shifts").push();
      newShiftRef.set(shiftData, function (error) {
        if (!error) {
          alert("Shift saved successfully!");
          closeAddShiftModal();
          loadShiftData(); // Reload all data to ensure correct serial numbers
        } else {
          alert("Error saving shift.");
        }
      });
    }

    // Validate and Update Shift Data from Edit Modal
    function validateAndUpdateShiftData() {
      // Run validation
      const nameValid = validateEditShiftName();
      const startTimeValid = validateEditStartTime();
      const endTimeValid = validateEditEndTime();
      
      if (!nameValid || !startTimeValid || !endTimeValid) {
        return; // Stop if validation fails
      }
      
      updateShiftData();
    }
    
    // Update Shift Data from Edit Modal
    function updateShiftData() {
      const key = document.getElementById("editShiftKey").value;
      const shiftName = document.getElementById("editShiftName").value.trim();
      const startTime = document.getElementById("editStartTime").value.trim();
      const endTime = document.getElementById("editEndTime").value.trim();

      const updatedData = {
        shiftName: shiftName,
        startTime: startTime,
        endTime: endTime
      };

      db.ref("shifts/" + key).set(updatedData, function (error) {
        if (!error) {
          alert("Shift updated successfully!");
          closeEditShiftModal();
          loadShiftData(); // Reload all data to ensure correct serial numbers
        } else {
          alert("Error updating shift.");
        }
      });
    }

    function deleteShift(button) {
      if (!confirm("Are you sure you want to delete this shift?")) {
        return;
      }
      
      const row = button.closest("tr");
      const key = row.getAttribute("data-key");

      if (key) {
        db.ref("shifts/" + key).remove(function (error) {
          if (!error) {
            loadShiftData(); // Reload all data to ensure correct serial numbers
            alert("Shift deleted successfully!");
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
      const addStreamModal = document.getElementById("addStreamModal");
      const editStreamModal = document.getElementById("editStreamModal");
      const viewStreamModal = document.getElementById("viewStreamModal");
      const addShiftModal = document.getElementById("addShiftModal");
      const editShiftModal = document.getElementById("editShiftModal");
      const viewShiftModal = document.getElementById("viewShiftModal");
      
      if (event.target == addStreamModal) {
        closeAddStreamModal();
      }
      
      if (event.target == editStreamModal) {
        closeEditStreamModal();
      }
      
      if (event.target == viewStreamModal) {
        closeViewStreamModal();
      }
      
      if (event.target == addShiftModal) {
        closeAddShiftModal();
      }
      
      if (event.target == editShiftModal) {
        closeEditShiftModal();
      }
      
      if (event.target == viewShiftModal) {
        closeViewShiftModal();
      }
    }
  </script>
</body>
</html>