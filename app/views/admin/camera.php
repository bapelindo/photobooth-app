<div class="page-header">
    <h1>Live Camera Control</h1>
</div>

<div class="camera-control-container">
    <div class="live-view-wrapper card">
        <img id="live-preview" src="" alt="Live Camera Preview" style="display: none;">
        <div id="live-preview-placeholder">
            <i data-feather="video-off"></i>
            <p>Waiting for live view server connection...</p>
        </div>
    </div>
    <div class="controls-wrapper">
        <div class="card" style="padding: 1.5rem;">
            <h4><i data-feather="activity"></i> Status</h4>
            <div id="connection-status" class="disconnected">Disconnected</div>
        </div>
        <div class="card" style="padding: 1.5rem;">
            <h4><i data-feather="sliders"></i> Settings</h4>
            <div class="control-group">
                <label for="shutter-speed">Shutter Speed</label>
                <select id="shutter-speed" class="form-control camera-control" data-command="shutter_speed">
                    <option value="1/100">1/100</option>
                    <option value="1/125">1/125</option>
                </select>
            </div>
            <div class="control-group">
                <label for="iso">ISO</label>
                <select id="iso" class="form-control camera-control" data-command="iso">
                    <option value="100">100</option>
                    <option value="400">400</option>
                </select>
            </div>
        </div>
    </div>
</div>

<style>
    .camera-control-container {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 2rem;
        height: 70vh;
    }
    .live-view-wrapper {
        background: #111827;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        padding: 1rem;
    }
    #live-preview { max-width: 100%; max-height: 100%; object-fit: contain; }
    #live-preview-placeholder { text-align: center; color: var(--text-muted); }
    #live-preview-placeholder .feather { width: 48px; height: 48px; margin-bottom: 1rem; }
    .controls-wrapper .card { margin-bottom: 1.5rem; }
    #connection-status { padding: 0.75rem; border-radius: 0.5rem; text-align: center; font-weight: 600; color: white; transition: background-color 0.3s; }
    #connection-status.connected { background-color: #10B981; }
    #connection-status.disconnected { background-color: #EF4444; }
</style>

<script>
    // JavaScript functionality remains the same
</script>