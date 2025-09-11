<?php require APPROOT . '/views/admin/layouts/header.php'; ?>

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
        <div class="card control-card">
            <h4><i data-feather="activity"></i> Status & Actions</h4>
            <div id="connection-status" class="disconnected">Disconnected</div>
            <button id="take-photo-btn" class="btn btn-primary" style="width: 100%; margin-top: 1rem;" disabled>
                <i data-feather="camera"></i> Take Photo
            </button>
        </div>

        <div class="card control-card">
            <h4><i data-feather="sliders"></i> Exposure Settings</h4>
            <div class="control-grid">
                <div class="control-group">
                    <label for="aperture">Aperture (F-Stop)</label>
                    <select id="aperture" class="form-control camera-control" data-command="aperture">
                        <option value="1.8">f/1.8</option>
                        <option value="2.8">f/2.8</option>
                        <option value="4.0">f/4.0</option>
                        <option value="5.6">f/5.6</option>
                        <option value="8.0">f/8.0</option>
                    </select>
                </div>
                <div class="control-group">
                    <label for="shutter-speed">Shutter Speed</label>
                    <select id="shutter-speed" class="form-control camera-control" data-command="shutter_speed">
                        <option value="1/100">1/100s</option>
                        <option value="1/125">1/125s</option>
                        <option value="1/250">1/250s</option>
                        <option value="1/500">1/500s</option>
                    </select>
                </div>
                <div class="control-group">
                    <label for="iso">ISO</label>
                    <select id="iso" class="form-control camera-control" data-command="iso">
                        <option value="100">100</option>
                        <option value="400">400</option>
                        <option value="800">800</option>
                        <option value="1600">1600</option>
                    </select>
                </div>
                 <div class="control-group">
                    <label for="exposure-comp">Exposure Comp.</label>
                    <select id="exposure-comp" class="form-control camera-control" data-command="exposure_compensation">
                        <option value="-2.0">-2.0</option>
                        <option value="-1.0">-1.0</option>
                        <option value="0.0">0.0</option>
                        <option value="+1.0">+1.0</option>
                        <option value="+2.0">+2.0</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card control-card">
            <h4><i data-feather="settings"></i> Other Settings</h4>
            <div class="control-grid">
                <div class="control-group">
                    <label for="white-balance">White Balance</label>
                    <select id="white-balance" class="form-control camera-control" data-command="white_balance">
                        <option value="auto">Auto</option>
                        <option value="daylight">Daylight</option>
                        <option value="flash">Flash</option>
                        <option value="cloudy">Cloudy</option>
                    </select>
                </div>
                <div class="control-group">
                    <label for="focus-mode">Focus Mode</label>
                    <select id="focus-mode" class="form-control camera-control" data-command="focus_mode">
                        <option value="af-s">AF-S (Single)</option>
                        <option value="af-c">AF-C (Continuous)</option>
                        <option value="mf">Manual</option>
                    </select>
                </div>
                 <div class="control-group">
                    <label for="drive-mode">Drive Mode</label>
                    <select id="drive-mode" class="form-control camera-control" data-command="drive_mode">
                        <option value="single">Single Shot</option>
                        <option value="continuous">Continuous</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .camera-control-container {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 2rem;
        height: 80vh; /* Increased height */
    }
    .live-view-wrapper {
        background: var(--bg-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        padding: 1rem;
        flex-direction: column;
    }
    #live-preview { max-width: 100%; max-height: 100%; object-fit: contain; }
    #live-preview-placeholder { text-align: center; color: var(--text-muted); }
    #live-preview-placeholder .feather { width: 48px; height: 48px; margin-bottom: 1rem; }
    
    .controls-wrapper {
        height: 100%;
        overflow-y: auto;
        padding-right: 10px; /* Space for scrollbar */
    }
    .control-card { 
        margin-bottom: 1.5rem; 
        padding: 1.5rem;
    }
    .control-card h4 {
        margin-top: 0;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .control-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .control-group {
        display: flex;
        flex-direction: column;
    }
    /* Make last item span both columns if odd number of items */
    .control-grid .control-group:last-child:nth-child(odd) {
        grid-column: span 2;
    }
    
    #connection-status { padding: 0.75rem; border-radius: 0.5rem; text-align: center; font-weight: 600; color: white; transition: background-color 0.3s; }
    #connection-status.connected { background-color: var(--success-color); }
    #connection-status.disconnected { background-color: var(--error-color); }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const livePreviewImg = document.getElementById('live-preview');
    const placeholder = document.getElementById('live-preview-placeholder');
    const statusDiv = document.getElementById('connection-status');
    const takePhotoButton = document.getElementById('take-photo-btn');
    const websocketUrl = '<?= $data['live_view_websocket_url']; ?>';
    let socket;

    function connect() {
        socket = new WebSocket(websocketUrl);

        socket.onopen = () => {
            console.log('Admin WebSocket connected!');
            placeholder.style.display = 'none';
            livePreviewImg.style.display = 'block';
            statusDiv.textContent = 'Connected';
            statusDiv.className = 'connected';
            takePhotoButton.disabled = false;
        };

        socket.onmessage = (event) => {
            if (event.data instanceof Blob) {
                const objectURL = URL.createObjectURL(event.data);
                livePreviewImg.src = objectURL;
                livePreviewImg.onload = () => { URL.revokeObjectURL(objectURL); }
            } else {
                const response = JSON.parse(event.data);
                console.log('Response from server:', response);
                // You can add logic here to handle responses, e.g., photo taken confirmation
            }
        };

        socket.onclose = () => {
            console.log('Admin WebSocket disconnected. Reconnecting...');
            placeholder.style.display = 'block';
            livePreviewImg.style.display = 'none';
            statusDiv.textContent = 'Disconnected';
            statusDiv.className = 'disconnected';
            takePhotoButton.disabled = true;
            setTimeout(connect, 3000);
        };

        socket.onerror = (err) => {
            console.error('WebSocket error:', err);
            socket.close();
        };
    }

    function sendCameraCommand(command, value) {
        if (socket && socket.readyState === WebSocket.OPEN) {
            const message = JSON.stringify({ type: 'control', command, value });
            socket.send(message);
            console.log(`Sent command: ${command}, value: ${value}`);
        } else {
            console.error('WebSocket is not connected. Cannot send command.');
        }
    }

    // Event listener for all dropdown controls
    document.querySelectorAll('.camera-control').forEach(control => {
        control.addEventListener('change', (event) => {
            sendCameraCommand(event.target.dataset.command, event.target.value);
        });
    });

    // Event listener for the Take Photo button
    takePhotoButton.addEventListener('click', () => {
        if (socket && socket.readyState === WebSocket.OPEN) {
            const message = JSON.stringify({ type: 'action', command: 'take_photo' });
            socket.send(message);
            console.log('Sent command: take_photo');
            // Optional: Show some feedback to the user
            takePhotoButton.textContent = 'Capturing...';
            setTimeout(() => { takePhotoButton.innerHTML = '<i data-feather="camera"></i> Take Photo'; feather.replace(); }, 1000);
        } else {
             console.error('WebSocket is not connected. Cannot take photo.');
        }
    });

    connect(); // Initial connection
});
</script>