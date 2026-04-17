const fs = require('fs');
const path = require('path');
const { JSDOM } = require('jsdom');

const DEMO_PATH = path.join(__dirname, '..', 'demo.html');
const html = fs.readFileSync(DEMO_PATH, 'utf8');

function wait(ms = 80) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

function expect(condition, message) {
  if (!condition) {
    throw new Error(message);
  }
}

function getProductIds(doc) {
  return [...doc.querySelectorAll('.np-products-grid .np-product-card .np-add-to-cart')]
    .map((btn) => {
      const onclick = btn.getAttribute('onclick') || '';
      const match = onclick.match(/\((\d+)\)/);
      return match ? Number(match[1]) : null;
    })
    .filter((id) => Number.isInteger(id));
}

function noIntersection(a, b) {
  const setB = new Set(b);
  return a.every((x) => !setB.has(x));
}

function parsePrice(text) {
  const digits = String(text || '').replace(/[^0-9]/g, '');
  return digits ? Number(digits) : NaN;
}

function clearCart(window) {
  for (let id = 1; id <= 60; id += 1) {
    window.removeFromCart(id);
  }
}

async function main() {
  const dom = new JSDOM(html, {
    runScripts: 'dangerously',
    resources: 'usable',
    url: 'http://localhost/',
    pretendToBeVisual: true,
    beforeParse(window) {
      window.scrollTo = () => {};
      window.IntersectionObserver = class {
        constructor(callback) {
          this.callback = callback;
        }
        observe(target) {
          this.callback([{ isIntersecting: true, target }], this);
        }
        unobserve() {}
        disconnect() {}
      };
      window.requestAnimationFrame = (cb) => setTimeout(cb, 0);
      window.cancelAnimationFrame = (id) => clearTimeout(id);
    },
  });

  const { window } = dom;
  const { document } = window;

  const results = [];

  async function runTest(name, fn) {
    try {
      await fn();
      results.push({ name, ok: true });
    } catch (error) {
      results.push({ name, ok: false, error: error.message });
    }
  }

  await wait(200);

  await runTest('Home page initial render', async () => {
    expect(document.querySelector('.np-hero'), 'Hero section should render');
  });

  await runTest('Shop default pagination page 1', async () => {
    window.navigate('shop');
    await wait();
    const ids = getProductIds(document);
    expect(ids.length === 12, `Expected 12 products on page 1, got ${ids.length}`);
    const countEl = document.querySelector('.np-shop-count');
    expect(countEl && countEl.textContent.includes('Trang 1/3'), 'Shop count should show page 1/3');
  });

  await runTest('Shop pagination pages 2 and 3 show correct items', async () => {
    window.navigate('shop');
    await wait();
    const page1 = getProductIds(document);

    window.goToShopPage(2);
    await wait();
    const page2 = getProductIds(document);

    window.goToShopPage(3);
    await wait();
    const page3 = getProductIds(document);

    expect(page1.length === 12, 'Page 1 must have 12 products');
    expect(page2.length === 12, 'Page 2 must have 12 products');
    expect(page3.length === 6, 'Page 3 must have 6 products');
    expect(noIntersection(page1, page2), 'Page 1 and 2 should not duplicate products');
    expect(noIntersection(page2, page3), 'Page 2 and 3 should not duplicate products');
    expect(noIntersection(page1, page3), 'Page 1 and 3 should not duplicate products');

    const all = new Set([...page1, ...page2, ...page3]);
    expect(all.size === 30, `Expected 30 unique products across all pages, got ${all.size}`);
  });

  await runTest('Filter reset page index and recalculate total pages', async () => {
    window.navigate('shop');
    await wait();
    window.goToShopPage(3);
    await wait();

    window.applyShopFilters({ category: 'Sofa' });
    await wait();

    const ids = getProductIds(document);
    expect(ids.length === 6, `Sofa should have 6 products, got ${ids.length}`);
    const countEl = document.querySelector('.np-shop-count');
    expect(countEl && countEl.textContent.includes('Trang 1/1 (6 SP)'), 'Filtered page count should be 1/1 (6 SP)');
  });

  await runTest('Category alias filter from footer link (Tủ) still returns products', async () => {
    window.navigate('shop', 'Tủ');
    await wait();
    const ids = getProductIds(document);
    expect(ids.length === 5, `Expected 5 products for Tủ alias, got ${ids.length}`);
  });

  await runTest('Keyword and price filters work together', async () => {
    window.navigate('shop');
    await wait();

    window.applyShopFilters({ keyword: 'gaming' });
    await wait();
    let ids = getProductIds(document);
    expect(ids.length === 1, `Expected 1 gaming product, got ${ids.length}`);

    window.applyShopFilters({ keyword: '', minPrice: 10000000, maxPrice: 16000000 });
    await wait();

    ids = getProductIds(document);
    expect(ids.length > 0, 'Price filter should return at least one product in configured range');
    expect(ids.length <= 12, 'Price filter first page should not exceed ITEMS_PER_PAGE');

    const prices = [...document.querySelectorAll('.np-products-grid .np-product-card .np-price-current')].map((el) => parsePrice(el.textContent));
    expect(prices.length === ids.length, 'Each rendered card should have current price');
    expect(prices.every((p) => p >= 10000000 && p <= 16000000), 'Rendered prices must be within [10M, 16M]');
  });

  await runTest('Search overlay and results', async () => {
    window.openSearch();
    await wait();
    expect(document.getElementById('search-overlay').classList.contains('active'), 'Search overlay should open');

    window.handleSearch('sofa');
    await wait();
    const resultItems = document.querySelectorAll('.np-search-result-item').length;
    expect(resultItems > 0, 'Search for sofa should return results');

    window.handleSearch('khongtontai123');
    await wait();
    const noResultText = document.getElementById('search-results').textContent || '';
    expect(noResultText.includes('Không tìm thấy'), 'Should show not found message');

    window.closeSearch();
    await wait();
    expect(!document.getElementById('search-overlay').classList.contains('active'), 'Search overlay should close');
  });

  await runTest('Cart add/update/remove logic', async () => {
    clearCart(window);

    window.addToCart(1);
    window.addToCart(1);
    window.addToCart(2);

    expect(window.getCartCount() === 3, `Expected cart count 3, got ${window.getCartCount()}`);

    window.changeQty(2, -1);
    expect(window.getCartCount() === 2, 'Cart count should be 2 after remove one line');

    window.removeFromCart(1);
    expect(window.getCartCount() === 0, 'Cart should be empty after removing product 1');
  });

  await runTest('Product detail quantity + add to cart', async () => {
    clearCart(window);

    window.navigate('product', 1);
    await wait();
    expect(document.getElementById('detail-qty'), 'Detail qty control should render');

    window.detailQty(1);
    window.detailQty(1);
    expect(document.getElementById('detail-qty').textContent.trim() === '3', 'Detail qty should be 3');

    window.addToCartQty(1);
    expect(window.getCartCount() === 3, `Expected cart count 3 after addToCartQty, got ${window.getCartCount()}`);
  });

  await runTest('Checkout place order flow', async () => {
    clearCart(window);
    window.addToCart(1);
    window.addToCart(2);

    window.navigate('checkout');
    await wait();

    const name = document.getElementById('ck-name');
    const phone = document.getElementById('ck-phone');
    const address = document.getElementById('ck-address');

    expect(name && phone && address, 'Checkout required fields should exist');

    name.value = 'Tester';
    phone.value = '0900000000';
    address.value = '123 Test Street';

    window.placeOrder();
    await wait();

    const appText = document.getElementById('app').textContent || '';
    expect(appText.includes('Đặt Hàng Thành Công'), 'Order success message should render');
    expect(window.getCartCount() === 0, 'Cart should be cleared after successful checkout');
  });

  await runTest('Navigation: about, contact, cart pages', async () => {
    window.navigate('about');
    await wait();
    expect((document.getElementById('app').textContent || '').includes('Câu Chuyện NoiThat Pro'), 'About page should render');

    window.navigate('contact');
    await wait();
    expect((document.getElementById('app').textContent || '').includes('Liên Hệ Với Chúng Tôi'), 'Contact page should render');

    clearCart(window);
    window.navigate('cart');
    await wait();
    expect((document.getElementById('app').textContent || '').includes('Giỏ hàng trống'), 'Cart page empty state should render');
  });

  await runTest('Header mobile menu toggle', async () => {
    const toggle = document.getElementById('np-menu-toggle');
    const nav = document.getElementById('np-main-nav');

    toggle.click();
    expect(nav.classList.contains('active'), 'Nav should open after first toggle click');

    toggle.click();
    expect(!nav.classList.contains('active'), 'Nav should close after second toggle click');
  });

  const passed = results.filter((r) => r.ok).length;
  const failed = results.filter((r) => !r.ok);

  console.log('=== SMOKE TEST RESULTS ===');
  for (const result of results) {
    if (result.ok) {
      console.log(`PASS: ${result.name}`);
    } else {
      console.log(`FAIL: ${result.name}`);
      console.log(`  -> ${result.error}`);
    }
  }

  console.log('--------------------------');
  console.log(`Total: ${results.length}`);
  console.log(`Passed: ${passed}`);
  console.log(`Failed: ${failed.length}`);

  dom.window.close();

  if (failed.length > 0) {
    process.exitCode = 1;
  }
}

main().catch((error) => {
  console.error('Smoke test runner crashed:', error);
  process.exitCode = 1;
});
