<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-feather="camera" style="color: var(--primary);"></i>
            Live Camera Control
        </h1>
        <p style="color: var(--text-muted); margin: 0; font-size: 0.875rem;">Remotely view and adjust camera settings.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem; align-items: start;">
    
    <!-- Live View -->
    <div class="card" style="height: 100%;">
        <div class="card-header" style="background-color: var(--bg-body);">
            <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;"><i data-feather="video" style="width: 18px;"></i> Live Preview</h3>
        </div>
        <div class="card-body" style="background-color: #0f172a; height: 600px; display: flex; align-items: center; justify-content: center; position: relative; border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
            <img id="live-preview" src="" alt="Live Camera Preview" style="display: none; max-width: 100%; max-height: 100%; object-fit: contain;">
            <div id="live-preview-placeholder" style="text-align: center; color: #64748b; display: flex; flex-direction: column; align-items: center;">
                <i data-feather="video-off" style="width: 48px; height: 48px; margin-bottom: 1rem; opacity: 0.5;"></i>
                <p style="margin: 0; font-weight: 500;">Waiting for live view server connection...</p>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;"><i data-feather="activity" style="width: 18px;"></i> Status & Actions</h3>
            </div>
            <div class="card-body">
                <div id="connection-status" style="padding: 0.75rem; border-radius: var(--radius-md); text-align: center; font-weight: 600; color: white; background-color: var(--danger); transition: background-color 0.3s; margin-bottom: 1rem;">
                    Disconnected
                </div>
                <button id="take-photo-btn" class="btn btn-primary" style="width: 100%; justify-content: center;" disabled>
                    <i data-feather="camera"></i> Take Photo
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;"><i data-feather="sliders" style="width: 18px;"></i> Exposure Settings</h3>
            </div>
            <div class="card-body" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group" style="margin: 0;">
                    <label for="aperture" class="form-label" style="font-size: 0.75rem;">Aperture (F-Stop)</label>
                    <select id="aperture" class="form-control camera-control" data-command="aperture">
                        <option value="1.8">f/1.8</option>
                        <option value="2.8">f/2.8</option>
                        <option value="4.0">f/4.0</option>
                        <option value="5.6">f/5.6</option>
                        <option value="8.0">f/8.0</option>
                    </select>
                </div>
                <div class="form-group" style="margin: 0;">
                    <label for="shutter-speed" class="form-label" style="font-size: 0.75rem;">Shutter Speed</label>
                    <select id="shutter-speed" class="form-control camera-control" data-command="shutter_speed">
                        <option value="1/100">1/100s</option>
                        <option value="1/125">1/125s</option>
                        <option value="1/250">1/250s</option>
                        <option value="1/500">1/500s</option>
                    </select>
                </div>
                <div class="form-group" style="margin: 0;">
                    <label for="iso" class="form-label" style="font-size: 0.75rem;">ISO</label>
                    <select id="iso" class="form-control camera-control" data-command="iso">
                        <option value="100">100</option>
                        <option value="400">400</option>
                        <option value="800">800</option>
                        <option value="1600">1600</option>
                    </select>
                </div>
                 <div class="form-group" style="margin: 0;">
                    <label for="exposure-comp" class="form-label" style="font-size: 0.75rem;">Exposure Comp.</label>
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

        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;"><i data-feather="settings" style="width: 18px;"></i> Other Settings</h3>
            </div>
            <div class="card-body" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group" style="margin: 0; grid-column: span 2;">
                    <label for="white-balance" class="form-label" style="font-size: 0.75rem;">White Balance</label>
                    <select id="white-balance" class="form-control camera-control" data-command="white_balance">
                        <option value="auto">Auto</option>
                        <option value="daylight">Daylight</option>
                        <option value="flash">Flash</option>
                        <option value="cloudy">Cloudy</option>
                    </select>
                </div>
                <div class="form-group" style="margin: 0;">
                    <label for="focus-mode" class="form-label" style="font-size: 0.75rem;">Focus Mode</label>
                    <select id="focus-mode" class="form-control camera-control" data-command="focus_mode">
                        <option value="af-s">AF-S</option>
                        <option value="af-c">AF-C</option>
                        <option value="mf">Manual</option>
                    </select>
                </div>
                 <div class="form-group" style="margin: 0;">
                    <label for="drive-mode" class="form-label" style="font-size: 0.75rem;">Drive Mode</label>
                    <select id="drive-mode" class="form-control camera-control" data-command="drive_mode">
                        <option value="single">Single</option>
                        <option value="continuous">Continuous</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 1024px) {
        div[style*="grid-template-columns: 1fr 350px;"] { grid-template-columns: 1fr !important; }
        .card-body[style*="height: 600px;"] { height: 400px !important; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const livePreviewImg = document.getElementById('live-preview');
    const placeholder = document.getElementById('live-preview-placeholder');
    const statusDiv = document.getElementById('connection-status');
    const takePhotoButton = document.getElementById('take-photo-btn');
    const websocketUrl = '<?= $data['live_view_websocket_url'] ?? ''; ?>';
    let socket;

    function connect() {
        if(!websocketUrl) return;
        
        socket = new WebSocket(websocketUrl);

        socket.onopen = () => {
            console.log('Admin WebSocket connected!');
            placeholder.style.display = 'none';
            livePreviewImg.style.display = 'block';
            statusDiv.textContent = 'Connected';
            statusDiv.style.backgroundColor = 'var(--success)';
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
            }
        };

        socket.onclose = () => {
            console.log('Admin WebSocket disconnected. Reconnecting...');
            placeholder.style.display = 'flex';
            livePreviewImg.style.display = 'none';
            statusDiv.textContent = 'Disconnected';
            statusDiv.style.backgroundColor = 'var(--danger)';
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

    document.querySelectorAll('.camera-control').forEach(control => {
        control.addEventListener('change', (event) => {
            sendCameraCommand(event.target.dataset.command, event.target.value);
        });
    });

    takePhotoButton.addEventListener('click', () => {
        if (socket && socket.readyState === WebSocket.OPEN) {
            const message = JSON.stringify({ type: 'action', command: 'take_photo' });
            socket.send(message);
            console.log('Sent command: take_photo');
            
            const originalHtml = takePhotoButton.innerHTML;
            takePhotoButton.textContent = 'Capturing...';
            setTimeout(() => { 
                takePhotoButton.innerHTML = originalHtml; 
                if(typeof feather !== 'undefined') feather.replace(); 
            }, 1000);
        } else {
             console.error('WebSocket is not connected. Cannot take photo.');
             if(typeof showToast === 'function') showToast('WebSocket not connected', 'error');
        }
    });

    connect();
});
</script>