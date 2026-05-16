// ================= DATA =================
let products = [];
        let articles = [];
        let galleryItems = [];
        let settings = {};
        let calls = [
            { id: 1, name: 'أحمد محمد', phone: '01012345678', type: 'whatsapp', subject: 'طلب 50 كجم فحم جوافة', date: 'منذ 5 دقائق', status: 'new' },
            { id: 2, name: 'محمد علي', phone: '01098765432', type: 'phone', subject: 'استفسار عن أسعار الجملة', date: 'منذ 30 دقيقة', status: 'replied' },
            { id: 3, name: 'خالد محمود', phone: '01122334455', type: 'whatsapp', subject: 'طلب تصدير 5 أطنان', date: 'منذ ساعة', status: 'new' },
            { id: 4, name: 'سامي عبدالله', phone: '01234567890', type: 'phone', subject: 'طلب 80 كجم فحم زيتون', date: 'منذ 3 ساعات', status: 'replied' },
            { id: 5, name: 'عمر حسن', phone: '01555667788', type: 'whatsapp', subject: 'استفسار عن مواعيد التوصيل', date: 'أمس', status: 'replied' },
            { id: 6, name: 'فهد سالم', phone: '01011223344', type: 'phone', subject: 'طلب 200 كجم للمطعم', date: 'أمس', status: 'converted' },
            { id: 7, name: 'ناصر عبدالرحمن', phone: '01055667788', type: 'whatsapp', subject: 'استفسار عن التصدير للسعودية', date: '2024-05-13', status: 'new' },
            { id: 8, name: 'يوسف أحمد', phone: '01099887766', type: 'phone', subject: 'شكوى عن جودة الشحنة الأخيرة', date: '2024-05-12', status: 'replied' }
        ];

        let editingProductId = null;
        let editingArticleId = null;
        let editingGalleryId = null;

        async function apiRequest(action, data = null) {
            const url = `api.php?action=${action}`;
            const options = {
                method: data instanceof FormData ? 'POST' : data ? 'POST' : 'GET',
                credentials: 'same-origin',
            };
            if (data instanceof FormData) {
                options.body = data;
            } else if (data) {
                options.headers = { 'Content-Type': 'application/json' };
                options.body = JSON.stringify(data);
            }
            const response = await fetch(url, options);
            return response.json();
        }

        async function loadSettings() {
            try {
                const response = await apiRequest('get_settings');
                if (response.success) {
                    settings = response.settings || {};
                    applyThemeSettings();
                    populateSettingsForm();
                    if (products.length) renderPublicProducts();
                }
            } catch (error) {
                console.error('فشل تحميل إعدادات الموقع', error);
            }
        }

        function getSetting(key, fallback = '') {
            return settings[key] || fallback;
        }

        function populateSettingsForm() {
            const map = {
                siteName: 'site_name',
                siteNameHighlight: 'site_name_highlight',
                siteDescription: 'site_tagline',
                heroBadge: 'hero_badge',
                heroHeadline: 'hero_headline',
                heroText: 'hero_text',
                aboutSectionTitle: 'about_section_title',
                aboutSectionSubtitle: 'about_section_subtitle',
                aboutTitle: 'about_title',
                aboutText: 'about_text',
                aboutText2: 'about_text_2',
                productsSectionTitle: 'products_section_title',
                productsSectionDescription: 'products_section_description',
                gallerySectionTitle: 'gallery_section_title',
                gallerySectionDescription: 'gallery_section_description',
                articlesSectionTitle: 'articles_section_title',
                articlesSectionDescription: 'articles_section_description',
                contactSectionTitle: 'contact_section_title',
                contactSectionDescription: 'contact_section_description',
                footerDescription: 'footer_description',
                contactAddress: 'contact_address',
                contactPhone: 'contact_phone_display',
                contactWhatsApp: 'contact_whatsapp_display',
                contactWhatsAppUrl: 'contact_whatsapp_url',
                orderMessageTemplate: 'hero_whatsapp_message',
                contactWorkHours: 'contact_work_hours',
                themeAccent: 'theme_accent',
                themeWhatsapp: 'theme_whatsapp'
            };
            Object.entries(map).forEach(([fieldId, key]) => {
                const element = document.getElementById(fieldId);
                if (!element) return;
                if (element.tagName.toLowerCase() === 'textarea') {
                    element.value = getSetting(key, element.value);
                } else {
                    element.value = getSetting(key, element.value);
                }
            });
        }

        async function saveSettings() {
            const payload = {
                settings: {
                    site_name: document.getElementById('siteName')?.value.trim() || getSetting('site_name'),
                    site_name_highlight: document.getElementById('siteNameHighlight')?.value.trim() || getSetting('site_name_highlight'),
                    site_tagline: document.getElementById('siteDescription')?.value.trim() || getSetting('site_tagline'),
                    hero_badge: document.getElementById('heroBadge')?.value.trim() || getSetting('hero_badge'),
                    hero_headline: document.getElementById('heroHeadline')?.value.trim() || getSetting('hero_headline'),
                    hero_text: document.getElementById('heroText')?.value.trim() || getSetting('hero_text'),
                    about_section_title: document.getElementById('aboutSectionTitle')?.value.trim() || getSetting('about_section_title'),
                    about_section_subtitle: document.getElementById('aboutSectionSubtitle')?.value.trim() || getSetting('about_section_subtitle'),
                    about_title: document.getElementById('aboutTitle')?.value.trim() || getSetting('about_title'),
                    about_text: document.getElementById('aboutText')?.value.trim() || getSetting('about_text'),
                    about_text_2: document.getElementById('aboutText2')?.value.trim() || getSetting('about_text_2'),
                    products_section_title: document.getElementById('productsSectionTitle')?.value.trim() || getSetting('products_section_title'),
                    products_section_description: document.getElementById('productsSectionDescription')?.value.trim() || getSetting('products_section_description'),
                    gallery_section_title: document.getElementById('gallerySectionTitle')?.value.trim() || getSetting('gallery_section_title'),
                    gallery_section_description: document.getElementById('gallerySectionDescription')?.value.trim() || getSetting('gallery_section_description'),
                    articles_section_title: document.getElementById('articlesSectionTitle')?.value.trim() || getSetting('articles_section_title'),
                    articles_section_description: document.getElementById('articlesSectionDescription')?.value.trim() || getSetting('articles_section_description'),
                    contact_section_title: document.getElementById('contactSectionTitle')?.value.trim() || getSetting('contact_section_title'),
                    contact_section_description: document.getElementById('contactSectionDescription')?.value.trim() || getSetting('contact_section_description'),
                    footer_description: document.getElementById('footerDescription')?.value.trim() || getSetting('footer_description'),
                    contact_address: document.getElementById('contactAddress')?.value.trim() || getSetting('contact_address'),
                    contact_phone_display: document.getElementById('contactPhone')?.value.trim() || getSetting('contact_phone_display'),
                    contact_whatsapp_display: document.getElementById('contactWhatsApp')?.value.trim() || getSetting('contact_whatsapp_display'),
                    contact_whatsapp_url: document.getElementById('contactWhatsAppUrl')?.value.trim() || getSetting('contact_whatsapp_url'),
                    hero_whatsapp_message: document.getElementById('orderMessageTemplate')?.value.trim() || getSetting('hero_whatsapp_message'),
                    contact_work_hours: document.getElementById('contactWorkHours')?.value.trim() || getSetting('contact_work_hours'),
                    theme_accent: document.getElementById('themeAccent')?.value || getSetting('theme_accent'),
                    theme_whatsapp: document.getElementById('themeWhatsapp')?.value || getSetting('theme_whatsapp')
                }
            };

            const response = await apiRequest('save_settings', payload);
            if (response.success) {
                await loadSettings();
                showToast(response.message || 'تم حفظ إعدادات الموقع بنجاح', 'success');
            } else {
                showToast(response.message || 'فشل حفظ الإعدادات', 'error');
            }
        }

        function applyThemeSettings() {
            const accent = getSetting('theme_accent');
            const whatsapp = getSetting('theme_whatsapp');
            if (accent) document.documentElement.style.setProperty('--accent', accent);
            if (whatsapp) document.documentElement.style.setProperty('--whatsapp', whatsapp);
        }

        function buildWhatsAppOrderLink(productName) {
            const directUrl = getSetting('contact_whatsapp_url', '').trim();
            if (directUrl) {
                return directUrl;
            }
            const phone = getSetting('contact_whatsapp_display', '+201234567890').replace(/[^0-9]/g, '');
            const template = getSetting('hero_whatsapp_message', 'مرحباً، أود طلب هذا المنتج:');
            const message = `${template} ${productName}`;
            return `https://wa.me/${phone || '201234567890'}?text=${encodeURIComponent(message)}`;
        }

        // ================= INITIALIZATION =================
        document.addEventListener('DOMContentLoaded', function() {
            loadSettings();
            if (document.getElementById('productsGrid')) loadProducts();
            if (document.getElementById('articlesGrid')) loadArticles();
            if (document.getElementById('galleryGrid')) loadGallery();
            if (document.getElementById('callsTableBody')) renderCalls();
        });

        // ================= NAVBAR SCROLL =================
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // ================= MOBILE MENU =================
        function toggleMobileMenu() {
            const navLinks = document.getElementById('navLinks');
            if (navLinks) navLinks.classList.toggle('active');
        }

        // ================= SMOOTH SCROLL =================
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    document.querySelectorAll('.nav-links a').forEach(a => a.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById('navLinks').classList.remove('active');
                }
            });
        });

        // ================= PUBLIC PRODUCTS =================
        function renderPublicProducts() {
            const grid = document.getElementById('productsGrid');
            if (!grid) return;
            const activeProducts = products.filter(p => p.status === 'active');

            grid.innerHTML = activeProducts.map(product => {
                const icons = {
                    guava: 'fa-apple-alt',
                    orange: 'fa-lemon',
                    lemon: 'fa-lemon',
                    olive: 'fa-oil-can',
                    mixed: 'fa-layer-group'
                };
                const typeNames = {
                    guava: 'فحم جوافة',
                    orange: 'فحم برتقال',
                    lemon: 'فحم ليمون',
                    olive: 'فحم زيتون',
                    mixed: 'فحم مشكل'
                };

                return `
                    <div class="product-card">
                        <div class="product-image">
                            <span class="product-badge">${typeNames[product.type] || product.type}</span>
                            <i class="fas ${icons[product.type] || 'fa-fire'}"></i>
                        </div>
                        <div class="product-info">
                            <h3>${product.name}</h3>
                            <p>${product.desc}</p>
                            <div class="product-meta">
                                <div class="product-price">${product.price} <span>ج.م/كجم</span></div>
                                <a href="${buildWhatsAppOrderLink(product.name)}" target="_blank" class="btn btn-sm btn-whatsapp">
                                    <i class="fab fa-whatsapp"></i> اطلب
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // ================= ADMIN LOGIN =================
        function showAdminLogin() {
            document.getElementById('publicSite')?.classList.remove('active');
            document.getElementById('adminLogin')?.classList.add('active');
            document.getElementById('adminPanel')?.classList.remove('active');
            window.scrollTo(0, 0);
        }

        async function handleLogin(e) {
            e.preventDefault();
            const username = document.getElementById('loginUsername').value.trim();
            const password = document.getElementById('loginPassword').value.trim();

            const response = await apiRequest('login', { username, password });
            if (response.success) {
                window.location.href = 'admin.php';
            } else {
                showToast(response.message || 'اسم المستخدم أو كلمة المرور غير صحيحة', 'error');
            }
        }

        async function logout() {
            await apiRequest('logout');
            window.location.href = 'admin.php';
        }

        function toggleAdminSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (!sidebar) return;
            sidebar.classList.toggle('active');
        }

        function showPublicSite() {
            const publicSite = document.getElementById('publicSite');
            if (publicSite) {
                document.getElementById('adminPanel')?.classList.remove('active');
                document.getElementById('adminLogin')?.classList.remove('active');
                publicSite.classList.add('active');
                window.scrollTo(0, 0);
                return;
            }
            window.location.href = 'index.php';
        }

        // ================= ADMIN PAGES =================
        function showAdminPage(pageName, element) {
            document.querySelectorAll('.admin-page').forEach(page => page.classList.remove('active'));
            document.getElementById('page-' + pageName).classList.add('active');

            document.querySelectorAll('.sidebar-nav a').forEach(link => link.classList.remove('active'));
            if (element) {
                element.classList.add('active');
            }
        }

        // ================= PRODUCTS MANAGEMENT =================
        async function renderProducts() {
            const tbody = document.getElementById('productsTableBody');
            if (!tbody) return;
            const typeNames = {
                guava: 'فحم جوافة',
                orange: 'فحم برتقال',
                lemon: 'فحم ليمون',
                olive: 'فحم زيتون',
                mixed: 'فحم مشكل'
            };

            tbody.innerHTML = products.map((product, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <div class="product-cell">
                            <div class="product-cell-img"><i class="fas fa-fire"></i></div>
                            <div class="product-cell-info">
                                <h4>${product.name}</h4>
                                <span>${(product.description || product.desc || '').substring(0, 40)}...</span>
                            </div>
                        </div>
                    </td>
                    <td>${typeNames[product.type] || product.type}</td>
                    <td style="font-weight:700;">${product.price} ج.م</td>
                    <td>${product.stock} كجم</td>
                    <td><span class="status-badge ${product.status === 'active' ? 'status-active' : 'status-inactive'}">${product.status === 'active' ? 'نشط' : 'غير نشط'}</span></td>
                    <td class="table-actions">
                        <button class="btn-edit" onclick="editProduct(${product.id})" title="تعديل"><i class="fas fa-edit"></i></button>
                        <button class="btn-delete" onclick="deleteProduct(${product.id})" title="حذف"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `).join('');
        }

        function openProductModal() {
            editingProductId = null;
            document.getElementById('modalTitle').textContent = 'إضافة منتج جديد';
            document.getElementById('productName').value = '';
            document.getElementById('productType').value = '';
            document.getElementById('productPrice').value = '';
            document.getElementById('productStock').value = '';
            document.getElementById('productDesc').value = '';
            document.getElementById('productModal').classList.add('active');
        }

        function closeProductModal() {
            document.getElementById('productModal').classList.remove('active');
        }

        function editProduct(id) {
            const product = products.find(p => p.id === id);
            if (!product) return;

            editingProductId = id;
            document.getElementById('modalTitle').textContent = 'تعديل منتج';
            document.getElementById('productName').value = product.name;
            document.getElementById('productType').value = product.type;
            document.getElementById('productPrice').value = product.price;
            document.getElementById('productStock').value = product.stock;
            document.getElementById('productDesc').value = product.description || product.desc || '';
            document.getElementById('productModal').classList.add('active');
        }

        async function saveProduct() {
            const name = document.getElementById('productName').value.trim();
            const type = document.getElementById('productType').value;
            const price = parseFloat(document.getElementById('productPrice').value);
            const stock = parseInt(document.getElementById('productStock').value, 10);
            const description = document.getElementById('productDesc').value.trim();

            if (!name || !type || !price) {
                showToast('يرجى ملء جميع الحقول المطلوبة', 'error');
                return;
            }

            const payload = {
                id: editingProductId,
                name,
                type,
                price,
                stock: isNaN(stock) ? 0 : stock,
                description,
                status: 'active',
                image: ''
            };

            const response = await apiRequest('save_product', payload);
            if (response.success) {
                await loadProducts();
                closeProductModal();
                showToast(response.message, 'success');
            } else {
                showToast(response.message || 'فشل حفظ المنتج', 'error');
            }
        }

        async function deleteProduct(id) {
            if (!confirm('هل أنت متأكد من حذف هذا المنتج؟')) return;
            const response = await apiRequest('delete_product', { id });
            if (response.success) {
                await loadProducts();
                showToast(response.message, 'success');
            } else {
                showToast(response.message || 'فشل حذف المنتج', 'error');
            }
        }

        async function loadProducts() {
            const response = await apiRequest('get_products');
            if (response.success) {
                products = response.products;
                renderProducts();
                renderPublicProducts();
            }
        }

        // ================= CALLS/CONTACTS =================
        function renderCalls() {
            const tbody = document.getElementById('callsTableBody');

            tbody.innerHTML = calls.map(call => {
                const typeIcon = call.type === 'whatsapp' 
                    ? '<span style="color:var(--whatsapp)"><i class="fab fa-whatsapp"></i> واتساب</span>'
                    : '<span style="color:var(--phone)"><i class="fas fa-phone-alt"></i> اتصال</span>';

                const statusBadge = call.status === 'new' 
                    ? '<span class="status-badge status-pending">جديد</span>'
                    : call.status === 'replied'
                    ? '<span class="status-badge status-active">تم الرد</span>'
                    : '<span class="status-badge status-active">تم التحويل</span>';

                return `
                    <tr>
                        <td>${typeIcon}</td>
                        <td><strong>${call.name}</strong></td>
                        <td dir="ltr">${call.phone}</td>
                        <td>${call.subject}</td>
                        <td>${statusBadge}</td>
                        <td>${call.date}</td>
                        <td class="table-actions">
                            <a href="${call.type === 'whatsapp' ? 'https://wa.me/20' + call.phone : 'tel:+20' + call.phone}" target="${call.type === 'whatsapp' ? '_blank' : '_self'}" class="btn-view" title="${call.type === 'whatsapp' ? 'فتح واتساب' : 'اتصال'}" style="text-decoration:none;">
                                <i class="${call.type === 'whatsapp' ? 'fab fa-whatsapp' : 'fas fa-phone-alt'}"></i>
                            </a>
                            <button class="btn-edit" title="تعديل" onclick="showToast('تم تحديث الحالة', 'success')"><i class="fas fa-edit"></i></button>
                            <button class="btn-delete" title="حذف" onclick="deleteCall(${call.id})"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function deleteCall(id) {
            if (!confirm('هل أنت متأكد من حذف هذا السجل؟')) return;
            calls = calls.filter(c => c.id !== id);
            renderCalls();
            showToast('تم حذف السجل بنجاح', 'success');
        }

        // ================= SETTINGS TABS =================
        function showSettingsTab(tabName, element) {
            document.querySelectorAll('.settings-tab').forEach(tab => tab.classList.remove('active'));
            if (element) element.classList.add('active');

            document.querySelectorAll('.settings-panel').forEach(panel => panel.classList.remove('active'));
            document.getElementById('tab-' + tabName)?.classList.add('active');
        }

        // ================= TOAST =================
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;

            const icons = {
                success: 'fa-check-circle',
                error: 'fa-times-circle',
                warning: 'fa-exclamation-triangle'
            };

            toast.innerHTML = `
                <i class="fas ${icons[type]}"></i>
                <span>${message}</span>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(-50px)';
                setTimeout(() => toast.remove(), 400);
            }, 3000);
        }

        // ================= MODAL CLOSE ON OVERLAY CLICK =================
        const productModal = document.getElementById('productModal');
        if (productModal) {
            productModal.addEventListener('click', function(e) {
                if (e.target === this) closeProductModal();
            });
        }

async function loadArticles() {
    const response = await apiRequest('get_articles');
    if (response.success) {
        articles = response.articles;
        renderArticles();
        renderArticlesAdminList();
    }
}

function renderArticles() {
    const grid = document.getElementById('articlesGrid');
    if (!grid) return;

    grid.innerHTML = articles.map((article, index) => `
        <div class="article-card">
            <img src="${article.image || 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=1200&auto=format&fit=crop'}" alt="${article.title}">
            <div class="article-content">
                <h3>${article.title}</h3>
                <p>${article.description}</p>
                <button class="btn btn-primary" onclick="openArticle(${index})">
                    قراءة المزيد
                </button>
            </div>
        </div>
    `).join('');
}

function renderArticlesAdminList() {
    const adminList = document.getElementById('articlesAdminList');
    if (!adminList) return;

    adminList.innerHTML = articles.map((article, index) => `
        <div style="padding:15px;border-bottom:1px solid rgba(255,255,255,.05);display:flex;justify-content:space-between;align-items:center;gap:15px;flex-wrap:wrap;">
            <div>
                <strong>${article.title}</strong>
                <p style="color:#999;margin-top:5px;">${article.description}</p>
            </div>
            <div style="display:flex;gap:10px;">
                <button class="btn btn-outline btn-sm" onclick="editArticle(${index})">تعديل</button>
                <button class="btn btn-danger btn-sm" onclick="deleteArticle(${article.id})">حذف</button>
            </div>
        </div>
    `).join('');
}

async function saveArticle() {
    const title = document.getElementById('articleTitle').value.trim();
    const content = document.getElementById('articleEditor').value.trim();
    const imageUrl = document.getElementById('articleImageUrl').value.trim();
    const imageFile = document.getElementById('articleImageFile').files[0];

    if (!title || !content) {
        showToast('أدخل البيانات كاملة', 'error');
        return;
    }

    const form = new FormData();
    form.append('id', editingArticleId || '');
    form.append('title', title);
    form.append('content', content);
    form.append('image_url', imageUrl);
    if (imageFile) {
        form.append('image', imageFile);
    }

    const response = await apiRequest('save_article', form);
    if (response.success) {
        document.getElementById('articleTitle').value = '';
        document.getElementById('articleImageUrl').value = '';
        document.getElementById('articleImageFile').value = '';
        document.getElementById('articleEditor').value = '';
        editingArticleId = null;
        await loadArticles();
        showToast(response.message, 'success');
    } else {
        showToast(response.message || 'فشل حفظ المقال', 'error');
    }
}

async function openArticle(index) {
    const article = articles[index];
    if (!article) return;
    const newWindow = window.open('', '_blank');
    newWindow.document.write(`
        <html dir="rtl">
        <head>
            <title>${article.title}</title>
            <style>
                body{font-family:Tajawal,sans-serif;background:#111;color:#fff;padding:40px;line-height:2;}
                h1,h2,h3{color:#c9a84c;}
                img{max-width:100%;border-radius:20px;margin-bottom:25px;}
                .content{max-width:900px;}
            </style>
        </head>
        <body>
            <div class="content">
                <h1>${article.title}</h1>
                <img src="${article.image || 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=1200&auto=format&fit=crop'}" alt="${article.title}">
                ${article.content}
            </div>
        </body>
        </html>
    `);
}

async function deleteArticle(id) {
    if (!confirm('هل تريد حذف المقال؟')) return;
    const response = await apiRequest('delete_article', { id });
    if (response.success) {
        await loadArticles();
        showToast(response.message, 'success');
    } else {
        showToast(response.message || 'فشل حذف المقال', 'error');
    }
}

function editArticle(index) {
    const article = articles[index];
    if (!article) return;
    editingArticleId = article.id;
    document.getElementById('articleTitle').value = article.title;
    document.getElementById('articleImageUrl').value = article.image || '';
    document.getElementById('articleImageFile').value = '';
    document.getElementById('articleEditor').value = article.content;
}

function previewArticle() {
    const content = document.getElementById('articleEditor').value;
    const preview = window.open('', '_blank');
    preview.document.write(`<html dir="rtl"><body style="background:#111;color:#fff;font-family:Tajawal,sans-serif;padding:40px;">${content}</body></html>`);
}

function addArticle() {
    showAdminPage('articles');
    editingArticleId = null;
    document.getElementById('articleTitle').value = '';
    document.getElementById('articleImageUrl').value = '';
    document.getElementById('articleImageFile').value = '';
    document.getElementById('articleEditor').value = '';
}

async function loadGallery() {
    const response = await apiRequest('get_gallery');
    if (response.success) {
        galleryItems = response.gallery;
        renderGallery();
        renderGalleryAdminList();
    }
}

function renderGallery() {
    const grid = document.getElementById('galleryGrid');
    if (!grid) return;

    grid.innerHTML = galleryItems.map(item => `
        <div class="product-card">
            <div class="product-image" style="background-image:url('${item.image}'); background-size:cover; background-position:center;">
            </div>
            <div class="product-info">
                <h3>${item.title}</h3>
                <div class="product-meta">
                    <button class="btn btn-sm btn-primary" onclick="window.open('${item.image}','_blank')">عرض الصورة</button>
                </div>
            </div>
        </div>
    `).join('');
}

function renderGalleryAdminList() {
    const adminList = document.getElementById('galleryAdminList');
    if (!adminList) return;

    adminList.innerHTML = galleryItems.map(item => `
        <div style="padding:15px;border-bottom:1px solid rgba(255,255,255,.05);display:flex;justify-content:space-between;align-items:center;gap:15px;flex-wrap:wrap;">
            <div>
                <strong>${item.title}</strong>
                <p style="color:#999;margin-top:5px;">${item.image}</p>
            </div>
            <div style="display:flex;gap:10px;">
                <button class="btn btn-outline btn-sm" onclick="editGallery(${item.id})">تعديل</button>
                <button class="btn btn-danger btn-sm" onclick="deleteGallery(${item.id})">حذف</button>
            </div>
        </div>
    `).join('');
}

function openGalleryModal() {
    editingGalleryId = null;
    document.getElementById('galleryModalTitle').textContent = 'إضافة صورة جديدة';
    document.getElementById('galleryTitle').value = '';
    document.getElementById('galleryImageUrl').value = '';
    document.getElementById('galleryImageFile').value = '';
    document.getElementById('galleryModal').classList.add('active');
}

function closeGalleryModal() {
    document.getElementById('galleryModal').classList.remove('active');
}

async function editGallery(id) {
    const item = galleryItems.find(image => image.id === id);
    if (!item) return;
    editingGalleryId = id;
    document.getElementById('galleryModalTitle').textContent = 'تعديل صورة المعرض';
    document.getElementById('galleryTitle').value = item.title;
    document.getElementById('galleryImageUrl').value = item.image;
    document.getElementById('galleryImageFile').value = '';
    document.getElementById('galleryModal').classList.add('active');
}

async function saveGalleryImage() {
    const title = document.getElementById('galleryTitle').value.trim();
    const imageUrl = document.getElementById('galleryImageUrl').value.trim();
    const imageFile = document.getElementById('galleryImageFile').files[0];

    if (!title || (!imageUrl && !imageFile)) {
        showToast('يرجى إضافة عنوان وصورة', 'error');
        return;
    }

    const form = new FormData();
    form.append('id', editingGalleryId || '');
    form.append('title', title);
    form.append('image_url', imageUrl);
    if (imageFile) {
        form.append('image', imageFile);
    }

    const response = await apiRequest('save_gallery', form);
    if (response.success) {
        closeGalleryModal();
        await loadGallery();
        showToast(response.message, 'success');
    } else {
        showToast(response.message || 'فشل حفظ صورة المعرض', 'error');
    }
}

async function deleteGallery(id) {
    if (!confirm('هل تريد حذف هذه الصورة؟')) return;
    const response = await apiRequest('delete_gallery', { id });
    if (response.success) {
        await loadGallery();
        showToast(response.message, 'success');
    } else {
        showToast(response.message || 'فشل حذف الصورة', 'error');
    }
}

function formatText(type){
    const textarea = document.getElementById('articleEditor');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selected = textarea.value.substring(start,end);

    let formatted = selected;

    switch(type){
        case 'h2':
            formatted = `<h2>${selected || 'عنوان رئيسي'}</h2>`;
            break;
        case 'h3':
            formatted = `<h3>${selected || 'عنوان فرعي'}</h3>`;
            break;
        case 'bold':
            formatted = `<b>${selected || 'نص عريض'}</b>`;
            break;
        case 'italic':
            formatted = `<i>${selected || 'نص مائل'}</i>`;
            break;
        case 'ul':
            formatted = `<ul><li>${selected || 'عنصر قائمة'}</li></ul>`;
            break;
    }

    textarea.setRangeText(formatted,start,end,'end');
}

