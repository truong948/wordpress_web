const fs = require('fs');
const path = require('path');

const inputPath = path.join(__dirname, '..', 'database', 'products-import.csv');

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

function isPrivateIpv4(host) {
  if (!/^\d+\.\d+\.\d+\.\d+$/.test(host)) return false;
  const parts = host.split('.').map((n) => parseInt(n, 10));
  if (parts.some((n) => Number.isNaN(n) || n < 0 || n > 255)) return false;

  if (parts[0] === 10) return true;
  if (parts[0] === 127) return true;
  if (parts[0] === 192 && parts[1] === 168) return true;
  if (parts[0] === 172 && parts[1] >= 16 && parts[1] <= 31) return true;

  return false;
}

function classifyUrl(value) {
  const raw = (value || '').trim();
  if (!raw) return { type: 'empty' };

  let parsed;
  try {
    parsed = new URL(raw);
  } catch (_err) {
    return { type: 'invalid', value: raw };
  }

  const host = (parsed.hostname || '').toLowerCase();
  if (
    host === 'localhost' ||
    host.endsWith('.local') ||
    isPrivateIpv4(host)
  ) {
    return { type: 'local', host, value: raw };
  }

  if (parsed.protocol !== 'http:' && parsed.protocol !== 'https:') {
    return { type: 'unsupported', protocol: parsed.protocol, value: raw };
  }

  return { type: 'remote', host, value: raw };
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

  const stats = {
    totalProducts: rows.length - 1,
    emptyImageCell: 0,
    localUrls: 0,
    invalidUrls: 0,
    unsupportedUrls: 0,
    remoteUrls: 0,
  };

  const localSamples = [];
  const invalidSamples = [];

  for (let i = 1; i < rows.length; i += 1) {
    const row = rows[i];
    const cell = row[imageIndex] || '';
    if (!cell.trim()) {
      stats.emptyImageCell += 1;
      continue;
    }

    const urls = cell.split(',').map((x) => x.trim()).filter(Boolean);
    if (urls.length === 0) {
      stats.emptyImageCell += 1;
      continue;
    }

    for (const url of urls) {
      const result = classifyUrl(url);
      if (result.type === 'local') {
        stats.localUrls += 1;
        if (localSamples.length < 5) {
          localSamples.push({ row: i + 1, value: result.value });
        }
      } else if (result.type === 'invalid') {
        stats.invalidUrls += 1;
        if (invalidSamples.length < 5) {
          invalidSamples.push({ row: i + 1, value: result.value });
        }
      } else if (result.type === 'unsupported') {
        stats.unsupportedUrls += 1;
      } else if (result.type === 'remote') {
        stats.remoteUrls += 1;
      }
    }
  }

  console.log('=== CSV IMAGE CHECK ===');
  console.log(`File: ${inputPath}`);
  console.log(`Products: ${stats.totalProducts}`);
  console.log(`Empty image cells: ${stats.emptyImageCell}`);
  console.log(`Remote URLs: ${stats.remoteUrls}`);
  console.log(`Local/private URLs: ${stats.localUrls}`);
  console.log(`Invalid URLs: ${stats.invalidUrls}`);
  console.log(`Unsupported protocol URLs: ${stats.unsupportedUrls}`);

  if (localSamples.length > 0) {
    console.log('\nLocal/private URL samples:');
    for (const item of localSamples) {
      console.log(`- Row ${item.row}: ${item.value}`);
    }
  }

  if (invalidSamples.length > 0) {
    console.log('\nInvalid URL samples:');
    for (const item of invalidSamples) {
      console.log(`- Row ${item.row}: ${item.value}`);
    }
  }

  if (stats.localUrls > 0 || stats.invalidUrls > 0 || stats.unsupportedUrls > 0) {
    console.error('\nFAIL: CSV image URLs can cause deploy/import issues.');
    process.exit(1);
  }

  console.log('\nPASS: Image URLs look deploy-safe (no localhost/private links found).');
}

main();
