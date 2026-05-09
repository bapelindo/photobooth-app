<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-feather="layout" style="color: var(--primary);"></i>
            Edit Frame Slots
        </h1>
        <p style="color: var(--text-muted); margin: 0; font-size: 0.875rem;">Define the photo placement slots for <strong style="color: var(--text-main);"><?= htmlspecialchars($data['asset']->name) ?></strong>.</p>
    </div>
    <div>
        <a href="<?= URLROOT; ?>/admin/assets" class="btn btn-secondary">
            <i data-feather="arrow-left"></i> Back to Assets
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem; align-items: start;">
    
    <!-- Editor Card -->
    <div class="card">
        <div class="card-header" style="background-color: var(--bg-body);">
            <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;">
                <i data-feather="edit-3" style="width: 18px;"></i> Visual Editor
            </h3>
        </div>
        <div class="card-body" style="display: flex; justify-content: center; background-color: var(--bg-surface-hover); overflow: hidden;">
            <div id="editor-container" style="position: relative; width: 300px; aspect-ratio: 2 / 6; background-color: #e2e8f0; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); border-radius: 4px; overflow: hidden;">
                <img id="frame-bg" src="<?= URLROOT . htmlspecialchars($data['asset']->path) ?>" alt="Frame Background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; pointer-events: none;">
                <!-- Slots generated here -->
            </div>
        </div>
    </div>

    <!-- Controls Card -->
    <div class="card">
        <div class="card-header" style="background-color: var(--bg-body);">
            <h3 class="card-title" style="display: flex; align-items: center; gap: 0.5rem;">
                <i data-feather="sliders" style="width: 18px;"></i> Configuration
            </h3>
        </div>
        <div class="card-body" style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <div>
                <label for="slot-count-input" class="form-label">Total Slots</label>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <input type="number" id="slot-count-input" value="<?= $data['asset']->slot_count ?? 1 ?>" min="0" max="10" class="form-control" style="width: 100px; background-color: var(--bg-body);" readonly>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Current active slots</span>
                </div>
            </div>

            <div style="border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                <label class="form-label" style="margin-bottom: 0.75rem;">Slot Management</label>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <button id="add-slot-btn" class="btn btn-secondary" style="width: 100%; justify-content: flex-start;">
                        <i data-feather="plus-circle" style="color: var(--success);"></i> Add 1 Slot
                    </button>
                    <button id="remove-slot-btn" class="btn btn-secondary" style="width: 100%; justify-content: flex-start;">
                        <i data-feather="minus-circle" style="color: var(--warning);"></i> Remove Last Slot
                    </button>
                    <button id="generate-slots-btn" class="btn btn-secondary" style="width: 100%; justify-content: flex-start;">
                        <i data-feather="refresh-cw" style="color: var(--primary);"></i> Auto Generate
                    </button>
                </div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 1rem;">
                    <i data-feather="info" style="width: 12px; height: 12px;"></i> Drag to move. Use bottom-right corner to resize. Right-click a slot to delete.
                </div>
            </div>

            <div style="border-top: 1px solid var(--border-color); padding-top: 1.5rem; margin-top: auto;">
                <button id="save-frame-btn" class="btn btn-primary" style="width: 100%;">
                    <i data-feather="save"></i> Save Frame Data
                </button>
            </div>
        </div>
    </div>

</div>

<style>
    .slot {
        position: absolute; box-sizing: border-box;
        border: 2px dashed #ffffff; background-color: rgba(37, 99, 235, 0.4);
        cursor: move; z-index: 10;
        color: white; font-size: 1.5rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        text-shadow: 0 1px 3px rgba(0,0,0,0.8);
        box-shadow: inset 0 0 0 1px rgba(0,0,0,0.2);
        transition: background-color 0.2s;
    }
    .slot:hover { background-color: rgba(37, 99, 235, 0.6); }
    .resizer { position: absolute; width: 12px; height: 12px; background: white; border: 2px solid var(--primary); border-radius: 50%; z-index: 11; }
    .resizer.bottom-right { bottom: -6px; right: -6px; cursor: se-resize; }
    
    @media (max-width: 900px) {
        div[style*="grid-template-columns: 1fr 350px;"] { grid-template-columns: 1fr !important; }
    }
</style>

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
            document.onmouseup = null;
            document.onmousemove = null;
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

    function updateSlotCount() { countInput.value = slots.length; }

    function clearSlots() {
        slots.forEach(s => s.remove());
        slots = [];
        updateSlotCount();
    }

    function addOneSlot() {
        const index = slots.length + 1;
        const defaultHeight = 15;
        const defaultTop = 5 + ((index - 1) * (defaultHeight + 5)); 
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
        const numToGen = prompt("Enter number of slots to generate (e.g., 4):", countInput.value);
        if (numToGen === null) return;
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
        if (existingSlots && existingSlots.length > 0) {
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
                if(slotIndex > -1) slots.splice(slotIndex, 1);
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
                body: JSON.stringify({ asset_id: assetId, slot_count: slotCount, coordinates: coordinates })
            });
            const result = await response.json();
            if (result.success) {
                if(typeof showToast === 'function') showToast('Frame data saved successfully!', 'success');
                else alert('Saved successfully!');
                setTimeout(() => window.location.href = '<?= URLROOT; ?>/admin/assets', 1000);
            } else {
                throw new Error(result.message || 'Failed to save.');
            }
        } catch (err) {
            if(typeof showToast === 'function') showToast('Error: ' + err.message, 'error');
            else alert('Error: ' + err.message);
            console.error(err);
        }
    });

    loadExistingSlots();
});
</script>