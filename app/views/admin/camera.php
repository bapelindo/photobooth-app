<?php require APPROOT . '/views/admin/layouts/header.php'; ?>

<style>
    .camera-control-container {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 2rem;
        height: 75vh;
    }
    .live-view-wrapper {
        background: #000;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }
    #live-preview {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    .controls-wrapper .card {
        margin-bottom: 1.5rem;
    }
    .control-group { margin-bottom: 1rem; }
    .control-group label { display: block; margin-bottom: .5rem; font-weight: 500; }
    .form-control { width: 100%; padding: .5rem; font-size: 1rem; }
    #connection-status {
        padding: 0.5rem;
        border-radius: 5px;
        text-align: center;
        font-weight: bold;
        color: white;
    }
    #connection-status.connected { background-color: #28a745; }
    #connection-status.disconnected { background-color: #dc3545; }
</style>

<div class="camera-control-container">
    <div class="live-view-wrapper">
        <img id="live-preview" src="" alt="Live Preview Kamera" style="display: none;">
        <p id="live-preview-placeholder" style="color: white;">Menunggu koneksi dari server live view...</p>
    </div>
    <div class="controls-wrapper">
        <div class="card">
            <h4>Status</h4>
            <div id="connection-status" class="disconnected">Terputus</div>
        </div>
        <div class="card">
            <h4>Pengaturan</h4>
            <div class="control-group">
                <label for="shutter-speed">Shutter Speed</label>
                <select id="shutter-speed" class="form-control camera-control" data-command="shutter_speed">
                    <option value="1/100">1/100</option>
                    <option value="1/125">1/125</option>
                    <option value="1/250">1/250</option>
                    <option value="1/500">1/500</option>
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
            </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const livePreviewImg = document.getElementById('live-preview');
    const placeholder = document.getElementById('live-preview-placeholder');
    const statusDiv = document.getElementById('connection-status');
    const websocketUrl = '<?= $data['live_view_websocket_url']; ?>';
    let socket;

    function connect() {
        socket = new WebSocket(websocketUrl);

        socket.onopen = () => {
            console.log('Admin WebSocket terhubung!');
            placeholder.style.display = 'none';
            livePreviewImg.style.display = 'block';
            statusDiv.textContent = 'Terhubung';
            statusDiv.className = 'connected';
        };

        socket.onmessage = (event) => {
            if (event.data instanceof Blob) {
                const objectURL = URL.createObjectURL(event.data);
                livePreviewImg.src = objectURL;
                livePreviewImg.onload = () => { URL.revokeObjectURL(objectURL); }
            } else {
                console.log('Respons dari server:', JSON.parse(event.data));
            }
        };

        socket.onclose = () => {
            console.log('Admin WebSocket terputus. Mencoba menghubungkan kembali...');
            placeholder.style.display = 'block';
            livePreviewImg.style.display = 'none';
            statusDiv.textContent = 'Terputus';
            statusDiv.className = 'disconnected';
            setTimeout(connect, 3000);
        };

        socket.onerror = (err) => {
            console.error('WebSocket error:', err);
            socket.close();
        };
    }

    function sendCameraCommand(command, value) {
        if (socket && socket.readyState === WebSocket.OPEN) {
            const message = JSON.stringify({ command, value });
            socket.send(message);
        } else {
            console.error('WebSocket tidak terhubung untuk mengirim perintah.');
        }
    }

    document.querySelectorAll('.camera-control').forEach(control => {
        control.addEventListener('change', (event) => {
            sendCameraCommand(event.target.dataset.command, event.target.value);
        });
    });

    connect();
});
</script>

<?php require APPROOT . '/views/admin/layouts/footer.php'; ?>