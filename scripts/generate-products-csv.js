const fs = require('fs');
const path = require('path');

const filePath = path.join(__dirname, '..', 'database', 'products-import.csv');

const headers = [
  'ID',
  'Type',
  'SKU',
  'Name',
  'Published',
  'Is featured?',
  'Visibility in catalog',
  'Short description',
  'Description',
  'Sale price',
  'Regular price',
  'Categories',
  'Tags',
  'Images',
  'Stock',
  'Weight (kg)',
  'Length (cm)',
  'Width (cm)',
  'Height (cm)'
];

const imagePool = {
  sofa: [
    'https://images.unsplash.com/photo-1493666438817-866a91353ca9?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1484101403633-562f891dc89a?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=1200&q=80'
  ],
  ban: [
    'https://images.unsplash.com/photo-1519710884006-4d4f6494f0d7?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1549187774-b4e9b0445b41?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1565538810643-b5bdb714032a?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1586105251261-72a756497a12?auto=format&fit=crop&w=1200&q=80'
  ],
  ghe: [
    'https://images.unsplash.com/photo-1579656592043-a20e25a4aa4b?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1616627451902-6fa33f9c5b79?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1598300042247-d088f8ab3a91?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1551298370-9d3d53740c72?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1601396974228-7ad4dff65136?auto=format&fit=crop&w=1200&q=80'
  ],
  tuke: [
    'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1616594039964-3f5f6c5f67df?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1578898887932-dce23a595ad4?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1597074866923-dc0589150358?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1582582429416-97d2f9f5a93c?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1556911220-bff31c812dba?auto=format&fit=crop&w=1200&q=80'
  ],
  giuong: [
    'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1540518614846-7eded433c457?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1616596871468-6f4d30fbd8b4?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1616628182509-6f3cb0f5d2fc?auto=format&fit=crop&w=1200&q=80'
  ]
};

const products = [
  // SOFA (6)
  { sku: 'NTP-SF001', name: 'Sofa Chu L Nordic Premium', featured: 1, sale: 15900000, regular: 18500000, category: 'Sofa', tags: 'sofa, phong khach, nordic', stock: 35, weight: 45, length: 280, width: 170, height: 85, image: imagePool.sofa[0] },
  { sku: 'NTP-SF002', name: 'Sofa 3 Cho Milano Velvet', featured: 1, sale: 12500000, regular: 14900000, category: 'Sofa', tags: 'sofa, velvet, milano', stock: 28, weight: 38, length: 220, width: 90, height: 82, image: imagePool.sofa[1] },
  { sku: 'NTP-SF003', name: 'Sofa Don Accent Chair Luna', featured: 0, sale: '', regular: 5900000, category: 'Sofa', tags: 'sofa don, accent chair', stock: 30, weight: 25, length: 78, width: 82, height: 92, image: imagePool.sofa[2] },
  { sku: 'NTP-SF004', name: 'Sofa Bang 2 Cho Compact Urban', featured: 0, sale: 6900000, regular: 7900000, category: 'Sofa', tags: 'sofa bang, nho gon, urban', stock: 22, weight: 30, length: 165, width: 85, height: 80, image: imagePool.sofa[3] },
  { sku: 'NTP-SF005', name: 'Sofa Goc Modulo Flex', featured: 1, sale: 17900000, regular: 19900000, category: 'Sofa', tags: 'sofa goc, modular', stock: 16, weight: 52, length: 300, width: 190, height: 88, image: imagePool.sofa[4] },
  { sku: 'NTP-SF006', name: 'Sofa Bed Da Nang Smart Sleep', featured: 0, sale: 9900000, regular: 11200000, category: 'Sofa', tags: 'sofa bed, thong minh', stock: 20, weight: 42, length: 210, width: 95, height: 86, image: imagePool.sofa[5] },

  // BAN (6)
  { sku: 'NTP-BA001', name: 'Ban An Go Soi 6 Cho Osaka', featured: 1, sale: 11900000, regular: 13500000, category: 'Ban', tags: 'ban an, go soi, 6 cho', stock: 24, weight: 55, length: 180, width: 90, height: 75, image: imagePool.ban[0] },
  { sku: 'NTP-BA002', name: 'Ban Lam Viec Standing Desk Pro', featured: 0, sale: '', regular: 8900000, category: 'Ban', tags: 'ban lam viec, standing desk', stock: 20, weight: 35, length: 140, width: 70, height: 75, image: imagePool.ban[1] },
  { sku: 'NTP-BA003', name: 'Ban Tra Mat Da Marble Mini', featured: 0, sale: 3200000, regular: 3800000, category: 'Ban', tags: 'ban tra, mat da, mini', stock: 30, weight: 18, length: 90, width: 55, height: 42, image: imagePool.ban[2] },
  { sku: 'NTP-BA004', name: 'Ban Console Entryway Slim', featured: 0, sale: '', regular: 4600000, category: 'Ban', tags: 'ban console, entryway', stock: 26, weight: 20, length: 120, width: 35, height: 82, image: imagePool.ban[3] },
  { sku: 'NTP-BA005', name: 'Ban Hoc Sinh Ergofit', featured: 0, sale: 2900000, regular: 3400000, category: 'Ban', tags: 'ban hoc, tre em, ergonomic', stock: 32, weight: 16, length: 110, width: 60, height: 73, image: imagePool.ban[4] },
  { sku: 'NTP-BA006', name: 'Ban Lam Viec Gaming Carbon X', featured: 1, sale: 7600000, regular: 8900000, category: 'Ban', tags: 'ban gaming, carbon, rgb', stock: 18, weight: 28, length: 160, width: 75, height: 75, image: imagePool.ban[5] },

  // GHE (6)
  { sku: 'NTP-GH001', name: 'Ghe An Go Tu Nhien Hana', featured: 1, sale: 1290000, regular: 1590000, category: 'Ghe', tags: 'ghe an, japandi', stock: 60, weight: 5, length: 45, width: 50, height: 82, image: imagePool.ghe[0] },
  { sku: 'NTP-GH002', name: 'Ghe Van Phong Ergonomic Mesh', featured: 0, sale: '', regular: 4500000, category: 'Ghe', tags: 'ghe van phong, ergonomic', stock: 40, weight: 18, length: 68, width: 68, height: 118, image: imagePool.ghe[1] },
  { sku: 'NTP-GH003', name: 'Ghe Thu Gian Lounge Rattan', featured: 0, sale: 3500000, regular: 4200000, category: 'Ghe', tags: 'ghe lounge, rattan', stock: 25, weight: 12, length: 72, width: 78, height: 86, image: imagePool.ghe[2] },
  { sku: 'NTP-GH004', name: 'Ghe Bar Chan Sat Loft', featured: 0, sale: '', regular: 1890000, category: 'Ghe', tags: 'ghe bar, loft', stock: 48, weight: 7, length: 42, width: 42, height: 102, image: imagePool.ghe[3] },
  { sku: 'NTP-GH005', name: 'Ghe An Boc Da Classic', featured: 1, sale: 2150000, regular: 2500000, category: 'Ghe', tags: 'ghe an, boc da', stock: 38, weight: 9, length: 47, width: 54, height: 88, image: imagePool.ghe[4] },
  { sku: 'NTP-GH006', name: 'Ghe Xoay Lam Viec Neo', featured: 0, sale: 2990000, regular: 3590000, category: 'Ghe', tags: 'ghe xoay, workstation', stock: 34, weight: 14, length: 64, width: 64, height: 112, image: imagePool.ghe[5] },

  // TU & KE (6)
  { sku: 'NTP-TK001', name: 'Tu Sach 5 Tang Minimalist Oak', featured: 1, sale: 3900000, regular: 4500000, category: 'Tu & Ke', tags: 'tu sach, minimalist', stock: 26, weight: 30, length: 80, width: 35, height: 180, image: imagePool.tuke[0] },
  { sku: 'NTP-TK002', name: 'Ke Tivi Go Cao Su Rustic 1m8', featured: 0, sale: '', regular: 6500000, category: 'Tu & Ke', tags: 'ke tivi, rustic', stock: 22, weight: 40, length: 180, width: 40, height: 55, image: imagePool.tuke[1] },
  { sku: 'NTP-TK003', name: 'Tu Giay Thong Minh Flip 24', featured: 0, sale: 3100000, regular: 3600000, category: 'Tu & Ke', tags: 'tu giay, thong minh', stock: 30, weight: 24, length: 90, width: 30, height: 120, image: imagePool.tuke[2] },
  { sku: 'NTP-TK004', name: 'Ke Treo Tuong Gallery 4 Tang', featured: 0, sale: '', regular: 2100000, category: 'Tu & Ke', tags: 'ke treo tuong, decor', stock: 45, weight: 8, length: 120, width: 24, height: 78, image: imagePool.tuke[3] },
  { sku: 'NTP-TK005', name: 'Tu Quan Ao Canh Lua Minimal', featured: 1, sale: 9800000, regular: 11200000, category: 'Tu & Ke', tags: 'tu quan ao, canh lua', stock: 14, weight: 65, length: 180, width: 60, height: 210, image: imagePool.tuke[4] },
  { sku: 'NTP-TK006', name: 'Ke Trung Bay Kinh Luxury', featured: 0, sale: 5400000, regular: 6200000, category: 'Tu & Ke', tags: 'ke trung bay, kinh', stock: 18, weight: 34, length: 100, width: 38, height: 185, image: imagePool.tuke[5] },

  // GIUONG (6)
  { sku: 'NTP-GN001', name: 'Giuong Ngu King Size Walnut', featured: 1, sale: 22900000, regular: 25900000, category: 'Giuong & Nem', tags: 'giuong ngu, king size', stock: 12, weight: 80, length: 200, width: 180, height: 110, image: imagePool.giuong[0] },
  { sku: 'NTP-GN002', name: 'Giuong Queen Linen Softtouch', featured: 0, sale: 14200000, regular: 16500000, category: 'Giuong & Nem', tags: 'giuong queen, linen', stock: 16, weight: 62, length: 200, width: 160, height: 105, image: imagePool.giuong[1] },
  { sku: 'NTP-GN003', name: 'Giuong Tang Tre Em Dino', featured: 0, sale: '', regular: 9800000, category: 'Giuong & Nem', tags: 'giuong tang, tre em', stock: 15, weight: 58, length: 200, width: 120, height: 170, image: imagePool.giuong[2] },
  { sku: 'NTP-GN004', name: 'Nem Foam Cool Gel 1m6', featured: 0, sale: 6900000, regular: 7900000, category: 'Giuong & Nem', tags: 'nem foam, cool gel', stock: 28, weight: 22, length: 200, width: 160, height: 25, image: imagePool.giuong[3] },
  { sku: 'NTP-GN005', name: 'Giuong Co Ngan Keo Storage Max', featured: 1, sale: 16900000, regular: 18900000, category: 'Giuong & Nem', tags: 'giuong storage, ngan keo', stock: 13, weight: 74, length: 220, width: 180, height: 115, image: imagePool.giuong[4] },
  { sku: 'NTP-GN006', name: 'Nem Latex Natural Sleep 1m8', featured: 0, sale: '', regular: 13500000, category: 'Giuong & Nem', tags: 'nem latex, cao cap', stock: 20, weight: 29, length: 200, width: 180, height: 22, image: imagePool.giuong[5] }
];

function escapeCsv(value) {
  const text = value === null || value === undefined ? '' : String(value);
  return `"${text.replace(/"/g, '""')}"`;
}

function buildDescription(name, category, length, width, height, weight) {
  return `<h3>${name}</h3><p>San pham ${category.toLowerCase()} thiet ke hien dai, toi uu cho khong gian song tien nghi va tham my.</p><ul><li>Chat lieu ben dep, de bao tri</li><li>Thiet ke phu hop nhieu phong cach noi that</li><li>Kich thuoc: ${length} x ${width} x ${height} cm</li><li>Khoi luong: ${weight} kg</li><li>Bao hanh 12 thang</li></ul>`;
}

const rows = products.map((p) => {
  const shortDesc = `${p.name} - thiet ke dep, chat lieu ben, phu hop cho khong gian song hien dai.`;
  const description = buildDescription(p.name, p.category, p.length, p.width, p.height, p.weight);
  return [
    '',
    'simple',
    p.sku,
    p.name,
    1,
    p.featured,
    'visible',
    shortDesc,
    description,
    p.sale,
    p.regular,
    p.category,
    p.tags,
    p.image,
    p.stock,
    p.weight,
    p.length,
    p.width,
    p.height
  ].map(escapeCsv).join(',');
});

const content = [headers.map(escapeCsv).join(','), ...rows].join('\n');
fs.writeFileSync(filePath, content, 'utf8');
console.log(`Generated ${products.length} products at ${filePath}`);
