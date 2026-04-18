const fs = require('fs');
const path = require('path');

const inputPath = path.join(__dirname, '..', 'database', 'products-import.csv');
const outputPath = path.join(__dirname, '..', 'database', 'products-import.host-safe.csv');

function parseCsvLine(line) {
  const out = [];
  let cur = '';
  let inQuotes = false;

  for (let i = 0; i < line.length; i += 1) {
    const ch = line[i];
    const next = line[i + 1];

    if (ch === '"') {
      if (inQuotes && next === '"') {
        cur += '"';
        i += 1;
      } else {
        inQuotes = !inQuotes;
      }
    } else if (ch === ',' && !inQuotes) {
      out.push(cur);
      cur = '';
    } else {
      cur += ch;
    }
  }

  out.push(cur);
  return out;
}

function escapeCsv(value) {
  const text = value === null || value === undefined ? '' : String(value);
  return `"${text.replace(/"/g, '""')}"`;
}

function main() {
  const raw = fs.readFileSync(inputPath, 'utf8').trim();
  const lines = raw.split(/\r?\n/);
  const rows = lines.map(parseCsvLine);

  if (rows.length < 2) {
    throw new Error('CSV khong co du lieu san pham');
  }

  const header = rows[0];
  const imageIndex = header.indexOf('Images');

  if (imageIndex === -1) {
    throw new Error('Khong tim thay cot Images trong CSV');
  }

  let changedRows = 0;
  const updatedRows = rows.map((row, idx) => {
    if (idx === 0) return row;

    const copy = [...row];
    if ((copy[imageIndex] || '').trim() !== '') {
      changedRows += 1;
    }

    // Xoa URL anh de importer khong phai tai anh tu internet tren host free.
    copy[imageIndex] = '';
    return copy;
  });

  const csv = updatedRows.map((row) => row.map(escapeCsv).join(',')).join('\n');
  fs.writeFileSync(outputPath, csv, 'utf8');

  console.log('=== HOST SAFE CSV GENERATED ===');
  console.log(`Input : ${inputPath}`);
  console.log(`Output: ${outputPath}`);
  console.log(`Products updated (Images cleared): ${changedRows}`);
  console.log('Use this file for import on free hosting to avoid timeout/external image fetch failures.');
}

main();
