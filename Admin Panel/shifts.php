<?php
// This is the shifts management page for MU Transit Admin Panel
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MU Transit - Shifts Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  <link rel="stylesheet" href="style.css"/>
</head>
<body>
  <!-- Sidebar -->
  <?php include('sidenavbar.php')?>

  <!-- Main Content -->
  <div class="content">
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Firebase config
    const firebaseConfig = {
      databaseURL: "https://mu-transit-86f0a-default-rtdb.firebaseio.com/"
    };
    firebase.initializeApp(firebaseConfig);
    const db = firebase.database();

    // Load data on page load
    window.onload = function () {
      loadShiftData();
      
      // Add input validation listeners for shifts
      document.getElementById("shiftName").addEventListener("input", validateShiftName);
      document.getElementById("startTime").addEventListener("input", validateStartTime);
      document.getElementById("endTime").addEventListener("input", validateEndTime);
      document.getElementById("editShiftName").addEventListener("input", validateEditShiftName);
      document.getElementById("editStartTime").addEventListener("input", validateEditStartTime);
      document.getElementById("editEndTime").addEventListener("input", validateEditEndTime);
    };
    
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
      const addShiftModal = document.getElementById("addShiftModal");
      const editShiftModal = document.getElementById("editShiftModal");
      const viewShiftModal = document.getElementById("viewShiftModal");
      
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