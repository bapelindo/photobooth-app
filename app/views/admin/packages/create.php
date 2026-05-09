<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-feather="box" style="color: var(--primary);"></i>
            <?= isset($package) ? 'Edit Package' : 'Create New Package' ?>
        </h1>
        <p style="color: var(--text-muted); margin: 0; font-size: 0.875rem;">
            <?= isset($package) ? 'Modify the details of an existing service package.' : 'Define a new service package for your photobooth.' ?>
        </p>
    </div>
    <div>
        <a href="<?= URLROOT; ?>/admin/packages" class="btn btn-secondary">
            <i data-feather="arrow-left"></i> Back to Packages
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= isset($package) ? URLROOT.'/admin/packages/update/'.$package->id : URLROOT.'/admin/packages/store' ?>" method="POST">
            
            <div style="margin-bottom: 2rem;">
                <h3 style="font-size: 1.125rem; margin-bottom: 1.5rem; color: var(--text-main); border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">General Information</h3>
                
                <div class="form-group">
                    <label for="name" class="form-label">Package Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($package->name ?? '') ?>" required placeholder="e.g., Basic Package">
                </div>
                
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Describe what's included in this package..."><?= htmlspecialchars($package->description ?? '') ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="price" class="form-label">Price (IDR)</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-weight: 500;">Rp</span>
                        <input type="number" name="price" id="price" class="form-control" style="padding-left: 2.5rem;" value="<?= htmlspecialchars($package->price ?? '') ?>" required step="1000" placeholder="0">
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <h3 style="font-size: 1.125rem; margin-bottom: 1.5rem; color: var(--text-main); border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Limits & Quotas</h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label for="photo_limit" class="form-label">Physical Prints</label>
                        <input type="number" name="photo_limit" id="photo_limit" class="form-control" value="<?= htmlspecialchars($package->photo_limit ?? 2) ?>" required min="1" max="10">
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Number of photostrips to print.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="photo_slots" class="form-label">Photos per Strip</label>
                        <input type="number" name="photo_slots" id="photo_slots" class="form-control" value="<?= htmlspecialchars($package->photo_slots ?? 4) ?>" required min="1" max="8">
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Number of photos within one layout frame.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="frame_limit" class="form-label">Frame Selections</label>
                        <input type="number" name="frame_limit" id="frame_limit" class="form-control" value="<?= htmlspecialchars($package->frame_limit ?? DEFAULT_FRAME_LIMIT) ?>" required min="1" max="5">
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Number of frames a user can choose.</div>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <h3 style="font-size: 1.125rem; margin-bottom: 1.5rem; color: var(--text-main); border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Session Settings</h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label for="session_duration" class="form-label">Session Duration (Seconds)</label>
                        <input type="number" name="session_duration" id="session_duration" class="form-control" value="<?= htmlspecialchars($package->session_duration ?? DEFAULT_SESSION_DURATION) ?>" required min="60" max="1800" step="30">
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Total time limit for the entire photo session.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="max_save_photos" class="form-label">Max Digital Saves</label>
                        <input type="number" name="max_save_photos" id="max_save_photos" class="form-control" value="<?= htmlspecialchars($package->max_save_photos ?? DEFAULT_MAX_SAVE_PHOTOS) ?>" required min="5" max="100">
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Max number of digital photos a user can save.</div>
                    </div>

                    <div class="form-group">
                        <label for="retake_limit" class="form-label">Retake Limit <span class="badge badge-warning" style="margin-left: 0.5rem;">Legacy</span></label>
                        <input type="number" name="retake_limit" id="retake_limit" class="form-control" value="<?= htmlspecialchars($package->retake_limit ?? 0) ?>" min="0">
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Legacy setting - ignored in current workflow.</div>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                <a href="<?= URLROOT; ?>/admin/packages" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i data-feather="save"></i> <?= isset($package) ? 'Update Package' : 'Create Package' ?>
                </button>
            </div>
        </form>
    </div>
</div>