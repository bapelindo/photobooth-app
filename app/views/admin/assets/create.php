<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-feather="upload-cloud" style="color: var(--primary);"></i>
            Upload New Asset
        </h1>
        <p style="color: var(--text-muted); margin: 0; font-size: 0.875rem;">Add new frames, stickers, or filters to the photobooth.</p>
    </div>
    <div>
        <a href="<?= URLROOT; ?>/admin/assets" class="btn btn-secondary">
            <i data-feather="arrow-left"></i> Back to Assets
        </a>
    </div>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <form action="<?= URLROOT; ?>/admin/assets/store" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label for="name" class="form-label">Asset Name</label>
                <input type="text" name="name" id="name" class="form-control" required placeholder="e.g., Summer Vibe Frame">
            </div>
            
            <div class="form-group">
                <label for="type" class="form-label">Asset Type</label>
                <select name="type" id="type" class="form-control" required onchange="toggleAssetInput()">
                    <option value="frame">Frame (PNG with transparency)</option>
                    <option value="sticker">Sticker (PNG with transparency)</option>
                    <option value="filter">CSS Filter (Visual Effect)</option>
                </select>
            </div>
            
            <div class="form-group" id="asset-file-group">
                <label for="asset_file" class="form-label">Asset File</label>
                <div style="border: 2px dashed var(--border-color); border-radius: var(--radius-md); padding: 2rem 1rem; text-align: center; background-color: var(--bg-body);">
                    <i data-feather="upload" style="color: var(--text-muted); margin-bottom: 0.5rem;"></i>
                    <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1rem;">Drag and drop or click to browse (PNG, JPG, GIF)</p>
                    <input type="file" name="asset_file" id="asset_file" class="form-control" accept="image/png, image/jpeg, image/gif" style="border: none; background: transparent; box-shadow: none; padding: 0;">
                </div>
            </div>
            
            <div class="form-group" id="asset-value-group" style="display: none;">
                <label for="asset_value" class="form-label">CSS Filter Value</label>
                <input type="text" name="asset_value" id="asset_value" class="form-control" placeholder="e.g., sepia(100%) contrast(150%)">
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Enter valid CSS filter properties.</div>
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
                <a href="<?= URLROOT; ?>/admin/assets" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i data-feather="upload-cloud"></i> Upload Asset
                </button>
            </div>
        </form>
    </div>
</div>

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