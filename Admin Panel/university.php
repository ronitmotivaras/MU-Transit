<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MU Transit - Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  <link rel="stylesheet" href="style.css"/>
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
      <!-- Include Streams Tab -->
      <div class="tab-pane fade show active" id="streams" role="tabpanel" aria-labelledby="streams-tab">
        <?php include('streams.php'); ?>
      </div>
      
      <!-- Include Shifts Tab -->
      <div class="tab-pane fade" id="shifts" role="tabpanel" aria-labelledby="shifts-tab">
        <?php include('shifts.php'); ?>
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