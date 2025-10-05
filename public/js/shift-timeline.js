// --- UTILITY FUNCTIONS ---
function generateWorkerColor(workerId) {
    const defaultColors = [
        "#3B82F6","#EF4444","#10B981","#F59E0B","#8B5CF6",
        "#EC4899","#06B6D4","#84CC16","#F97316","#6366F1",
        "#14B8A6","#F43F5E","#0EA5E9","#A855F7","#84CC16",
        "#F472B6","#60A5FA","#34D399","#FBBF24","#A78BFA"
    ];

    // Find worker from global list
    const worker = window.appWorkers?.find(w => w.id === workerId);

    // If worker has a position and we have a color for it, use that
    if (worker && worker.position && window.positionColors[worker.position]) {
        return window.positionColors[worker.position];
    }

    // Otherwise fallback to rotating palette
    return defaultColors[workerId % defaultColors.length];
}


function getFlightColor(flightType) {
    return flightType === "arrival" ? "#10B981" : "#EF4444";
}

function getContrastColor(hexColor) {
    const r = parseInt(hexColor.substr(1,2),16);
    const g = parseInt(hexColor.substr(3,2),16);
    const b = parseInt(hexColor.substr(5,2),16);
    const brightness = (r*299 + g*587 + b*114)/1000;
    return brightness > 128 ? "#000000" : "#FFFFFF";
}

function darkenColor(hexColor, percent) {
    let r = parseInt(hexColor.substr(1,2),16);
    let g = parseInt(hexColor.substr(3,2),16);
    let b = parseInt(hexColor.substr(5,2),16);
    r = Math.max(0, Math.min(255, r*(1-percent/100)));
    g = Math.max(0, Math.min(255, g*(1-percent/100)));
    b = Math.max(0, Math.min(255, b*(1-percent/100)));
    return `#${Math.round(r).toString(16).padStart(2,"0")}${Math.round(g).toString(16).padStart(2,"0")}${Math.round(b).toString(16).padStart(2,"0")}`;
}

function toDateTimeLocal(value, isTimeOnly=false) {
    if(!value) return "";
    if(isTimeOnly) return value;
    if(value.includes("T")) return value.slice(0,16);
    if(value.includes(" ")) return value.replace(" ","T").slice(0,16);
    if(/^\d{1,2}:\d{2}$/.test(value)){
        const today = new Date().toISOString().split("T")[0];
        return `${today}T${value}`;
    }
    return "";
}

function parseTime(str) {
    if(str.includes("T")) str = str.split("T")[1];
    return str.split(":").map(Number);
}

// --- GLOBALS ---
let currentShifts = [];

// --- MAIN SCRIPT ---
document.addEventListener("DOMContentLoaded", function(){

    const workerColors = {};
    const dateInput = document.getElementById("shift-date");

    if(window.appWorkers){
        window.appWorkers.forEach(w => workerColors[w.id] = generateWorkerColor(w.id));
    }

    loadDataForDate(dateInput.value);

    // --- DATE NAVIGATION ---
    document.getElementById("prev-day").addEventListener("click", ()=>navigateDate(-1));
    document.getElementById("next-day").addEventListener("click", ()=>navigateDate(1));
    dateInput.addEventListener("change", ()=>loadDataForDate(dateInput.value));

    // --- MODAL HANDLING ---
    const modal = document.getElementById("shift-modal");
    function closeModal(){ if(modal) modal.classList.add("hidden"); }

    modal.addEventListener("click", e=>{
        if(e.target.id === "shift-modal") closeModal();
    });

    function navigateDate(days){
        const date = new Date(dateInput.value);
        date.setDate(date.getDate()+days);
        dateInput.value = date.toISOString().split("T")[0];
        loadDataForDate(dateInput.value);
    }

    function updateDateDisplay(date){
        const dateDisplay = document.querySelector(".text-sm.text-gray-500");
        if(dateDisplay){
            dateDisplay.textContent = new Date(date).toLocaleDateString("en-US",{
                month:"long", day:"numeric", year:"numeric"
            });
        }
    }

    function generateTimeSlots(container){
        container.innerHTML="";
        const slotWidth = 40;
        container.style.position = "relative";
        container.style.height = "50px";
        container.style.width = (96*slotWidth) + "px";
        for(let i=0;i<96;i++){
            const slot = document.createElement("div");
            slot.className="time-slot";
            slot.style.width=slotWidth+"px";
            slot.style.display="inline-block";
            slot.style.position="relative";
            container.appendChild(slot);
        }
    }

    // --- LOAD DATA ---
    function loadDataForDate(date){
        fetch(`./shifts/data?date=${date}`)
            .then(r=>r.json())
            .then(shifts=>{
                currentShifts = shifts;
                renderShifts(shifts);
                updateDateDisplay(date);
            }).catch(err=>console.error("Error loading shifts:",err));

        fetch(`./flights/data?date=${date}`)
            .then(r=>r.json())
            .then(flights=>renderFlights(flights))
            .catch(err=>console.error("Error loading flights:",err));
    }

    // --- RENDER SHIFTS ---
    function renderShifts(shifts){
        document.querySelectorAll(".shift-block").forEach(el=>el.remove());
        const shiftsByWorker={};
        shifts.forEach(s=>{ 
            if(!shiftsByWorker[s.worker_id]) shiftsByWorker[s.worker_id]=[]; 
            shiftsByWorker[s.worker_id].push(s); 
        });

        Object.keys(shiftsByWorker).forEach(workerId=>{
            const workerRow = document.querySelector(`.worker-row[data-worker-id="${workerId}"]`);
            if(!workerRow) return;
            const timeline = workerRow.querySelector(".worker-timeline");
            generateTimeSlots(timeline);

            shiftsByWorker[workerId].forEach(shift=>{
                const [sh,sm] = parseTime(shift.start_time);
                const [eh,em] = parseTime(shift.end_time);
                const startSlot = sh*4 + Math.floor(sm/15);
                let endSlot = eh*4 + Math.ceil(em/15);
                if(endSlot <= startSlot) endSlot = 96;
                createShiftBlock(shift,startSlot,endSlot,timeline,workerColors[workerId]);
            });
        });
    }

    // --- CREATE SHIFT BLOCK ---
   function createShiftBlock(shift, startSlot, endSlot, container, workerColor) {
    const slotWidth = 40;
    const totalWidth = (endSlot - startSlot) * slotWidth;
    const shiftBlock = document.createElement("div");

    const isBreak = Number(shift.shift_type) === 3;
    const backgroundColor = isBreak ? "#ff0707ff" : (workerColor || "#6B7280");
    const borderColor = isBreak ? darkenColor("#ff0707ff", 20) : darkenColor(workerColor || "#6B7280", 20);

    shiftBlock.className = "shift-block";
    shiftBlock.style.backgroundColor = backgroundColor;
    shiftBlock.style.color = getContrastColor(backgroundColor);
    shiftBlock.style.borderLeft = "4px solid " + borderColor;
    shiftBlock.style.width = totalWidth + "px";
    shiftBlock.style.height = "43px";
    shiftBlock.style.position = "absolute";
    shiftBlock.style.left = startSlot * slotWidth + "px";
    shiftBlock.style.top = "2px";
    shiftBlock.style.cursor = "pointer";
    shiftBlock.textContent = `It's Time to Have your meal: ${shift.break_time_start}-${shift.break_time_end}`;

    // ✅ Role-based admin controls
    let adminControls = "";
    if (window.currentUserRole === "admin" || window.currentUserRole === "super_admin") {
        adminControls = `
            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="edit-btn text-xs bg-blue-500 text-white px-2 py-1 rounded" data-id="${shift.id}">Edit</button>
                <button class="delete-btn text-xs bg-red-500 text-white px-2 py-1 rounded" data-id="${shift.id}">Delete</button>
            </div>
        `;
    }

    shiftBlock.innerHTML = `
        <div class="shift-content group">
            <div class="flex justify-between items-start">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold truncate">${isBreak ? "BREAK" : (shift.flight?.flight_number || "")}</div>
                    <div class="text-xs opacity-90">${shift.start_time}-${shift.end_time}</div>
                </div>
                ${adminControls}
            </div>
            ${shift.notes ? `<div class="mt-2 text-xs italic line-clamp-2" title="${shift.notes}">💬 ${shift.notes}</div>` : ""}
        </div>
    `;

    // ✅ Break inside regular shift
    if (!isBreak && shift.break_time_start && shift.break_time_end) {
        const [shH, shM] = parseTime(shift.start_time);
        const shiftStartMin = shH * 60 + shM;
        const [bSH, bSM] = parseTime(shift.break_time_start);
        const [bEH, bEM] = parseTime(shift.break_time_end);
        const breakStartMin = bSH * 60 + bSM;
        const breakEndMin = bEH * 60 + bEM;
        const pxPerMin = slotWidth / 15;

        const breakBlock = document.createElement("div");
        breakBlock.className = "break-block";
        breakBlock.style.position = "absolute";
        breakBlock.style.left = ((breakStartMin - shiftStartMin) * pxPerMin) + "px";
        breakBlock.style.top = "0";
        breakBlock.style.width = ((breakEndMin - breakStartMin) * pxPerMin) + "px";
        breakBlock.style.height = "100%";
        breakBlock.style.backgroundColor = "#ff0707ff";
        breakBlock.style.borderRadius = "3px";
        breakBlock.style.display = "flex";
        breakBlock.style.alignItems = "center";
        breakBlock.style.justifyContent = "center";
        breakBlock.style.color = "#000";
        breakBlock.style.fontSize = "14px";
        breakBlock.style.fontWeight = "600";
        breakBlock.style.opacity = "0.8";
        breakBlock.textContent = `It's Time to Have your meal: ${shift.break_time_start}-${shift.break_time_end}`;

        shiftBlock.appendChild(breakBlock);
    }

    container.appendChild(shiftBlock);
}


    // --- EVENT DELEGATION FOR EDIT/DELETE ---
    document.body.addEventListener("click", function(e){
        const editBtn = e.target.closest(".edit-btn");
        const deleteBtn = e.target.closest(".delete-btn");
        const shiftBlockEl = e.target.closest(".shift-block");

        if(editBtn){
            e.stopPropagation();
            const shiftId = editBtn.dataset.id;
            const shift = currentShifts.find(s=>s.id==shiftId);
            if(shift) openEditModal(shift);
        }

        if(deleteBtn){
            e.stopPropagation();
            const shiftId = deleteBtn.dataset.id;
            deleteShift(shiftId);
        }

        if(shiftBlockEl && !editBtn && !deleteBtn){
            const shiftId = shiftBlockEl.querySelector(".edit-btn")?.dataset.id;
            const shift = currentShifts.find(s=>s.id==shiftId);
            if(shift) showShiftDetails(shift);
        }
    });

    // --- EDIT MODAL ---
    function openEditModal(shift){
        const title = document.getElementById("modal-title");
        const content = document.getElementById("modal-content");
        if(!modal || !title || !content) return console.error("Modal elements missing");

        title.textContent = "Edit Shift";

        const startVal = toDateTimeLocal(shift.start_time);
        const endVal = toDateTimeLocal(shift.end_time);

        // Fetch today's scheduled flights from global or fallback to empty array
        const todayFlights = (window.todayFlights || []).filter(f => f.status === "scheduled");

        content.innerHTML = `
            <form id="edit-shift-form" class="space-y-4" novalidate>
            <input type="hidden" name="worker_id" value="${shift.worker_id ?? ''}">
            <input type="hidden" name="shift_type" value="${shift.shift_type ?? ''}">

            <div>
                <label>Flight</label>
                <select name="flight_id" class="w-full p-2 border rounded">
                <option value="">-- No Flight --</option>
                ${todayFlights.map(f => `
                    <option value="${f.id}" ${shift.flight_id == f.id ? "selected" : ""}>
                    ${f.flight_number} (${f.scheduled_time})
                    </option>
                `).join("")}
                </select>
            </div>

            <div>
                <label>Start</label>
                <input type="datetime-local" name="start_time" value="${startVal}" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label>End</label>
                <input type="datetime-local" name="end_time" value="${endVal}" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label>Notes</label>
                <textarea name="notes" class="w-full p-2 border rounded">${shift.notes || ""}</textarea>
            </div>

            <div id="edit-form-errors" style="color: #b91c1c; font-size: .9rem;"></div>

            <div class="flex justify-end gap-2">
                <button type="button" id="edit-cancel-btn" class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</button>
                <button type="submit" id="edit-submit-btn" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
            </div>
            </form>
        `;

        modal.classList.remove("hidden");

        document.getElementById("edit-cancel-btn").onclick = closeModal;

        const form = document.getElementById("edit-shift-form");
        const submitBtn = document.getElementById("edit-submit-btn");
        const errorsDiv = document.getElementById("edit-form-errors");

        form.addEventListener("submit", async function handler(e){
            e.preventDefault();
            errorsDiv.textContent = "";

            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span style="opacity:.9">Updating…</span>';

            const formData = new FormData(form);
            formData.append("_method","PUT");
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if(csrfMeta) formData.append("_token",csrfMeta.content);

            const url = `./admin/shifts/${shift.id}`;

            try{
                const resp = await fetch(url,{
                    method:"POST",
                    body:formData,
                    credentials:"same-origin",
                    headers: {"X-CSRF-TOKEN": csrfMeta ? csrfMeta.content : ""}
                });

                if(resp.status===422){
                    const data = await resp.json().catch(()=>null);
                    errorsDiv.textContent = data?.errors ? Object.values(data.errors)[0][0] : "Validation failed";
                    return;
                }

                if(!resp.ok){
                    const text = await resp.text().catch(()=>null);
                    errorsDiv.textContent = text || `Update failed (status ${resp.status})`;
                    return;
                }

                await resp.json().catch(()=>null);
                closeModal();
                loadDataForDate(dateInput.value);

            }catch(err){
                console.error("Network/update error:", err);
                errorsDiv.textContent = "Network error — please try again.";
            }finally{
                submitBtn.disabled=false;
                submitBtn.innerHTML=originalBtnHtml;
            }
        }, {once:true});
    }

    function deleteShift(id){
        if(!confirm("Are you sure you want to delete this shift?")) return;
        const csrfMeta=document.querySelector('meta[name="csrf-token"]');
        const formData = new FormData();
        formData.append("_method","DELETE");
        if(csrfMeta) formData.append("_token",csrfMeta.content);
        fetch(`./admin/shifts/${id}`,{method:"POST",body:formData,headers:{"X-CSRF-TOKEN":csrfMeta?csrfMeta.content:""}})
            .then(()=>loadDataForDate(dateInput.value)).catch(err=>console.error(err));
    }

    function showShiftDetails(shift) {
    const modal = document.getElementById("shift-details-modal");
    const title = document.getElementById("shift-details-title");
    const body = document.getElementById("shift-details-body");
    const closeBtn = document.getElementById("shift-details-close");

    if (!modal || !title || !body || !closeBtn) return console.error("Shift details modal elements missing");

    // Set modal content
    title.textContent = `Shift Details - ${shift.worker_name}`;
    body.innerHTML = `
        <p><strong>Staff:</strong> ${shift.worker_name}</p>
        <p><strong>Shift:</strong> ${shift.start_time} - ${shift.end_time}</p>
        <p><strong>Notes:</strong> ${shift.notes || "None"}</p>
    `;

    // Show modal
    modal.classList.remove("hidden");

    // Close modal on button click
    closeBtn.onclick = () => modal.classList.add("hidden");

    // Close modal when clicking outside the content
    modal.onclick = (e) => {
        if (e.target === modal) modal.classList.add("hidden");
    };
}

    // --- FLIGHTS ---
    function renderFlights(flights){
        document.querySelectorAll(".flight-item").forEach(el=>el.remove());
        const flightsBySlot={};
        flights.forEach(f=>{
            const [h,m]=f.scheduled_time.split(":").map(Number);
            const slot=h*4 + Math.floor(m/15);
            if(!flightsBySlot[slot]) flightsBySlot[slot]=[];
            flightsBySlot[slot].push(f);
        });
        const flightTimeline=document.getElementById("flight-timeline");
        Object.keys(flightsBySlot).forEach(slot=>{
            flightsBySlot[slot].forEach((f,idx)=>createFlightItem(f,parseInt(slot),idx,flightTimeline));
        });
    }

    function createFlightItem(flight,slot,rowIndex,container){
        const flightColor=getFlightColor(flight.type);
        const textColor=getContrastColor(flightColor);
        const left=slot*40;
        const top=8+rowIndex*52;
        const flightItem=document.createElement("div");
        flightItem.className="flight-item";
        flightItem.style.position="absolute";
        flightItem.style.left=left+"px";
        flightItem.style.top=top+"px";
        flightItem.draggable=true;
        flightItem.innerHTML=`
            <div class="px-2 py-1 rounded flex items-center gap-2 bg-white shadow" style="border-left:4px solid ${flightColor}; color:${textColor}">
                <span>${flight.flight_number}</span>
                <span class="text-xs opacity-70">${flight.scheduled_time}</span>
            </div>
        `;
        container.appendChild(flightItem);
        flightItem.addEventListener("click", e=>{ e.stopPropagation(); showFlightDetails(flight); });
    }

    function showFlightDetails(flight){
        alert(`Flight: ${flight.flight_number}\nType: ${flight.type}\nScheduled: ${flight.scheduled_time}`);
    }
});
