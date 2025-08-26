<h1>Upload New Asset</h1>

<form action="<?= URLROOT; ?>/admin/assets/store" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Asset Name</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="type">Asset Type</label>
        <select name="type" id="type" class="form-control" required onchange="toggleAssetInput()">
            <option value="frame">Frame</option>
            <option value="sticker">Sticker</option>
            <option value="filter">Filter</option>
        </select>
    </div>
    <div class="form-group" id="asset-file-group">
        <label for="asset_file">Asset File (PNG, JPG, GIF)</label>
        <input type="file" name="asset_file" id="asset_file" class="form-control" accept="image/png, image/jpeg, image/gif">
    </div>
    <div class="form-group" id="asset-value-group" style="display: none;">
        <label for="asset_value">CSS Filter Value</label>
        <input type="text" name="asset_value" id="asset_value" class="form-control" placeholder="e.g., sepia(100%)">
    </div>
    <button type="submit" class="btn btn-primary">Upload Asset</button>
    <a href="<?= URLROOT; ?>/admin/assets" class="btn btn-secondary">Cancel</a>
</form>

<script>
    function toggleAssetInput() {
        const type = document.getElementById('type').value;
        const fileGroup = document.getElementById('asset-file-group');
        const valueGroup = document.getElementById('asset-value-group');
        const fileInput = document.getElementById('asset_file');
        const valueInput = document.getElementById('asset_value');

        if (type === 'filter') {
            fileGroup.style.display = 'none';
            valueGroup.style.display = 'block';
            fileInput.required = false;
            valueInput.required = true;
        } else {
            fileGroup.style.display = 'block';
            valueGroup.style.display = 'none';
            fileInput.required = true;
            valueInput.required = false;
        }
    }
    // Initial call
    toggleAssetInput();
</script>