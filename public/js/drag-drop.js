document.addEventListener("DOMContentLoaded", function () {
    const flightTiles = document.querySelectorAll(".flight-tile");
    const workerTimelines = document.querySelectorAll(".droppable");

    const storeDragUrl = window.storeDragUrl || "/admin/shifts/store-drag";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    flightTiles.forEach((tile) => {
        tile.addEventListener("dragstart", (e) => {
            e.dataTransfer.setData("flight_id", tile.dataset.flightId);
            e.dataTransfer.setData("flight_number", tile.dataset.flightNumber);
            e.dataTransfer.setData("departure_time", tile.dataset.departureTime);
        });
    });

    workerTimelines.forEach((timeline) => {
        timeline.addEventListener("dragover", (e) => {
            e.preventDefault();
            timeline.classList.add("bg-green-50");
        });

        timeline.addEventListener("dragleave", () => {
            timeline.classList.remove("bg-green-50");
        });

        timeline.addEventListener("drop", async (e) => {
            e.preventDefault();
            timeline.classList.remove("bg-green-50");

            const workerId = timeline.dataset.workerId;
            const flightId = e.dataTransfer.getData("flight_id");
            const departureTimeStr = e.dataTransfer.getData("departure_time");

            const startTime = new Date(departureTimeStr);
            if (isNaN(startTime)) {
                alert("Invalid departure time: " + departureTimeStr);
                return;
            }

            const endTime = new Date(startTime.getTime() + 60 * 60 * 1000); // +1 hour

            const formData = new FormData();
            formData.append("worker_id", parseInt(workerId));
            formData.append("flight_id", parseInt(flightId));
            formData.append("shift_type", 1);
            formData.append("notes", "Auto-assigned via drag & drop");
            formData.append("_token", csrfToken);
            formData.append("start_time", startTime.toISOString());
            formData.append("end_time", endTime.toISOString());

            try {
                const response = await fetch(storeDragUrl, {
                    method: "POST",
                    body: formData,
                    headers: { "Accept": "application/json" }
                });

                const text = await response.text();
                try {
                    const result = JSON.parse(text);
                    if (result.success) {
                        alert(result.message || "Shift assigned successfully!");
                        location.reload();
                    } else {
                        alert("Failed: " + (result.message || "Unknown error"));
                    }
                } catch (err) {
                    console.error("Response not JSON:", text);
                    alert("Server returned non-JSON response.");
                }

            } catch (err) {
                console.error("Fetch error:", err);
                alert("Something went wrong while saving shift!");
            }
        });
    });
});
