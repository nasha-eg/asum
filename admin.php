<?php
session_start();
$adminLogged = !empty($_SESSION['admin_id']);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم فحم العاصمة</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script defer src="assets/js/app.js"></script>
</head>
<body>
    <!-- ================= ADMIN LOGIN ================= -->
    <div class="admin-login<?php if (!$adminLogged) echo ' active'; ?>" id="adminLogin">
        <div class="login-box">
            <div class="logo-icon"><i class="fas fa-fire"></i></div>
            <h2>تسجيل الدخول</h2>
            <p>لوحة تحكم مصنع فحم العاصمة</p>
            <form onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label>اسم المستخدم</label>
                    <input type="text" id="loginUsername" required placeholder="أدخل اسم المستخدم">
                </div>
                <div class="form-group">
                    <label>كلمة المرور</label>
                    <input type="password" id="loginPassword" required placeholder="أدخل كلمة المرور">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> تسجيل الدخول
                </button>
            </form>
            <div class="back-to-site" onclick="showPublicSite()">
                <i class="fas fa-arrow-right"></i> العودة إلى الموقع
            </div>
        </div>
    </div>

    <!-- ================= ADMIN PANEL ================= -->
    <div class="admin-panel<?php if ($adminLogged) echo ' active'; ?>" id="adminPanel">

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon"><i class="fas fa-fire"></i></div>
                    <div class="logo-text">فحم <span>العاصمة</span></div>
                </div>
            </div>
            <div class="sidebar-user">
                <div class="user-avatar">أ</div>
                <div class="user-info">
                    <h4>المدير العام</h4>
                    <span>مدير النظام</span>
                </div>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a class="active" onclick="showAdminPage('dashboard', this)">
                        <i class="fas fa-th-large"></i> لوحة التحكم
                    </a></li>
                    <li><a onclick="showAdminPage('products', this)">
                        <i class="fas fa-box-open"></i> المنتجات
                    </a></li>
                    <li><a onclick="showAdminPage('orders', this)">
                        <i class="fas fa-shopping-cart"></i> الطلبات
                        <span style="margin-right:auto; background:var(--danger); color:white; padding:2px 8px; border-radius:50px; font-size:11px;">3</span>
                    </a></li>
                    <li><a onclick="showAdminPage('calls', this)">
                        <i class="fas fa-phone-alt"></i> سجل المكالمات
                        <span style="margin-right:auto; background:var(--warning); color:var(--primary); padding:2px 8px; border-radius:50px; font-size:11px;">8</span>
                    </a></li>
                    <li><a onclick="showAdminPage('customers', this)">
                        <i class="fas fa-users"></i> العملاء
                    </a></li>
                    <li><a onclick="showAdminPage('articles', this)">
                        <i class="fas fa-newspaper"></i> المقالات
                    </a></li>
                    <li><a onclick="showAdminPage('gallery', this)">
                        <i class="fas fa-images"></i> معرض الصور
                    </a></li>
                    <li><a onclick="showAdminPage('analytics', this)">
                        <i class="fas fa-chart-line"></i> التحليلات
                    </a></li>
                    <li><a onclick="showAdminPage('settings', this)">
                        <i class="fas fa-cog"></i> الإعدادات
                    </a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <button class="btn btn-outline" onclick="logout()">
                    <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="البحث في لوحة التحكم...">
                </div>
                <div class="header-actions">
                    <div class="header-icon" onclick="showPublicSite()" title="عرض الموقع">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="header-icon" title="الإشعارات">
                        <i class="fas fa-bell"></i>
                        <span class="badge">8</span>
                    </div>
                    <div class="header-icon" title="المكالمات">
                        <i class="fas fa-phone-alt"></i>
                        <span class="badge">5</span>
                    </div>
                </div>
            </header>

            
            <!-- Articles Page -->
            <div class="admin-page" id="page-articles">
                <div class="dashboard-content">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:20px;flex-wrap:wrap;">
                        <div>
                            <h1 class="page-title">إدارة المقالات</h1>
                            <p class="page-subtitle">إنشاء وتعديل المقالات الخاصة بالموقع</p>
                        </div>
                        <button class="btn btn-primary" onclick="addArticle()">
                            <i class="fas fa-plus"></i> مقال جديد
                        </button>
                    </div>

                    <div class="card" style="margin-top:25px;">
                        <div class="card-header">
                            <h3>محرر المقالات HTML</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>عنوان المقال</label>
                                <input type="text" id="articleTitle" placeholder="اكتب عنوان المقال">
                            </div>

                            <div style="display:flex;gap:10px;flex-wrap:wrap;margin:20px 0;">
                                <button class="btn btn-outline btn-sm" onclick="formatText('h2')">عنوان H2</button>
                                <button class="btn btn-outline btn-sm" onclick="formatText('h3')">عنوان H3</button>
                                <button class="btn btn-outline btn-sm" onclick="formatText('bold')">عريض</button>
                                <button class="btn btn-outline btn-sm" onclick="formatText('italic')">مائل</button>
                                <button class="btn btn-outline btn-sm" onclick="formatText('ul')">قائمة</button>
                            </div>

                            <div class="form-group">
                                <label>رابط صورة الغلاف (URL)</label>
                                <input type="text" id="articleImageUrl" placeholder="https://example.com/article-cover.jpg">
                            </div>
                            <div class="form-group">
                                <label>رفع صورة الغلاف</label>
                                <input type="file" id="articleImageFile" accept="image/*">
                            </div>
                            <textarea id="articleEditor" style="width:100%;height:300px;background:#111;color:#fff;border:1px solid rgba(201,168,76,.2);border-radius:12px;padding:20px;font-size:15px;" placeholder="اكتب محتوى المقال HTML هنا"></textarea>

                            <div style="display:flex;gap:15px;margin-top:20px;flex-wrap:wrap;">
                                <button class="btn btn-success" onclick="saveArticle()">
                                    <i class="fas fa-save"></i> حفظ المقال
                                </button>
                                <button class="btn btn-outline" onclick="previewArticle()">
                                    <i class="fas fa-eye"></i> معاينة
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card" style="margin-top:25px;">
                        <div class="card-header">
                            <h3>كل المقالات</h3>
                        </div>
                        <div class="card-body">
                            <div id="articlesAdminList"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gallery Page -->
            <div class="admin-page" id="page-gallery">
                <div class="dashboard-content">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:20px;flex-wrap:wrap;">
                        <div>
                            <h1 class="page-title">إدارة معرض الصور</h1>
                            <p class="page-subtitle">أضف وحذف وتحرير صور المعرض بكل سهولة</p>
                        </div>
                        <button class="btn btn-primary" onclick="openGalleryModal()">
                            <i class="fas fa-plus"></i> صورة جديدة
                        </button>
                    </div>

                    <div class="card" style="margin-top:25px;">
                        <div class="card-header">
                            <h3>صور المعرض</h3>
                        </div>
                        <div class="card-body">
                            <div id="galleryAdminList"></div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Dashboard Page -->
            <div class="admin-page active" id="page-dashboard">
                <div class="dashboard-content">
                    <h1 class="page-title">لوحة التحكم</h1>
                    <p class="page-subtitle">نظرة عامة على أداء مصنع فحم العاصمة</p>

                    <div class="stats-row">
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <h4>إجمالي المبيعات</h4>
                                <div class="stat-card-icon gold"><i class="fas fa-shekel-sign"></i></div>
                            </div>
                            <div class="stat-card-value">125,430 ج.م</div>
                            <div class="stat-card-change positive">
                                <i class="fas fa-arrow-up"></i> +12.5% عن الشهر الماضي
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <h4>الطلبات الجديدة</h4>
                                <div class="stat-card-icon blue"><i class="fas fa-shopping-bag"></i></div>
                            </div>
                            <div class="stat-card-value">48</div>
                            <div class="stat-card-change positive">
                                <i class="fas fa-arrow-up"></i> +8.2% عن الشهر الماضي
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <h4>المكالمات الواردة</h4>
                                <div class="stat-card-icon green"><i class="fas fa-phone-alt"></i></div>
                            </div>
                            <div class="stat-card-value">156</div>
                            <div class="stat-card-change positive">
                                <i class="fas fa-arrow-up"></i> +25% عن الشهر الماضي
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <h4>رسائل واتساب</h4>
                                <div class="stat-card-icon red"><i class="fab fa-whatsapp"></i></div>
                            </div>
                            <div class="stat-card-value">89</div>
                            <div class="stat-card-change positive">
                                <i class="fas fa-arrow-up"></i> +18% عن الشهر الماضي
                            </div>
                        </div>
                    </div>

                    <div class="cards-row">
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-phone-alt" style="color:var(--accent); margin-left:8px;"></i> آخر المكالمات والطلبات</h3>
                                <button class="btn btn-sm btn-outline" onclick="showAdminPage('calls')">عرض الكل</button>
                            </div>
                            <div class="card-body" style="padding:0;">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>النوع</th>
                                            <th>العميل</th>
                                            <th>الهاتف</th>
                                            <th>الطلب/الموضوع</th>
                                            <th>الحالة</th>
                                            <th>التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span style="color:var(--whatsapp)"><i class="fab fa-whatsapp"></i> واتساب</span></td>
                                            <td>أحمد محمد</td>
                                            <td dir="ltr">01012345678</td>
                                            <td>طلب 50 كجم فحم جوافة</td>
                                            <td><span class="status-badge status-pending">جديد</span></td>
                                            <td>منذ 5 دقائق</td>
                                        </tr>
                                        <tr>
                                            <td><span style="color:var(--phone)"><i class="fas fa-phone-alt"></i> اتصال</span></td>
                                            <td>محمد علي</td>
                                            <td dir="ltr">01098765432</td>
                                            <td>استفسار عن أسعار الجملة</td>
                                            <td><span class="status-badge status-active">تم الرد</span></td>
                                            <td>منذ 30 دقيقة</td>
                                        </tr>
                                        <tr>
                                            <td><span style="color:var(--whatsapp)"><i class="fab fa-whatsapp"></i> واتساب</span></td>
                                            <td>خالد محمود</td>
                                            <td dir="ltr">01122334455</td>
                                            <td>طلب تصدير 5 أطنان</td>
                                            <td><span class="status-badge status-pending">جديد</span></td>
                                            <td>منذ ساعة</td>
                                        </tr>
                                        <tr>
                                            <td><span style="color:var(--phone)"><i class="fas fa-phone-alt"></i> اتصال</span></td>
                                            <td>سامي عبدالله</td>
                                            <td dir="ltr">01234567890</td>
                                            <td>طلب 80 كجم فحم زيتون</td>
                                            <td><span class="status-badge status-active">تم الرد</span></td>
                                            <td>منذ 3 ساعات</td>
                                        </tr>
                                        <tr>
                                            <td><span style="color:var(--whatsapp)"><i class="fab fa-whatsapp"></i> واتساب</span></td>
                                            <td>عمر حسن</td>
                                            <td dir="ltr">01555667788</td>
                                            <td>استفسار عن مواعيد التوصيل</td>
                                            <td><span class="status-badge status-active">تم الرد</span></td>
                                            <td>أمس</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-clock" style="color:var(--accent); margin-left:8px;"></i> النشاط الأخير</h3>
                            </div>
                            <div class="card-body">
                                <ul class="activity-list">
                                    <li class="activity-item">
                                        <div class="activity-icon" style="background: rgba(37,211,102,0.15); color: var(--whatsapp);"><i class="fab fa-whatsapp"></i></div>
                                        <div class="activity-content">
                                            <h4>رسالة واتساب جديدة</h4>
                                            <p>أحمد محمد - طلب 50 كجم فحم جوافة</p>
                                        </div>
                                        <span class="activity-time">منذ 5 دقائق</span>
                                    </li>
                                    <li class="activity-item">
                                        <div class="activity-icon" style="background: rgba(52,183,241,0.15); color: var(--phone);"><i class="fas fa-phone-alt"></i></div>
                                        <div class="activity-content">
                                            <h4>مكالمة واردة</h4>
                                            <p>محمد علي - استفسار عن الأسعار</p>
                                        </div>
                                        <span class="activity-time">منذ 30 دقيقة</span>
                                    </li>
                                    <li class="activity-item">
                                        <div class="activity-icon green"><i class="fas fa-check-circle"></i></div>
                                        <div class="activity-content">
                                            <h4>تم تسليم طلب #ORD-2019</h4>
                                            <p>محمد علي - فحم برتقال ممتاز</p>
                                        </div>
                                        <span class="activity-time">منذ ساعة</span>
                                    </li>
                                    <li class="activity-item">
                                        <div class="activity-icon" style="background: rgba(37,211,102,0.15); color: var(--whatsapp);"><i class="fab fa-whatsapp"></i></div>
                                        <div class="activity-content">
                                            <h4>رسالة واتساب</h4>
                                            <p>خالد محمود - طلب تصدير 5 أطنان</p>
                                        </div>
                                        <span class="activity-time">منذ 3 ساعات</span>
                                    </li>
                                    <li class="activity-item">
                                        <div class="activity-icon gold"><i class="fas fa-star"></i></div>
                                        <div class="activity-content">
                                            <h4>تقييم جديد 5 نجوم</h4>
                                            <p>من سامي عبدالله على فحم الزيتون</p>
                                        </div>
                                        <span class="activity-time">منذ 5 ساعات</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-chart-bar" style="color:var(--accent); margin-left:8px;"></i> إحصائيات المكالمات والمبيعات</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-placeholder">
                                <i class="fas fa-chart-area"></i>
                                <p>رسم بياني للمكالمات والمبيعات الشهرية</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Page -->
            <div class="admin-page" id="page-products">
                <div class="dashboard-content">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
                        <div>
                            <h1 class="page-title">إدارة المنتجات</h1>
                            <p class="page-subtitle">إضافة، تعديل وحذف منتجات فحم العاصمة</p>
                        </div>
                        <button class="btn btn-primary" onclick="openProductModal()">
                            <i class="fas fa-plus"></i> إضافة منتج جديد
                        </button>
                    </div>
                    <div class="card">
                        <div class="card-body" style="padding:0;">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">#</th>
                                        <th>المنتج</th>
                                        <th>النوع</th>
                                        <th>السعر (كجم)</th>
                                        <th>المخزون</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody id="productsTableBody">
                                    <!-- Filled by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="pagination">
                        <button><i class="fas fa-chevron-right"></i></button>
                        <button class="active">1</button>
                        <button>2</button>
                        <button>3</button>
                        <button><i class="fas fa-chevron-left"></i></button>
                    </div>
                </div>
            </div>

            <!-- Orders Page -->
            <div class="admin-page" id="page-orders">
                <div class="dashboard-content">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
                        <div>
                            <h1 class="page-title">إدارة الطلبات</h1>
                            <p class="page-subtitle">متابعة وإدارة طلبات العملاء الواردة عبر الهاتف وواتساب</p>
                        </div>
                        <div style="display:flex; gap:10px;">
                            <select style="padding:10px 15px; background:var(--secondary); border:2px solid rgba(201,168,76,0.1); border-radius:10px; color:var(--text); font-family:'Tajawal',sans-serif;">
                                <option>جميع الحالات</option>
                                <option>جديد</option>
                                <option>تم الرد</option>
                                <option>قيد التنفيذ</option>
                                <option>مكتمل</option>
                            </select>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" style="padding:0;">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>رقم الطلب</th>
                                        <th>العميل</th>
                                        <th>الهاتف</th>
                                        <th>طريقة التواصل</th>
                                        <th>المنتجات</th>
                                        <th>الإجمالي</th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>#ORD-2024</strong></td>
                                        <td>أحمد محمد</td>
                                        <td dir="ltr">01012345678</td>
                                        <td><span style="color:var(--whatsapp)"><i class="fab fa-whatsapp"></i> واتساب</span></td>
                                        <td>فحم جوافة × 50 كجم</td>
                                        <td style="color:var(--accent); font-weight:700;">3,500 ج.م</td>
                                        <td><span class="status-badge status-pending">جديد</span></td>
                                        <td>2024-05-15</td>
                                        <td class="table-actions">
                                            <button class="btn-view" title="عرض"><i class="fas fa-eye"></i></button>
                                            <button class="btn-edit" title="تعديل"><i class="fas fa-edit"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>#ORD-2023</strong></td>
                                        <td>محمد علي</td>
                                        <td dir="ltr">01098765432</td>
                                        <td><span style="color:var(--phone)"><i class="fas fa-phone-alt"></i> اتصال</span></td>
                                        <td>فحم برتقال × 100 كجم</td>
                                        <td style="color:var(--accent); font-weight:700;">6,000 ج.م</td>
                                        <td><span class="status-badge status-active">مكتمل</span></td>
                                        <td>2024-05-14</td>
                                        <td class="table-actions">
                                            <button class="btn-view" title="عرض"><i class="fas fa-eye"></i></button>
                                            <button class="btn-edit" title="تعديل"><i class="fas fa-edit"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>#ORD-2022</strong></td>
                                        <td>خالد محمود</td>
                                        <td dir="ltr">01122334455</td>
                                        <td><span style="color:var(--whatsapp)"><i class="fab fa-whatsapp"></i> واتساب</span></td>
                                        <td>فحم ليمون × 30 كجم</td>
                                        <td style="color:var(--accent); font-weight:700;">1,800 ج.م</td>
                                        <td><span class="status-badge status-active">مكتمل</span></td>
                                        <td>2024-05-13</td>
                                        <td class="table-actions">
                                            <button class="btn-view" title="عرض"><i class="fas fa-eye"></i></button>
                                            <button class="btn-edit" title="تعديل"><i class="fas fa-edit"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>#ORD-2021</strong></td>
                                        <td>سامي عبدالله</td>
                                        <td dir="ltr">01234567890</td>
                                        <td><span style="color:var(--phone)"><i class="fas fa-phone-alt"></i> اتصال</span></td>
                                        <td>فحم زيتون × 80 كجم</td>
                                        <td style="color:var(--accent); font-weight:700;">7,200 ج.م</td>
                                        <td><span class="status-badge status-pending">جديد</span></td>
                                        <td>2024-05-12</td>
                                        <td class="table-actions">
                                            <button class="btn-view" title="عرض"><i class="fas fa-eye"></i></button>
                                            <button class="btn-edit" title="تعديل"><i class="fas fa-edit"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calls/Contacts Page -->
            <div class="admin-page" id="page-calls">
                <div class="dashboard-content">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
                        <div>
                            <h1 class="page-title">سجل المكالمات والتواصل</h1>
                            <p class="page-subtitle">متابعة جميع المكالمات ورسائل الواتساب الواردة</p>
                        </div>
                        <div style="display:flex; gap:10px;">
                            <button class="btn btn-whatsapp" onclick="showToast('فتح واتساب ويب', 'success')">
                                <i class="fab fa-whatsapp"></i> فتح واتساب
                            </button>
                        </div>
                    </div>

                    <div class="stats-row" style="margin-bottom:25px;">
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <h4>مكالمات اليوم</h4>
                                <div class="stat-card-icon" style="background:rgba(52,183,241,0.15); color:var(--phone);"><i class="fas fa-phone-alt"></i></div>
                            </div>
                            <div class="stat-card-value">12</div>
                            <div class="stat-card-change positive">
                                <i class="fas fa-arrow-up"></i> 3 جديدة
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <h4>رسائل واتساب اليوم</h4>
                                <div class="stat-card-icon" style="background:rgba(37,211,102,0.15); color:var(--whatsapp);"><i class="fab fa-whatsapp"></i></div>
                            </div>
                            <div class="stat-card-value">8</div>
                            <div class="stat-card-change positive">
                                <i class="fas fa-arrow-up"></i> 2 جديدة
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <h4>إجمالي المكالمات (شهر)</h4>
                                <div class="stat-card-icon blue"><i class="fas fa-chart-line"></i></div>
                            </div>
                            <div class="stat-card-value">156</div>
                            <div class="stat-card-change positive">
                                <i class="fas fa-arrow-up"></i> +25% عن الماضي
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <h4>معدل الرد</h4>
                                <div class="stat-card-icon green"><i class="fas fa-check-circle"></i></div>
                            </div>
                            <div class="stat-card-value">94%</div>
                            <div class="stat-card-change positive">
                                <i class="fas fa-arrow-up"></i> +5% تحسن
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-list-alt" style="color:var(--accent); margin-left:8px;"></i> سجل التواصل</h3>
                            <div style="display:flex; gap:10px;">
                                <select style="padding:8px 12px; background:var(--primary); border:2px solid rgba(201,168,76,0.1); border-radius:8px; color:var(--text); font-family:'Tajawal',sans-serif; font-size:13px;">
                                    <option>الكل</option>
                                    <option>واتساب</option>
                                    <option>اتصال</option>
                                </select>
                                <select style="padding:8px 12px; background:var(--primary); border:2px solid rgba(201,168,76,0.1); border-radius:8px; color:var(--text); font-family:'Tajawal',sans-serif; font-size:13px;">
                                    <option>جميع الحالات</option>
                                    <option>جديد</option>
                                    <option>تم الرد</option>
                                    <option>تم التحويل لطلب</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body" style="padding:0;">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>النوع</th>
                                        <th>العميل</th>
                                        <th>الهاتف</th>
                                        <th>الموضوع</th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody id="callsTableBody">
                                    <!-- Filled by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customers Page -->
            <div class="admin-page" id="page-customers">
                <div class="dashboard-content">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
                        <div>
                            <h1 class="page-title">العملاء</h1>
                            <p class="page-subtitle">قاعدة بيانات عملاء مصنع فحم العاصمة</p>
                        </div>
                        <button class="btn btn-primary" onclick="showToast('سيتم إضافة هذه الميزة قريباً', 'warning')">
                            <i class="fas fa-plus"></i> إضافة عميل
                        </button>
                    </div>
                    <div class="card">
                        <div class="card-body" style="padding:0;">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>الاسم</th>
                                        <th>الهاتف</th>
                                        <th>طريقة التواصل المفضلة</th>
                                        <th>عدد الطلبات</th>
                                        <th>إجمالي المشتريات</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>أحمد محمد</strong></td>
                                        <td dir="ltr">01012345678</td>
                                        <td><span style="color:var(--whatsapp)"><i class="fab fa-whatsapp"></i> واتساب</span></td>
                                        <td>5</td>
                                        <td style="color:var(--accent); font-weight:700;">18,500 ج.م</td>
                                        <td><span class="status-badge status-active">نشط</span></td>
                                        <td class="table-actions">
                                            <button class="btn-view" title="عرض"><i class="fas fa-eye"></i></button>
                                            <button class="btn-edit" title="تعديل"><i class="fas fa-edit"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>محمد علي</strong></td>
                                        <td dir="ltr">01098765432</td>
                                        <td><span style="color:var(--phone)"><i class="fas fa-phone-alt"></i> اتصال</span></td>
                                        <td>8</td>
                                        <td style="color:var(--accent); font-weight:700;">32,000 ج.م</td>
                                        <td><span class="status-badge status-active">نشط</span></td>
                                        <td class="table-actions">
                                            <button class="btn-view" title="عرض"><i class="fas fa-eye"></i></button>
                                            <button class="btn-edit" title="تعديل"><i class="fas fa-edit"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>خالد محمود</strong></td>
                                        <td dir="ltr">01122334455</td>
                                        <td><span style="color:var(--whatsapp)"><i class="fab fa-whatsapp"></i> واتساب</span></td>
                                        <td>3</td>
                                        <td style="color:var(--accent); font-weight:700;">8,200 ج.م</td>
                                        <td><span class="status-badge status-active">نشط</span></td>
                                        <td class="table-actions">
                                            <button class="btn-view" title="عرض"><i class="fas fa-eye"></i></button>
                                            <button class="btn-edit" title="تعديل"><i class="fas fa-edit"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>سامي عبدالله</strong></td>
                                        <td dir="ltr">01234567890</td>
                                        <td><span style="color:var(--phone)"><i class="fas fa-phone-alt"></i> اتصال</span></td>
                                        <td>2</td>
                                        <td style="color:var(--accent); font-weight:700;">12,000 ج.م</td>
                                        <td><span class="status-badge status-pending">جديد</span></td>
                                        <td class="table-actions">
                                            <button class="btn-view" title="عرض"><i class="fas fa-eye"></i></button>
                                            <button class="btn-edit" title="تعديل"><i class="fas fa-edit"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Page -->
            <div class="admin-page" id="page-analytics">
                <div class="dashboard-content">
                    <h1 class="page-title">التحليلات والإحصائيات</h1>
                    <p class="page-subtitle">تحليل أداء المبيعات والتواصل</p>

                    <div class="stats-row">
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <h4>إجمالي الإيرادات (سنوي)</h4>
                                <div class="stat-card-icon gold"><i class="fas fa-shekel-sign"></i></div>
                            </div>
                            <div class="stat-card-value">1,450,000 ج.م</div>
                            <div class="stat-card-change positive">
                                <i class="fas fa-arrow-up"></i> +23% عن العام الماضي
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <h4>إجمالي الطلبات</h4>
                                <div class="stat-card-icon blue"><i class="fas fa-shopping-bag"></i></div>
                            </div>
                            <div class="stat-card-value">486</div>
                            <div class="stat-card-change positive">
                                <i class="fas fa-arrow-up"></i> +15% عن العام الماضي
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <h4>متوسط قيمة الطلب</h4>
                                <div class="stat-card-icon green"><i class="fas fa-chart-line"></i></div>
                            </div>
                            <div class="stat-card-value">2,984 ج.م</div>
                            <div class="stat-card-change positive">
                                <i class="fas fa-arrow-up"></i> +7% عن العام الماضي
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <h4>العملاء الجدد</h4>
                                <div class="stat-card-icon red"><i class="fas fa-users"></i></div>
                            </div>
                            <div class="stat-card-value">+42</div>
                            <div class="stat-card-change positive">
                                <i class="fas fa-arrow-up"></i> +18% عن العام الماضي
                            </div>
                        </div>
                    </div>

                    <div class="cards-row">
                        <div class="card">
                            <div class="card-header">
                                <h3>المبيعات الشهرية</h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-placeholder">
                                    <i class="fas fa-chart-bar"></i>
                                    <p>رسم بياني للمبيعات الشهرية</p>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3>توزيع قنوات التواصل</h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-placeholder">
                                    <i class="fas fa-chart-pie"></i>
                                    <p>واتساب 65% | اتصال 35%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Page -->
            <div class="admin-page" id="page-settings">
                <div class="dashboard-content">
                    <h1 class="page-title">إعدادات الموقع</h1>
                    <p class="page-subtitle">إدارة إعدادات مصنع فحم العاصمة</p>

                    <div class="settings-tabs">
                        <button class="settings-tab active" onclick="showSettingsTab('general', this)">عام</button>
                        <button class="settings-tab" onclick="showSettingsTab('contact', this)">اتصال</button>
                        <button class="settings-tab" onclick="showSettingsTab('appearance', this)">المظهر</button>
                        <button class="settings-tab" onclick="showSettingsTab('security', this)">الأمان</button>
                    </div>

                    <div class="settings-panel active" id="tab-general">
                        <div class="card">
                            <div class="card-header">
                                <h3>إعدادات عامة</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>اسم الموقع</label>
                                    <input type="text" id="siteName" value="فحم العاصمة">
                                </div>
                                <div class="form-group">
                                    <label>الشعار المميز</label>
                                    <input type="text" id="siteNameHighlight" value="العاصمة">
                                </div>
                                <div class="form-group">
                                    <label>الوصف</label>
                                    <textarea rows="3" id="siteDescription">أجود أنواع الفحم النباتي المصري - مصنع فحم العاصمة بدمياط الجديدة</textarea>
                                </div>
                                <div class="form-group">
                                    <label>شعار الصفحة الرئيسية</label>
                                    <input type="text" id="heroBadge" value="أفضل مصنع فحم في مصر">
                                </div>
                                <div class="form-group">
                                    <label>عنوان الصفحة الرئيسية</label>
                                    <input type="text" id="heroHeadline" value="فحم العاصمة النباتي">
                                </div>
                                <div class="form-group">
                                    <label>نص المقدمة</label>
                                    <textarea rows="3" id="heroText">أجود أنواع الفحم النباتي المصري بأعلى معايير الجودة العالمية. ننتج الفحم الطبيعي 100% من أجود أخشاب الأشجار المثمرة بخبرة تتجاوز 15 عاماً.</textarea>
                                </div>
                                <div class="form-group">
                                    <label>عنوان قسم من نحن</label>
                                    <input type="text" id="aboutSectionTitle" value="من نحن">
                                </div>
                                <div class="form-group">
                                    <label>الوصف في قسم من نحن</label>
                                    <input type="text" id="aboutSectionSubtitle" value="مصنع متخصص في إنتاج الفحم النباتي الطبيعي بأعلى معايير الجودة">
                                </div>
                                <div class="form-group">
                                    <label>عنوان قسم الخبرة</label>
                                    <input type="text" id="aboutTitle" value="خبرة 15 عاماً في صناعة الفحم">
                                </div>
                                <div class="form-group">
                                    <label>نص القسم الأول</label>
                                    <textarea rows="3" id="aboutText">مصنع فحم العاصمة يقع في قلب المنطقة الصناعية بدمياط الجديدة، مصر. نحن متخصصون في إنتاج الفحم النباتي الطبيعي 100% من أجود أنواع الأخشاب المثمرة مثل الجوافة والبرتقال والليمون والزيتون.</textarea>
                                </div>
                                <div class="form-group">
                                    <label>نص القسم الثاني</label>
                                    <textarea rows="3" id="aboutText2">نستخدم أحدث التقنيات في عمليات التقطير والتفحيم لضمان منتج نظيف خالٍ من الشوائب والمواد الكيميائية، مما يجعل فحمنا الخيار الأمثل للمطاعم والمقاهي والاستخدام المنزلي.</textarea>
                                </div>
                                <div class="form-group">
                                    <label>عنوان قسم المنتجات</label>
                                    <input type="text" id="productsSectionTitle" value="منتجات فحم العاصمة">
                                </div>
                                <div class="form-group">
                                    <label>وصف قسم المنتجات</label>
                                    <textarea rows="3" id="productsSectionDescription">نقدم مجموعة متنوعة من أجود أنواع الفحم النباتي لتلبية جميع احتياجاتك</textarea>
                                </div>
                                <div class="form-group">
                                    <label>عنوان قسم المعرض</label>
                                    <input type="text" id="gallerySectionTitle" value="معرض الصور">
                                </div>
                                <div class="form-group">
                                    <label>وصف قسم المعرض</label>
                                    <textarea rows="3" id="gallerySectionDescription">صور من خط الإنتاج، التعبئة والشحن داخل مصنع الفحم</textarea>
                                </div>
                                <div class="form-group">
                                    <label>عنوان قسم المقالات</label>
                                    <input type="text" id="articlesSectionTitle" value="أحدث المقالات">
                                </div>
                                <div class="form-group">
                                    <label>وصف قسم المقالات</label>
                                    <textarea rows="3" id="articlesSectionDescription">تعرف على أفضل النصائح والمعلومات عن الفحم النباتي والتصدير</textarea>
                                </div>
                                <div class="form-group">
                                    <label>وصف الفوتر</label>
                                    <textarea rows="3" id="footerDescription">مصنع فحم العاصمة هو أحد أكبر مصانع الفحم النباتي في مصر. نحن نلتزم بتقديم منتجات عالية الجودة تلبي احتياجات عملائنا في السوق المحلي والدولي.</textarea>
                                </div>
                                <div class="form-group">
                                    <label>أيقونة الشعار</label>
                                    <div class="image-upload">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p>اسحب الصورة هنا أو انقر للاختيار</p>
                                    </div>
                                </div>
                                <div style="text-align:left; margin-top:20px;">
                                    <button class="btn btn-primary" onclick="saveSettings()" type="button">
                                        <i class="fas fa-save"></i> حفظ التغييرات
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="settings-panel" id="tab-contact">
                        <div class="card">
                            <div class="card-header">
                                <h3>معلومات الاتصال</h3>
                            </div>
                            <div class="card-body">
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                                    <div class="form-group">
                                        <label>العنوان</label>
                                        <input type="text" id="contactAddress" value="مصر - دمياط الجديدة - المنطقة الصناعية">
                                    </div>
                                    <div class="form-group">
                                        <label>رقم الهاتف</label>
                                        <input type="text" id="contactPhone" value="+20 123 456 7890">
                                    </div>
                                    <div class="form-group">
                                        <label>رقم واتساب</label>
                                        <input type="text" id="contactWhatsApp" value="+20 123 456 7890">
                                    </div>
                                    <div class="form-group">
                                        <label>رابط واتساب مباشر</label>
                                        <input type="text" id="contactWhatsAppUrl" value="https://wa.me/201234567890">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top:15px;">
                                    <label>رسالة واتساب افتراضية</label>
                                    <input type="text" id="orderMessageTemplate" value="مرحباً، أرغب في الاستفسار عن منتجات فحم العاصمة">
                                </div>
                                <div class="form-group">
                                    <label>ساعات العمل</label>
                                    <input type="text" id="contactWorkHours" value="السبت - الخميس: 8:00 ص - 6:00 م">
                                </div>
                                <div style="text-align:left; margin-top:20px;">
                                    <button class="btn btn-primary" onclick="saveSettings()" type="button">
                                        <i class="fas fa-save"></i> حفظ التغييرات
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="settings-panel" id="tab-appearance">
                        <div class="card">
                            <div class="card-header">
                                <h3>إعدادات المظهر</h3>
                            </div>
                            <div class="card-body">
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                                    <div class="form-group">
                                        <label>لون التمييز</label>
                                        <input type="color" id="themeAccent" value="#c9a84c" style="height:50px; padding:5px;">
                                    </div>
                                    <div class="form-group">
                                        <label>لون واتساب</label>
                                        <input type="color" id="themeWhatsapp" value="#25D366" style="height:50px; padding:5px;">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top:15px;">
                                    <label>صورة الخلفية الرئيسية</label>
                                    <div class="image-upload">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p>اسحب الصورة هنا أو انقر للاختيار</p>
                                    </div>
                                </div>
                                <div style="text-align:left; margin-top:20px;">
                                    <button class="btn btn-primary" onclick="saveSettings()" type="button">
                                        <i class="fas fa-save"></i> حفظ التغييرات
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="settings-panel" id="tab-security">
                        <div class="card">
                            <div class="card-header">
                                <h3>الأمان</h3>
                            </div>
                            <div class="card-body">
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                                    <div class="form-group">
                                        <label>اسم المستخدم</label>
                                        <input type="text" value="admin">
                                    </div>
                                    <div class="form-group">
                                        <label>كلمة المرور الحالية</label>
                                        <input type="password" placeholder="أدخل كلمة المرور الحالية">
                                    </div>
                                    <div class="form-group">
                                        <label>كلمة المرور الجديدة</label>
                                        <input type="password" placeholder="أدخل كلمة المرور الجديدة">
                                    </div>
                                    <div class="form-group">
                                        <label>تأكيد كلمة المرور</label>
                                        <input type="password" placeholder="أعد إدخال كلمة المرور الجديدة">
                                    </div>
                                </div>
                                <div style="text-align:left; margin-top:20px;">
                                    <button class="btn btn-primary" onclick="showToast('تم تحديث كلمة المرور بنجاح', 'success')">
                                        <i class="fas fa-lock"></i> تحديث كلمة المرور
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Product Modal -->
    <div class="modal-overlay" id="productModal">
        <div class="modal">
            <div class="modal-header">
                <h3 id="modalTitle">إضافة منتج جديد</h3>
                <button class="modal-close" onclick="closeProductModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label>اسم المنتج *</label>
                        <input type="text" id="productName" placeholder="أدخل اسم المنتج">
                    </div>
                    <div class="form-group">
                        <label>النوع *</label>
                        <select id="productType">
                            <option value="">اختر النوع</option>
                            <option value="guava">فحم جوافة</option>
                            <option value="orange">فحم برتقال</option>
                            <option value="lemon">فحم ليمون</option>
                            <option value="olive">فحم زيتون</option>
                            <option value="mixed">فحم مشكل</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>السعر للكيلو (ج.م) *</label>
                        <input type="number" id="productPrice" placeholder="أدخل السعر">
                    </div>
                    <div class="form-group">
                        <label>المخزون المتاح (كجم) *</label>
                        <input type="number" id="productStock" placeholder="أدخل الكمية">
                    </div>
                </div>
                <div class="form-group" style="margin-top:15px;">
                    <label>وصف المنتج</label>
                    <textarea id="productDesc" rows="3" placeholder="أدخل وصف المنتج"></textarea>
                </div>
                <div class="form-group">
                    <label>صورة المنتج</label>
                    <div class="image-upload">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>اسحب الصورة هنا أو انقر للاختيار</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeProductModal()">إلغاء</button>
                <button class="btn btn-primary" onclick="saveProduct()">
                    <i class="fas fa-save"></i> حفظ المنتج
                </button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="galleryModal">
        <div class="modal">
            <div class="modal-header">
                <h3 id="galleryModalTitle">إضافة صورة جديدة</h3>
                <button class="modal-close" onclick="closeGalleryModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>عنوان الصورة *</label>
                    <input type="text" id="galleryTitle" placeholder="أدخل عنوان الصورة">
                </div>
                <div class="form-group">
                    <label>رابط الصورة (URL)</label>
                    <input type="text" id="galleryImageUrl" placeholder="https://example.com/image.jpg">
                </div>
                <div class="form-group">
                    <label>رفع صورة من الجهاز</label>
                    <input type="file" id="galleryImageFile" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeGalleryModal()">إلغاء</button>
                <button class="btn btn-primary" onclick="saveGalleryImage()">
                    <i class="fas fa-save"></i> حفظ الصورة
                </button>
            </div>
        </div>
    </div>

    

</body>
</html>
