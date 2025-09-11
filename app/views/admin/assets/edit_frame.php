<?php require APPROOT . '/views/admin/layouts/header.php'; ?>

<style>
    .editor-wrapper {
        max-width: 800px;
        margin: auto;
        padding: 1rem;
    }
    #editor-container {
        position: relative;
        width: 300px; /* Fixed width for a predictable canvas */
        margin: 1rem auto;
        border: 2px solid #ccc;
        aspect-ratio: 2 / 6; /* Enforcing 2x6 inch aspect ratio */
        background-color: #f0f0f0;
    }
    #frame-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 1;
    }
    .slot {
        position: absolute;
        box-sizing: border-box;
        border: 2px dashed #fff;
        background-color: rgba(0, 150, 255, 0.5);
        cursor: move;
        z-index: 10;
        color: white;
        font-size: 24px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        text-shadow: 1px 1px 2px black;
    }
    .resizer {
        position: absolute;
        width: 10px;
        height: 10px;
        background: #fff;
        border: 1px solid #333;
        z-index: 11;
    }
    .resizer.bottom-right {
        bottom: -5px;
        right: -5px;
        cursor: se-resize;
    }
    .controls {
        text-align: center;
        margin-top: 1rem;
        padding: 1rem;
        background-color: #f7f7f7;
        border-radius: 8px;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .slot-controls button {
        margin: 0 5px;
    }
</style>

<div class="editor-wrapper">
    <h2>Edit Slots for: <?= htmlspecialchars($data['asset']->name) ?></h2>

    <div class="controls">
        <div class="form-group">
            <label for="slot-count-input">Number of Slots:</label>
            <input type="number" id="slot-count-input" value="<?= $data['asset']->slot_count ?? 1 ?>" min="0" max="10" class="form-control" style="width: 100px; display: inline-block;" readonly>
        </div>
        <div class="form-group slot-controls">
            <button id="add-slot-btn" class="btn btn-success">+ Add Slot</button>
            <button id="remove-slot-btn" class="btn btn-warning">- Remove Last</button>
            <button id="generate-slots-btn" class="btn btn-info">Reset & Generate</button>
        </div>
        <button id="save-frame-btn" class="btn btn-primary">Save Frame Data</button>
    </div>

    <div id="editor-container">
        <img id="frame-bg" src="<?= URLROOT . htmlspecialchars($data['asset']->path) ?>" alt="Frame Background">
        <!-- Slots will be generated here by JavaScript -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const editor = document.getElementById('editor-container');
    const generateBtn = document.getElementById('generate-slots-btn');
    const saveBtn = document.getElementById('save-frame-btn');
    const countInput = document.getElementById('slot-count-input');
    const addSlotBtn = document.getElementById('add-slot-btn');
    const removeSlotBtn = document.getElementById('remove-slot-btn');
    const assetId = <?= $data['asset']->id; ?>;
    let slots = [];

    function makeDraggable(element) {
        let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
        element.onmousedown = dragMouseDown;

        function dragMouseDown(e) {
            e.preventDefault();
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = closeDragElement;
            document.onmousemove = elementDrag;
        }

        function elementDrag(e) {
            e.preventDefault();
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
            element.style.top = (element.offsetTop - pos2) + "px";
            element.style.left = (element.offsetLeft - pos1) + "px";
        }

        function closeDragElement() {
            // Clean up all mouse event listeners
            document.onmouseup = null;
            document.onmousemove = null;
            document.ontouchend = null;
            document.ontouchmove = null;
        }
    }

    function makeResizable(element) {
        const resizer = document.createElement('div');
        resizer.className = 'resizer bottom-right';
        element.appendChild(resizer);

        resizer.onmousedown = initResize;

        function initResize(e) {
            e.stopPropagation();
            window.addEventListener('mousemove', resize);
            window.addEventListener('mouseup', stopResize);
        }

        function resize(e) {
            element.style.width = (e.clientX - element.getBoundingClientRect().left) + 'px';
            element.style.height = (e.clientY - element.getBoundingClientRect().top) + 'px';
        }

        function stopResize() {
            window.removeEventListener('mousemove', resize);
            window.removeEventListener('mouseup', stopResize);
        }
    }

    function updateSlotCount() {
        countInput.value = slots.length;
    }

    function clearSlots() {
        slots.forEach(s => s.remove());
        slots = [];
        updateSlotCount();
    }

    function addOneSlot() {
        const index = slots.length + 1;
        const defaultHeight = 15;
        const defaultTop = 5 + ((index - 1) * (defaultHeight + 5)); // Stagger new slots
        const coords = { top: defaultTop, left: 10, width: 80, height: defaultHeight };
        createSlot(coords, index);
    }

    function removeLastSlot() {
        if (slots.length > 0) {
            const lastSlot = slots.pop();
            lastSlot.remove();
            updateSlotCount();
        }
    }

    function generateSlots() {
        const numToGen = prompt("Enter number of slots to generate:", countInput.value);
        const count = parseInt(numToGen, 10);
        if (isNaN(count) || count < 0) return;
        
        clearSlots();
        countInput.value = count;

        for (let i = 0; i < count; i++) {
            const defaultHeight = 15;
            const defaultTop = 5 + (i * (defaultHeight + 5));
            const coords = { top: defaultTop, left: 10, width: 80, height: defaultHeight };
            createSlot(coords, i + 1);
        }
    }

    function loadExistingSlots() {
        const existingSlots = JSON.parse(<?= json_encode($data['asset']->slot_coordinates ?? '[]') ?>);
        if (existingSlots.length > 0) {
            countInput.value = existingSlots.length;
            existingSlots.forEach((coord, index) => createSlot(coord, index + 1));
        } else {
            updateSlotCount();
        }
    }

    function createSlot(coords, index) {
        const slot = document.createElement('div');
        slot.className = 'slot';
        slot.textContent = index;
        slot.style.top = `${coords.top}%`;
        slot.style.left = `${coords.left}%`;
        slot.style.width = `${coords.width}%`;
        slot.style.height = `${coords.height}%`;

        makeDraggable(slot);
        makeResizable(slot);
        
        editor.appendChild(slot);
        slots.push(slot);
        updateSlotCount();

        slot.addEventListener('contextmenu', (e) => {
            e.preventDefault();
            if (confirm('Delete this slot?')) {
                const slotIndex = slots.indexOf(slot);
                if(slotIndex > -1) {
                    slots.splice(slotIndex, 1);
                }
                slot.remove();
                updateSlotCount();
            }
        });
    }

    addSlotBtn.addEventListener('click', addOneSlot);
    removeSlotBtn.addEventListener('click', removeLastSlot);
    generateBtn.addEventListener('click', generateSlots);
    saveBtn.addEventListener('click', async () => {
        const coordinates = slots.map(slot => {
            const parentRect = editor.getBoundingClientRect();
            const rect = slot.getBoundingClientRect();
            return {
                top: ((rect.top - parentRect.top) / parentRect.height) * 100,
                left: ((rect.left - parentRect.left) / parentRect.width) * 100,
                width: (rect.width / parentRect.width) * 100,
                height: (rect.height / parentRect.height) * 100,
            };
        });
        const slotCount = slots.length;

        try {
            const response = await fetch('<?= URLROOT; ?>/admin/assets/ajax_save_frame_data', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    asset_id: assetId, 
                    slot_count: slotCount,
                    coordinates: coordinates 
                })
            });
            const result = await response.json();
            if (result.success) {
                alert('Frame data saved successfully!');
                window.location.href = '<?= URLROOT; ?>/admin/assets';
            } else {
                throw new Error(result.message || 'Failed to save.');
            }
        } catch (err) {
            alert('Error: ' + err.message);
            console.error(err);
        }
    });

    // Initial load
    loadExistingSlots();
});
</script>

<?php require APPROOT . '/views/admin/layouts/footer.php'; ?>
