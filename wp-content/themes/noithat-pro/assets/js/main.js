/**
 * NoiThat Pro - Main JavaScript
 * 
 * File JavaScript chính cho theme NoiThat Pro
 * Xử lý: menu mobile, scroll effects, animations, AJAX cart
 * 
 * @package NoiThat_Pro
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // ========================================================================
    // 1. HEADER SCROLL EFFECT
    // Thêm class 'scrolled' khi cuộn trang
    // ========================================================================
    const header = document.querySelector('.np-header');
    
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

    // ========================================================================
    // 2. MOBILE MENU TOGGLE
    // Bật/tắt menu trên mobile
    // ========================================================================
    const menuToggle = document.querySelector('.np-menu-toggle');
    const navMenu = document.querySelector('.np-nav');
    
    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            
            // Animation cho hamburger icon
            const spans = this.querySelectorAll('span');
            this.classList.toggle('open');
            
            if (this.classList.contains('open')) {
                spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                spans[1].style.opacity = '0';
                spans[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
            } else {
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            }
        });
        
        // Đóng menu khi click ngoài
        document.addEventListener('click', function(e) {
            if (!menuToggle.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('active');
                menuToggle.classList.remove('open');
                const spans = menuToggle.querySelectorAll('span');
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            }
        });
    }

    // ========================================================================
    // 3. SCROLL ANIMATIONS (Intersection Observer)
    // Hiệu ứng fade-in khi phần tử xuất hiện trong viewport
    // ========================================================================
    const animateElements = document.querySelectorAll('.np-animate');
    
    if (animateElements.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        animateElements.forEach(function(el) {
            observer.observe(el);
        });
    }

    // ========================================================================
    // 4. BACK TO TOP BUTTON
    // Nút cuộn lên đầu trang
    // ========================================================================
    const backToTop = document.querySelector('.np-back-to-top');
    
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 500) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });
        
        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // ========================================================================
    // 5. SMOOTH SCROLL cho các anchor links
    // ========================================================================
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ========================================================================
    // 6. AJAX CART UPDATE (WooCommerce)
    // Cập nhật số lượng giỏ hàng không cần reload trang
    // ========================================================================
    if (typeof noithatProData !== 'undefined') {
        // Sau khi thêm sản phẩm vào giỏ
        $(document.body).on('added_to_cart', function() {
            updateCartCount();
        });
        
        // Sau khi xóa sản phẩm khỏi giỏ
        $(document.body).on('removed_from_cart', function() {
            updateCartCount();
        });
        
        function updateCartCount() {
            $.ajax({
                url: noithatProData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'noithat_cart_count',
                    nonce: noithatProData.nonce
                },
                success: function(response) {
                    if (response.success) {
                        var countEl = document.querySelector('.np-cart-count');
                        if (countEl) {
                            countEl.textContent = response.data.count;
                            
                            // Animation khi cập nhật
                            countEl.style.transform = 'scale(1.3)';
                            setTimeout(function() {
                                countEl.style.transform = 'scale(1)';
                            }, 300);
                        }
                    }
                }
            });
        }
    }

    // ========================================================================
    // 7. PRODUCT IMAGE ZOOM (hover effect trên mobile)
    // ========================================================================
    const productImages = document.querySelectorAll('.np-product-img-wrap');
    
    productImages.forEach(function(wrap) {
        wrap.addEventListener('mousemove', function(e) {
            const img = this.querySelector('img');
            if (!img || window.innerWidth < 768) return;
            
            const rect = this.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            
            img.style.transformOrigin = x + '% ' + y + '%';
        });
    });

    // ========================================================================
    // 7.1 PRODUCT IMAGE FALLBACK (khi ảnh URL ngoài lỗi tải)
    // ========================================================================
    const fallbackImage = (typeof noithatProData !== 'undefined' && noithatProData.placeholderImage)
        ? noithatProData.placeholderImage
        : '';

    if (fallbackImage) {
        const loopImages = document.querySelectorAll('.woocommerce ul.products li.product img, .np-product-img-wrap img');

        loopImages.forEach(function(img) {
            const applyFallback = function() {
                if (img.dataset.npFallbackApplied === '1') return;
                img.dataset.npFallbackApplied = '1';
                img.src = fallbackImage;
                img.removeAttribute('srcset');
                img.removeAttribute('sizes');
            };

            img.addEventListener('error', applyFallback);

            // Trường hợp ảnh đã lỗi trước khi listener được gắn
            if (img.complete && img.naturalWidth === 0) {
                applyFallback();
            }
        });
    }

    // ========================================================================
    // 8. COUNTER ANIMATION (cho hero stats)
    // Đếm số từ 0 đến giá trị thực
    // ========================================================================
    function animateCounter(element) {
        const target = parseInt(element.getAttribute('data-count')) || 0;
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(function() {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current).toLocaleString('vi-VN');
        }, 16);
    }
    
    // Kích hoạt counter animation khi xuất hiện trong viewport
    const counters = document.querySelectorAll('[data-count]');
    if (counters.length > 0 && 'IntersectionObserver' in window) {
        const counterObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    counterObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        counters.forEach(function(counter) {
            counterObserver.observe(counter);
        });
    }

    // ========================================================================
    // 9. PRODUCT QUICK VIEW (placeholder)
    // ========================================================================
    $(document).on('click', '.np-quick-view-btn', function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        // Có thể mở rộng với modal quick view
        window.location.href = $(this).attr('href');
    });

    // ========================================================================
    // 10. SEARCH TOGGLE
    // ========================================================================
    const searchToggle = document.querySelector('.np-search-toggle');
    const searchForm = document.getElementById('np-search-form');
    
    if (searchToggle && searchForm) {
        searchToggle.addEventListener('click', function() {
            var isHidden = searchForm.style.display === 'none' || searchForm.style.display === '';
            if (isHidden) {
                searchForm.style.display = 'block';
                var input = searchForm.querySelector('input[type="search"]');
                if (input) input.focus();
            } else {
                searchForm.style.display = 'none';
            }
        });
    }

    // ========================================================================
    // 11. CONTACT FORM FALLBACK HANDLER
    // Chỉ dùng cho bản demo; production submit backend sẽ không bị chặn
    // ========================================================================
    var contactForm = document.getElementById('np-contact-form');
    
    if (contactForm && contactForm.dataset.demoSubmit === '1') {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var submitBtn = contactForm.querySelector('button[type="submit"]');
            var origHTML = submitBtn.innerHTML;
            
            // Hiển thị loading
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';
            submitBtn.disabled = true;
            
            // Giả lập gửi form (vì không có backend xử lý)
            setTimeout(function() {
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Đã gửi thành công!';
                submitBtn.style.background = '#4CAF50';
                contactForm.reset();
                
                setTimeout(function() {
                    submitBtn.innerHTML = origHTML;
                    submitBtn.style.background = '';
                    submitBtn.disabled = false;
                }, 3000);
            }, 1500);
        });
    }

    // ========================================================================
    // 12. ADD-TO-CART ANIMATION (cho custom buttons)
    // ========================================================================
    $(document).on('click', '.np-add-to-cart', function() {
        var btn = this;
        var origHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Đã thêm!';
        btn.style.background = '#4CAF50';
        btn.style.color = 'white';
        
        setTimeout(function() {
            btn.innerHTML = origHTML;
            btn.style.background = '';
            btn.style.color = '';
        }, 1500);
    });

    // Khởi tạo khi DOM ready
    console.log('NoiThat Pro theme loaded successfully!');

})(jQuery);
