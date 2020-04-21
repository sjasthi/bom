<form id="importform" action="admin_import_bom.php" method="post">
<h4>Map the columns</h4>
<div class="group">
  <label for="app_id">App ID</label>
  <select id="app_id" name="app_id" required>
    <option value="">--Select Choice--</option>
    <?php dropdown($row); ?>
  </select>

  <label for="app_name">App Name</label>
  <select id="app_name" name="app_name" required>
    <option value="">--Select Choice--</option>
    <?php dropdown($row); ?>
  </select>

  <label for="app_version">App Version</label>
  <select id="app_version" name="app_version" required>
    <option value="">--Select Choice--</option>
    <?php dropdown($row); ?>
  </select>

  <label for="cmp_id">Component ID:</label>
  <select id="cmp_id" name="cmp_id" required>
    <option value="">--Select Choice--</option>
    <?php dropdown($row); ?>
  </select>

  <label for="cmp_name">Component Name:</label>
  <select id="cmp_name" name="cmp_name" required>
    <option value="">--Select Choice--</option>
    <?php dropdown($row); ?>
  </select>
</div>

<div class="group">
  <label for="cmp_version">Component Version:</label>
  <select id="cmp_version" name="cmp_version" required>
    <option value="">--Select Choice--</option>
    <?php dropdown($row); ?>
  </select>

  <label for="cmp_type">Component Type:</label>
  <select id="cmp_type" name="cmp_type" required>
    <option value="">--Select Choice--</option>
    <?php dropdown($row); ?>
  </select>

  <label for="app_status">App Status:</label>
  <select id="app_status" name="app_status" required>
    <option value="">--Select Choice--</option>
    <?php dropdown($row); ?>
  </select>

  <label for="cmp_status">Component Status:</label>
  <select id="cmp_status" name="cmp_status" required>
    <option value="">--Select Choice--</option>
    <?php dropdown($row); ?>
  </select>

  <label for="request_id">Request ID:</label>
  <select id="request_id" name="request_id" required>
    <option value="">--Select Choice--</option>
    <?php dropdown($row); ?>
  </select>
</div>

<div class="group">
  <label for="request_date">Request Date:</label>
  <select id="request_date" name="request_date" required>
    <option value="">--Select Choice--</option>
    <?php dropdown($row); ?>
  </select>

  <label for="request_status">Request Status:</label>
  <select id="request_status" name="request_status" required>
    <option value="">--Select Choice--</option>
    <?php dropdown($row); ?>
  </select>

  <label for="request_step">Request Step:</label>
  <select id="request_step" name="request_step" required>
    <option value="">--Select Choice--</option>
    <?php dropdown($row); ?>
  </select>

  <label for="notes">Notes:</label>
  <select id="notes" name="notes" required>
    <option value="">--Select Choice--</option>
    <?php dropdown($row); ?>
  </select>

  <label for="requestor">Requestor:</label>
  <select id="requestor" name="requestor" required>
    <option value="">--Select Choice--</option>
    <?php dropdown($row); ?>
  </select>
</div>

<button type="submit" name="submitform" value="submit">Import File</button>
</form>
