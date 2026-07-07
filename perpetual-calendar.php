<?php
/**
 * -------------------------------------------------------------------------
 * Perpetual Calendar
 * -------------------------------------------------------------------------
 *
 * A printable perpetual calendar generator for any Gregorian calendar year.
 *
 * The calendar automatically determines the weekday on which each month
 * begins, constructs a bottom-aligned month matrix, and renders a perpetual
 * calendar lookup table suitable for on-screen use and printing.
 *
 * Features:
 *   - Supports years 1900 to 2100.
 *   - Automatic month placement.
 *   - Responsive Bootstrap 5 layout.
 *   - Print-friendly A4 landscape output.
 *   - Interactive row and column highlighting.
 *   - Year selection with automatic calendar generation.
 *
 * Copyright (c) 2026 Rick Ruggiero
 *
 * Released under the MIT License.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * -------------------------------------------------------------------------
 */
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
$year = max(1900, min(2100, $year));

$months = [
    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
    5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
    9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec',
];

$days = ['Su', 'M', 'Tu', 'W', 'Th', 'F', 'Sa'];

$monthGroups = array_fill(0, 7, []);

foreach ($months as $month => $name) {
    $weekday = (int)date('w', strtotime(sprintf('%04d-%02d-01', $year, $month)));
    $monthGroups[$weekday][] = $name;
}

/*
 * Build a bottom-aligned month matrix.
 *
 * Example for 2026:
 *
 *          Su   M    Tu   W    Th   F    Sa
 * row 1    Feb
 * row 2    Mar       Sep  Apr  Jan
 * row 3    Nov  Jun  Dec  Jul  Oct  May  Aug
 */
$monthRows = max(array_map('count', $monthGroups));

$monthMatrix = array_fill(
    0,
    $monthRows,
    array_fill(0, 7, '')
);

foreach ($monthGroups as $weekday => $group) {
    $startRow = $monthRows - count($group);

    foreach ($group as $offset => $monthName) {
        $monthMatrix[$startRow + $offset][$weekday] = $monthName;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Perpetual Calendar <?= htmlspecialchars((string)$year) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        html,
        body {
            height: 100%;
        }

        body {
            background: #f8f9fa;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;

            display: flex;
            flex-direction: column;
        }

        .page-content {
            flex: 1;
        }

        footer.sticky-footer {
            background: #212529;
            color: #dee2e6;
            border-top: 1px solid #495057;

            padding: .6rem 1rem;

            text-align: center;
            font-size: .9rem;
        }

        .page-wrap {
            max-width: 980px;
            margin: 0 auto;
        }

        .calendar-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table.perpetual-calendar {
            border-collapse: collapse;
            background: white;
            margin: 1.5rem auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 100%;
	    /* max-width: 900px; */
            max-width: 1250px;
            border: .5px solid #000;
            table-layout: fixed;
        }

        .perpetual-calendar td,
        .perpetual-calendar th {
            border: 1.5px solid #333;
            text-align: center;
            vertical-align: middle;
            padding: 6px 4px;
            font-size: 1.05rem;
            color: #222;
            line-height: 1.1;
        }

        .perpetual-calendar .blank {
            border: none;
            background: transparent;
        }

        .perpetual-calendar .month {
            background: #000;
            color: #fff;
            font-weight: 700;
            font-size: 1.05rem;

            border: 1px solid #000;      /* thin white divider between touching months */
            outline: 1px solid #000;     /* black outline around the outside */

            text-align: center;
            vertical-align: middle;

            padding: 0;
            height: 46px;
            line-height: 46px;
        }

        .perpetual-calendar .date-cell {
            font-weight: 500;
            font-size: 1.3rem;
            background: #fff;
            height: 42px;
        }

        .perpetual-calendar .weekday {
            font-weight: 500;
            background: #f1f3f5;
	    /* font-size: 0.95rem; */
            font-size: 1.3rem;
            padding-top: 6px;
            height: 42px;
        }

        .perpetual-calendar .year-block {
            font-size: clamp(2.4rem, 8vw, 5.8rem);
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            color: white;
            padding: 15px 10px;
        }

        @media (max-width: 768px) {
            .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            table.perpetual-calendar {
                min-width: 640px;
            }

            .perpetual-calendar td,
            .perpetual-calendar th {
                font-size: 0.9rem;
                padding: 5px 3px;
            }

            .perpetual-calendar .month {
                height: 46px;
                font-size: 0.95rem;
            }

            .perpetual-calendar .date-cell {
                font-size: 1.1rem;
            }
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm;
            }

            html,
            body {
                width: 100%;
                height: auto;
                margin: 0;
                padding: 0;
                background: white !important;
            }

            body * {
                visibility: hidden !important;
            }

            .calendar-wrap,
            .calendar-wrap * {
                visibility: visible !important;
            }

            .calendar-wrap {
                position: fixed;
                left: 10mm;
                top: 10mm;
                width: calc(100vw - 20mm);
                overflow: visible !important;
                padding: 0;
                margin: 0;
            }

            table.perpetual-calendar {
                width: 100%;
                max-width: none;
                margin: 0 auto;
                box-shadow: none;
                table-layout: fixed;
                page-break-inside: avoid;
            }

            .perpetual-calendar td,
            .perpetual-calendar th {
                border: 1.2px solid #000;
                color: #000 !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .perpetual-calendar .blank {
                background: white !important;
                border: none !important;
            }

            .perpetual-calendar .month {
                background: #e9ecef !important;
            }

            .perpetual-calendar .weekday {
                background: #f1f3f5 !important;
            }

            .perpetual-calendar .year-block {
                background: linear-gradient(135deg, #0d6efd, #6610f2) !important;
                color: white !important;
                font-size: 48pt;
            }
        }

.hover-row {
    background: rgba(13,110,253,.12) !important;
}

.hover-col {
    background: rgba(13,110,253,.12) !important;
}

.hover-row.hover-col {
    background: rgba(13,110,253,.28) !important;
    font-weight: 700;
}

    </style>
</head>
<body>

<div class="page-content">
    <div class="container py-4 page-wrap">
        <div class="no-print d-flex flex-wrap gap-3 align-items-end justify-content-between mb-4">
            <div>
                <h1 class="mb-1 display-5 fw-bold">Perpetual Calendar</h1>
                <div class="text-muted">Year <span class="fw-semibold"><?= htmlspecialchars((string)$year) ?></span></div>
            </div>

            <form method="get" class="d-flex flex-wrap gap-2 align-items-end">
                <div class="me-2">
                    <label for="year" class="form-label small">Select Year</label>
                    <select name="year" id="year" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 110px;">
                        <?php
                        $currentYear = (int)date('Y');
                        $lastYear = $currentYear + 9;

                        for ($y = $currentYear; $y <= $lastYear; $y++): ?>
                            <option value="<?= $y ?>" <?= $y === $year ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                    <i class="bi bi-printer-fill"></i> Print / Save as PDF
                </button>
            </form>
        </div>

        <div class="calendar-wrap">
            <table class="perpetual-calendar">
                <tbody>

                <?php foreach ($monthMatrix as $rowIndex => $monthRow): ?>
                    <tr>
                        <?php if ($rowIndex === 0): ?>
                            <td class="year-block" colspan="5" rowspan="<?= $monthRows ?>">
                                <?= htmlspecialchars((string)$year) ?>
                            </td>
                        <?php endif; ?>
    
                        <?php foreach ($monthRow as $monthName): ?>
                            <td class="<?= $monthName === '' ? 'blank' : 'month' ?>">
                                <?= htmlspecialchars($monthName) ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
    
                <?php for ($row = 1; $row <= 7; $row++): ?>
                    <tr>
                        <?php for ($col = 0; $col < 5; $col++): ?>
                            <?php $date = $row + ($col * 7); ?>
                                <td class="date-cell"
                                    data-lookup="1"
                                    data-row="<?= $row ?>"
                                    data-col="<?= $col ?>">
                                    <?= $date <= 31 ? $date : '&nbsp;' ?>
                                </td>
                        <?php endfor; ?>

                        <?php for ($weekday = 0; $weekday < 7; $weekday++): ?>
                            <?php $weekdayIndex = ($weekday + $row - 1) % 7; ?>
                            <?php for ($weekday = 0; $weekday < 7; $weekday++): ?>
                                <?php $weekdayIndex = ($weekday + $row - 1) % 7; ?>
                                <td class="weekday"
                                    data-lookup="1"
                                    data-row="<?= $row ?>"
                                    data-col="<?= $weekday + 5 ?>">
                                    <?= htmlspecialchars($days[$weekdayIndex]) ?>
                                </td>
                            <?php endfor; ?>
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>
    
                </tbody>
            </table>
        </div>
    </div>
</div>
<footer class="sticky-footer">
    &copy; <?= date('Y') ?> Rick Ruggiero.  Released under the MIT Licence.
</footer>
<script>
document.querySelectorAll('[data-lookup="1"]').forEach(cell => {

    cell.addEventListener('mouseenter', function () {

        const row = this.dataset.row;
        const col = this.dataset.col;

        document
            .querySelectorAll(`[data-row="${row}"]`)
            .forEach(c => c.classList.add('hover-row'));

        document
            .querySelectorAll(`[data-col="${col}"]`)
            .forEach(c => c.classList.add('hover-col'));

        this.classList.add('hover-cell');

    });

    cell.addEventListener('mouseleave', function () {

        document
            .querySelectorAll('.hover-row, .hover-col, .hover-cell')
            .forEach(c =>
                c.classList.remove('hover-row', 'hover-col', 'hover-cell'));

    });

});
</script>
</body>
</html>
