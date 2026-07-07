# Perpetual Calendar

A printable perpetual calendar generator written in PHP and Bootstrap 5.

This application generates a perpetual calendar for any Gregorian calendar year by automatically determining the weekday on which each month begins and arranging the months into the traditional perpetual calendar layout.

---

## Features

- Generate perpetual calendars for any year.
- Responsive Bootstrap 5 interface.
- Print-friendly A4 landscape output.
- Interactive row and column highlighting.
- Automatic month placement.
- Automatic leap year support.
- Year selector.
- MIT Licensed.

---

## Requirements

- PHP 8.0 or later
- Modern web browser
- Bootstrap 5 (loaded from CDN)

No database is required.

---

## Installation

Clone or download the project.

```
git clone <repository>
```

or simply copy the files into your web server.

Open:

```
http://localhost/perpetual-calendar.php
```

---

## Usage

1. Select the required year.
2. The calendar is generated automatically.
3. Hover over a date or weekday to highlight the corresponding lookup row and column.
4. Use **Print / Save as PDF** to produce a printable calendar.

---

## How it Works

The calendar is generated in three stages.

### 1. Month Analysis

For each month, PHP determines the weekday on which the first day occurs.

Example:

```
January 2026   → Thursday
February 2026  → Sunday
March 2026     → Sunday
...
```

---

### 2. Month Matrix Construction

The months are grouped by weekday and converted into a bottom-aligned matrix.

Example for 2026:

|       | Su | M | Tu | W | Th | F | Sa |
|-------|----|---|----|---|----|---|----|
| Row 1 | Feb | | | | | | |
| Row 2 | Mar | | Sep | Apr | Jan | | |
| Row 3 | Nov | Jun | Dec | Jul | Oct | May | Aug |

This matrix is then rendered directly into the calendar.

---

### 3. Calendar Rendering

The completed month matrix is combined with the date lookup table to produce the perpetual calendar.

---

## Printing

The calendar has been designed for:

- A4 Landscape
- Print Preview
- Save as PDF

Only the calendar itself is printed.

---

## Browser Support

Tested with modern browsers supporting:

- Chrome
- Edge
- Firefox
- Safari

---

## Future Ideas

Possible future enhancements include:

- Colour themes.
- Holiday overlays.
- Julian calendar support.
- Export as PNG or SVG.
- Keyboard navigation.
- Accessibility improvements.

---

## Licence

Copyright © 2026 Rick Ruggiero.

Released under the MIT License.

```
MIT License

Copyright (c) 2026 Rick Ruggiero

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

## Author

Rick Ruggiero

Gold Coast, Queensland, Australia


