/**
 * CinéBook - Seat Selection Logic
 */
document.addEventListener('DOMContentLoaded', function () {
    const grid = document.getElementById('seatGrid');
    if (!grid) return;

    const rows = parseInt(grid.dataset.rows);
    const cols = parseInt(grid.dataset.cols);
    const price = parseFloat(grid.dataset.price);
    const bookedRaw = JSON.parse(grid.dataset.booked || '[]');

    const bookedSet = new Set();
    bookedRaw.forEach(s => bookedSet.add(s.seat_row + s.seat_number));

    const selected = new Map();

    // Build grid
    for (let r = 0; r < rows; r++) {
        const rowLabel = String.fromCharCode(65 + r);
        const rowDiv = document.createElement('div');
        rowDiv.className = 'seat-row';

        const label = document.createElement('span');
        label.className = 'seat-row-label';
        label.textContent = rowLabel;
        rowDiv.appendChild(label);

        for (let c = 1; c <= cols; c++) {
            const btn = document.createElement('button');
            btn.type = 'button';
            const key = rowLabel + c;

            if (bookedSet.has(key)) {
                btn.className = 'seat seat-taken';
                btn.disabled = true;
                btn.title = 'Occupé';
            } else {
                btn.className = 'seat seat-available';
                btn.title = key;
                btn.addEventListener('click', function () {
                    if (selected.has(key)) {
                        selected.delete(key);
                        btn.className = 'seat seat-available';
                    } else {
                        selected.set(key, { row: rowLabel, number: c });
                        btn.className = 'seat seat-selected';
                    }
                    updateSummary();
                });
            }

            btn.textContent = c;
            rowDiv.appendChild(btn);
        }

        const labelEnd = document.createElement('span');
        labelEnd.className = 'seat-row-label';
        labelEnd.textContent = rowLabel;
        rowDiv.appendChild(labelEnd);

        grid.appendChild(rowDiv);
    }

    function updateSummary() {
        const summary = document.getElementById('bookingSummary');
        const seatsDisplay = document.getElementById('selectedSeatsDisplay');
        const totalDisplay = document.getElementById('totalPriceDisplay');
        const seatsInput = document.getElementById('seatsInput');
        const confirmBtn = document.getElementById('confirmBtn');

        if (selected.size > 0) {
            summary.style.display = 'block';
            const seatLabels = [];
            const seatData = [];
            selected.forEach((val, key) => {
                seatLabels.push(key);
                seatData.push(val);
            });
            seatsDisplay.textContent = seatLabels.join(', ');
            totalDisplay.textContent = (selected.size * price).toFixed(2) + ' DT';
            seatsInput.value = JSON.stringify(seatData);
            confirmBtn.disabled = false;
        } else {
            summary.style.display = 'none';
            seatsInput.value = '[]';
        }
    }
});
