const fs = require('fs');
const path = require('path');

const filePath = path.join(__dirname, '..', 'database', 'products-import.csv');
const raw = fs.readFileSync(filePath, 'utf8').trim();

function parseCsvLine(line) {
  const out = [];
  let cur = '';
  let inQuotes = false;

  for (let i = 0; i < line.length; i += 1) {
    const char = line[i];
    const next = line[i + 1];

    if (char === '"') {
      if (inQuotes && next === '"') {
        cur += '"';
        i += 1;
      } else {
        inQuotes = !inQuotes;
      }
    } else if (char === ',' && !inQuotes) {
      out.push(cur);
      cur = '';
    } else {
      cur += char;
    }
  }

  out.push(cur);
  return out;
}

const lines = raw.split(/\r?\n/);
const rows = lines.map(parseCsvLine);
const header = rows[0];
const dataRows = rows.slice(1);

const expectedColumns = 19;
const badRows = dataRows
  .map((row, idx) => ({ index: idx + 2, columns: row.length }))
  .filter((row) => row.columns !== expectedColumns);

const categories = new Map();
for (const row of dataRows) {
  const category = row[11] || '';
  categories.set(category, (categories.get(category) || 0) + 1);
}

console.log('=== PRODUCTS CSV VERIFY ===');
console.log(`File: ${filePath}`);
console.log(`Header columns: ${header.length}`);
console.log(`Data rows: ${dataRows.length}`);
console.log('Category distribution:', Object.fromEntries(categories));

if (header.length !== expectedColumns) {
  console.error(`ERROR: Expected ${expectedColumns} header columns, got ${header.length}`);
  process.exit(1);
}

if (dataRows.length !== 30) {
  console.error(`ERROR: Expected 30 products, got ${dataRows.length}`);
  process.exit(1);
}

if (badRows.length > 0) {
  console.error('ERROR: Rows with invalid column count:', badRows);
  process.exit(1);
}

console.log('PASS: CSV is valid with 30 products and 19 columns per row.');
