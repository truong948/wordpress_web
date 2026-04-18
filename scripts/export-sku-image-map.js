const fs = require('fs');
const path = require('path');

const inputPath = path.join(__dirname, '..', 'database', 'products-import.csv');
const outputPath = path.join(
  __dirname,
  '..',
  'wp-content',
  'themes',
  'noithat-pro',
  'assets',
  'data',
  'sku-image-map.json'
);

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

function main() {
  const raw = fs.readFileSync(inputPath, 'utf8').trim();
  const rows = raw.split(/\r?\n/).map(parseCsvLine);
  const header = rows[0];

  const skuIndex = header.indexOf('SKU');
  const imagesIndex = header.indexOf('Images');

  if (skuIndex === -1 || imagesIndex === -1) {
    throw new Error('CSV khong co cot SKU hoac Images');
  }

  const map = {};
  for (let i = 1; i < rows.length; i += 1) {
    const row = rows[i];
    const sku = (row[skuIndex] || '').trim();
    const imagesCell = (row[imagesIndex] || '').trim();
    if (!sku || !imagesCell) continue;

    const firstImage = imagesCell.split(',').map((x) => x.trim()).filter(Boolean)[0] || '';
    if (firstImage) {
      map[sku] = firstImage;
    }
  }

  const payload = {
    generatedAt: new Date().toISOString(),
    source: 'database/products-import.csv',
    total: Object.keys(map).length,
    imagesBySku: map,
  };

  fs.writeFileSync(outputPath, `${JSON.stringify(payload, null, 2)}\n`, 'utf8');

  console.log('=== SKU IMAGE MAP EXPORTED ===');
  console.log(`Input : ${inputPath}`);
  console.log(`Output: ${outputPath}`);
  console.log(`Mapped SKUs: ${payload.total}`);
}

main();
