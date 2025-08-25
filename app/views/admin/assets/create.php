<h1>Upload New Asset</h1>

<form action="<?= URLROOT; ?>/admin/assets/store" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Asset Name</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="type">Asset Type</label>
        <select name="type" id="type" class="form-control" required>
            <option value="frame">Frame</option>
            <option value="sticker">Sticker</option>
            <option value="filter">Filter</option>
        </select>
    </div>
    <div class="form-group">
        <label for="asset_file">Asset File (PNG recommended)</label>
        <input type="file" name="asset_file" id="asset_file" class="form-control" required accept="image/png, image/jpeg, image/gif">
    </div>
    <button type="submit" class="btn btn-primary">Upload Asset</button>
    <a href="<?= URLROOT; ?>/admin/assets" class="btn btn-secondary">Cancel</a>
</form>