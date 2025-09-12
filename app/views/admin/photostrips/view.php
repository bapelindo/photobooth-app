<div class="page-header">
    <h1>Photostrip Details #<?= htmlspecialchars($photostrip->id) ?></h1>
    <a href="<?= URLROOT; ?>/admin/photostrips" class="btn btn-secondary">← Back to Photostrips</a>
</div>

<div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem;">
    <div class="card" style="padding: 2rem;">
        <h2 style="margin-top: 0;">Photostrip Information</h2>
        <div class="info-grid">
            <div class="info-item">
                <label>Photostrip ID:</label>
                <span>#<?= htmlspecialchars($photostrip->id) ?></span>
            </div>
            <div class="info-item">
                <label>Session ID:</label>
                <span>#<?= htmlspecialchars($photostrip->session_id) ?></span>
            </div>
            <div class="info-item">
                <label>Frame:</label>
                <span><?= htmlspecialchars($photostrip->frame_name ?? 'N/A') ?></span>
            </div>
            <div class="info-item">
                <label>Print Status:</label>
                <span class="badge badge-<?= $photostrip->print_status === 'printed' ? 'success' : ($photostrip->print_status === 'queued' ? 'warning' : 'secondary') ?>">
                    <?= ucfirst(htmlspecialchars($photostrip->print_status ?? 'none')) ?>
                </span>
            </div>
            <div class="info-item">
                <label>Email Status:</label>
                <span class="badge badge-<?= $photostrip->email_status === 'sent' ? 'success' : ($photostrip->email_status === 'queued' ? 'warning' : 'secondary') ?>">
                    <?= ucfirst(htmlspecialchars($photostrip->email_status ?? 'none')) ?>
                </span>
            </div>
            <div class="info-item">
                <label>Email:</label>
                <span><?= htmlspecialchars($photostrip->email_address ?? 'Not provided') ?></span>
            </div>
            <div class="info-item">
                <label>Created At:</label>
                <span><?= date('M j, Y H:i:s', strtotime($photostrip->created_at)) ?></span>
            </div>
        </div>

        <div style="margin-top: 2rem;">
            <h3>Layout Configuration</h3>
            <div class="layout-config">
                <pre class="data-display"><?= htmlspecialchars($photostrip->layout_data ?? 'No layout configuration') ?></pre>
            </div>
        </div>

        <div style="margin-top: 2rem;">
            <h3>Decoration Data</h3>
            <div class="decoration-data">
                <pre class="data-display"><?= htmlspecialchars($photostrip->decoration_data ?? 'No decorations applied') ?></pre>
            </div>
        </div>

        <div class="form-actions" style="margin-top: 2rem;">
            <form action="<?= URLROOT; ?>/admin/photostrips/regenerate/<?= $photostrip->id ?>" method="POST" style="display:inline;">
                <button type="submit" class="btn btn-primary">
                    <i data-feather="refresh-cw"></i> Regenerate Photostrip
                </button>
            </form>
        </div>
    </div>

    <div>
        <?php 
        $finalImageExists = false;
        $finalImageUrl = '';
        
        if (!empty($photostrip->final_image_path)) {
            $fullPath = dirname(APPROOT) . '/public' . $photostrip->final_image_path;
            if (file_exists($fullPath)) {
                $finalImageExists = true;
                $finalImageUrl = URLROOT . $photostrip->final_image_path;
            }
        }
        ?>
        
        <?php if ($finalImageExists): ?>
            <div class="card" style="padding: 1rem;">
                <h3 style="margin-top: 0;">Final Photostrip</h3>
                <img src="<?= $finalImageUrl ?>" alt="Final Photostrip" style="width: 100%; border-radius: 0.5rem; box-shadow: var(--shadow);">
                <div style="margin-top: 1rem; font-size: 0.875rem; color: var(--text-muted);">
                    Path: <?= htmlspecialchars($photostrip->final_image_path) ?>
                </div>
            </div>
        <?php else: ?>
            <div class="card" style="padding: 1rem; text-align: center; color: var(--text-muted);">
                <h3 style="margin-top: 0;">Final Photostrip</h3>
                <div style="padding: 2rem;">
                    <p>No final photostrip generated yet.</p>
                    <?php if (!empty($photostrip->final_image_path)): ?>
                        <p style="font-size: 0.875rem;">Expected path: <?= htmlspecialchars($photostrip->final_image_path) ?></p>
                        <p style="font-size: 0.875rem;">Full path: <?= dirname(APPROOT) . '/public' . $photostrip->final_image_path ?></p>
                    <?php endif; ?>
                    <button onclick="regeneratePhotostrip(<?= $photostrip->id ?>)" class="btn btn-primary">
                        Generate Now
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .info-grid {
        display: grid;
        gap: 1rem;
    }
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
    }
    .info-item label {
        font-weight: 600;
        color: var(--text-muted);
    }
    .badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 0.375rem;
        text-transform: uppercase;
    }
    .badge-success { background-color: #dcfce7; color: #15803d; }
    .badge-warning { background-color: #fef3c7; color: #d97706; }
    .badge-secondary { background-color: #f3f4f6; color: #6b7280; }
    .data-display {
        background: var(--card-secondary);
        color: var(--text-color);
        padding: 1rem;
        border-radius: 0.5rem;
        overflow-x: auto;
        border: 1px solid var(--border-color);
    }
</style>

<script>
function regeneratePhotostrip(photostripId) {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = 'Generating...';
    btn.disabled = true;
    
    fetch('<?= URLROOT ?>/admin/photostrips/regenerate/' + photostripId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to show the new image
            window.location.reload();
        } else {
            alert('Error regenerating photostrip: ' + (data.message || 'Unknown error'));
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error regenerating photostrip: ' + error.message);
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

// Handle form submission for existing regenerate button
document.querySelector('form[action*="regenerate"]').addEventListener('submit', function(e) {
    e.preventDefault();
    const photostripId = <?= $photostrip->id ?>;
    regeneratePhotostrip(photostripId);
});
</script>