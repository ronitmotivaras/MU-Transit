<?php
// This is the streams management page for MU Transit Admin Panel
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MU Transit - Streams Management</title>
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
      loadStreamData();
      
      // Add input validation listeners for streams
      document.getElementById("streamName").addEventListener("input", validateStreamName);
      document.getElementById("editStreamName").addEventListener("input", validateEditStreamName);
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

    // Close the modals when clicking outside of them
    window.onclick = function(event) {
      const addStreamModal = document.getElementById("addStreamModal");
      const editStreamModal = document.getElementById("editStreamModal");
      const viewStreamModal = document.getElementById("viewStreamModal");
      
      if (event.target == addStreamModal) {
        closeAddStreamModal();
      }
      
      if (event.target == editStreamModal) {
        closeEditStreamModal();
      }
      
      if (event.target == viewStreamModal) {
        closeViewStreamModal();
      }
    }
  </script>
</body>
</html>