<?php
require_once __DIR__ . '/db.php';

function loadSiteSettings()
{
    $pdo = getPDO();
    $settings = [];

    try {
        $stmt = $pdo->query('SELECT setting_key, setting_value FROM site_settings');
        foreach ($stmt->fetchAll() as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    } catch (PDOException $e) {
        // إذا لم توجد الجداول بعد، نستخدم القيم الافتراضية فقط
    }

    return array_merge([
        'site_title' => 'فحم العاصمة | مصنع الفحم النباتي - دمياط الجديدة',
        'site_name' => 'فحم العاصمة',
        'site_name_highlight' => 'العاصمة',
        'site_tagline' => 'مصنع الفحم النباتي الأفضل في دمياط الجديدة',
        'hero_badge' => 'أفضل مصنع فحم في مصر',
        'hero_headline' => 'فحم العاصمة النباتي',
        'hero_text' => 'أجود أنواع الفحم النباتي المصري بأعلى معايير الجودة العالمية. ننتج الفحم الطبيعي 100% من أجود أخشاب الأشجار المثمرة بخبرة تتجاوز 15 عاماً.',
        'hero_whatsapp_message' => 'مرحباً، أود الاستفسار عن منتجات فحم العاصمة',
        'contact_whatsapp_display' => '+20 123 456 7890',
        'contact_phone_display' => '+20 123 456 7890',
        'contact_whatsapp_url' => 'https://wa.me/201234567890',
        'theme_accent' => '#c9a84c',
        'theme_whatsapp' => '#25D366',
        'about_section_title' => 'من نحن',
        'about_section_subtitle' => 'مصنع متخصص في إنتاج الفحم النباتي الطبيعي بأعلى معايير الجودة',
        'about_title' => 'خبرة 15 عاماً في صناعة الفحم',
        'about_text' => 'مصنع فحم العاصمة يقع في قلب المنطقة الصناعية بدمياط الجديدة، مصر. نحن متخصصون في إنتاج الفحم النباتي الطبيعي 100% من أجود أنواع الأخشاب المثمرة مثل الجوافة والبرتقال والليمون والزيتون.',
        'about_text_2' => 'نستخدم أحدث التقنيات في عمليات التقطير والتفحيم لضمان منتج نظيف خالٍ من الشوائب والمواد الكيميائية، مما يجعل فحمنا الخيار الأمثل للمطاعم والمقاهي والاستخدام المنزلي.',
        'products_section_title' => 'منتجات فحم العاصمة',
        'products_section_description' => 'نقدم مجموعة متنوعة من أجود أنواع الفحم النباتي لتلبية جميع احتياجاتك',
        'gallery_section_title' => 'معرض الصور',
        'gallery_section_description' => 'صور من خط الإنتاج، التعبئة والشحن داخل مصنع الفحم',
        'articles_section_title' => 'أحدث المقالات',
        'articles_section_description' => 'تعرف على أفضل النصائح والمعلومات عن الفحم النباتي والتصدير',
        'contact_section_title' => 'تواصل معنا',
        'contact_section_description' => 'تواصل معنا مباشرة عبر الهاتف أو واتساب للطلب والاستفسار',
        'contact_address' => "مصر - دمياط الجديدة\nالمنطقة الصناعية - مصنع فحم العاصمة",
        'contact_work_hours' => "السبت - الخميس: 8:00 ص - 6:00 م\nالجمعة: مغلق",
        'footer_description' => 'مصنع فحم العاصمة هو أحد أكبر مصانع الفحم النباتي في مصر. نحن نلتزم بتقديم منتجات عالية الجودة تلبي احتياجات عملائنا في السوق المحلي والدولي.',
    ], $settings);
}

$siteSettings = loadSiteSettings();

function siteSetting(string $key, string $fallback = ''): string
{
    global $siteSettings;
    return htmlspecialchars($siteSettings[$key] ?? $fallback, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function siteSettingText(string $key, string $fallback = ''): string
{
    global $siteSettings;
    return nl2br(htmlspecialchars($siteSettings[$key] ?? $fallback, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
}

function formatPhoneLink(string $phone): string
{
    $digits = preg_replace('/[^0-9]/', '', $phone);
    return $digits ? 'tel:+' . $digits : 'tel:+201234567890';
}

function formatWhatsAppLink(string $phone, string $message = ''): string
{
    $digits = preg_replace('/[^0-9]/', '', $phone);
    if (!$digits) {
        $digits = '201234567890';
    }
    $url = 'https://wa.me/' . $digits;
    if ($message) {
        $url .= '?text=' . rawurlencode($message);
    }
    return $url;
}

$siteTitle = siteSetting('site_title');
$siteName = siteSetting('site_name');
$siteNameHighlight = siteSetting('site_name_highlight');
$heroBadge = siteSetting('hero_badge');
$heroHeadline = siteSetting('hero_headline');
$heroText = siteSetting('hero_text');
$heroWhatsAppText = siteSetting('hero_whatsapp_text', 'طلب عبر واتساب');
$heroPhoneText = siteSetting('hero_phone_text', 'اتصل بنا');
$heroContactTitle = siteSetting('hero_contact_title', 'تواصل مباشر');
$siteWhatsAppUrl = siteSetting('contact_whatsapp_url');
if (!$siteWhatsAppUrl) {
    $siteWhatsAppUrl = formatWhatsAppLink(siteSetting('contact_whatsapp_display', '+20 123 456 7890'), siteSetting('hero_whatsapp_message', 'مرحباً، أود الاستفسار عن منتجات فحم العاصمة'));
}
$sitePhoneUrl = formatPhoneLink(siteSetting('contact_phone_display', '+20 123 456 7890'));
$siteWhatsAppDisplay = siteSetting('contact_whatsapp_display', '+20 123 456 7890');
$sitePhoneDisplay = siteSetting('contact_phone_display', '+20 123 456 7890');
$aboutSectionTitle = siteSetting('about_section_title');
$aboutSectionSubtitle = siteSetting('about_section_subtitle');
$aboutTitle = siteSetting('about_title');
$aboutText = siteSetting('about_text');
$aboutText2 = siteSetting('about_text_2');
$productsSectionTitle = siteSetting('products_section_title');
$productsSectionDescription = siteSetting('products_section_description');
$gallerySectionTitle = siteSetting('gallery_section_title');
$gallerySectionDescription = siteSetting('gallery_section_description');
$articlesSectionTitle = siteSetting('articles_section_title');
$articlesSectionDescription = siteSetting('articles_section_description');
$contactSectionTitle = siteSetting('contact_section_title');
$contactSectionDescription = siteSetting('contact_section_description');
$contactAddress = siteSettingText('contact_address');
$contactWorkHours = siteSettingText('contact_work_hours');
$footerDescription = siteSetting('footer_description');
$footerWhatsAppDisplay = siteSetting('contact_whatsapp_display', '+20 123 456 7890');
$footerPhoneDisplay = siteSetting('contact_phone_display', '+20 123 456 7890');
$footerWhatsAppUrl = siteSetting('contact_whatsapp_url');
if (!$footerWhatsAppUrl) {
    $footerWhatsAppUrl = formatWhatsAppLink(siteSetting('contact_whatsapp_display', '+20 123 456 7890'));
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $siteTitle ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- ===== FLOATING WHATSAPP & PHONE BUTTONS ===== -->
    <div class="floating-contact">
        <a href="<?= $siteWhatsAppUrl ?>" target="_blank" class="floating-btn whatsapp" title="تواصل عبر واتساب">
            <i class="fab fa-whatsapp"></i>
            <span class="tooltip">تواصل عبر واتساب</span>
        </a>
        <a href="<?= $sitePhoneUrl ?>" class="floating-btn phone" title="اتصل بنا الآن">
            <i class="fas fa-phone-alt"></i>
            <span class="tooltip">اتصل بنا الآن</span>
        </a>
    </div>

    <!-- ================= PUBLIC SITE ================= -->
    <div class="public-site active" id="publicSite">

        <!-- Navbar -->
        <nav class="navbar" id="navbar">
            <div class="logo">
                <div class="logo-icon"><i class="fas fa-fire"></i></div>
                <div class="logo-text"><?= $siteName ?> <span><?= $siteNameHighlight ?></span></div>
            </div>
            <ul class="nav-links" id="navLinks">
                <li><a href="#home" class="active">الرئيسية</a></li>
                <li><a href="#about">من نحن</a></li>
                <li><a href="#products">منتجاتنا</a></li>
                <li><a href="#features">لماذا نحن</a></li>
                <li><a href="#gallery">المعرض</a></li>
                <li><a href="#articles">المقالات</a></li>
                <li><a href="#contact">تواصل معنا</a></li>
            </ul>
            <div class="nav-actions">
                <a href="<?= $sitePhoneUrl ?>" class="btn btn-phone"><i class="fas fa-phone-alt"></i> اتصل الآن</a>
                <a href="<?= $siteWhatsAppUrl ?>" target="_blank" class="btn btn-whatsapp"><i class="fab fa-whatsapp"></i> واتساب</a>
                <div class="mobile-menu-btn" onclick="toggleMobileMenu()"><i class="fas fa-bars"></i></div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero" id="home">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-certificate"></i>
                    <?= $heroBadge ?>
                </div>
                <h1><?= $heroHeadline ?></h1>
                <p><?= $heroText ?></p>
                <div class="hero-buttons">
                    <a href="<?= $siteWhatsAppUrl ?>" target="_blank" class="btn btn-whatsapp"><i class="fab fa-whatsapp"></i> <?= $heroWhatsAppText ?></a>
                    <a href="<?= $sitePhoneUrl ?>" class="btn btn-phone"><i class="fas fa-phone-alt"></i> <?= $heroPhoneText ?></a>
                </div>

                <!-- Quick Contact Box in Hero -->
                <div class="hero-contact-box">
                    <h3><i class="fas fa-headset" style="margin-left:8px;"></i> <?= $heroContactTitle ?></h3>
                    <span class="phone-number"><?= $sitePhoneDisplay ?></span>
                    <div class="contact-actions">
                        <a href="<?= $siteWhatsAppUrl ?>" target="_blank" class="btn btn-whatsapp" style="flex:1; justify-content:center;">
                            <i class="fab fa-whatsapp"></i> واتساب
                        </a>
                        <a href="<?= $sitePhoneUrl ?>" class="btn btn-phone" style="flex:1; justify-content:center;">
                            <i class="fas fa-phone-alt"></i> اتصال
                        </a>
                    </div>
                </div>
            </div>
            <div class="scroll-indicator">
                <i class="fas fa-chevron-down"></i>
            </div>
        </section>

        <!-- About Section -->
        <section class="section" id="about">
            <div class="section-header">
                <h2><?= siteSetting('about_section_title') ?> <span><?= $siteName ?></span></h2>
                <div class="section-divider"></div>
                <p><?= $aboutSectionSubtitle ?></p>
            </div>
            <div class="about-grid">
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="<?= $siteName ?>">
                </div>
                <div class="about-content">
                    <h3><?= $aboutTitle ?></h3>
                    <p><?= $aboutText ?></p>
                    <p><?= $aboutText2 ?></p>
                    <div class="about-stats">
                        <div class="stat-box">
                            <span class="number">+15</span>
                            <span class="label">سنوات خبرة</span>
                        </div>
                        <div class="stat-box">
                            <span class="number">+50</span>
                            <span class="label">عميل سعيد</span>
                        </div>
                        <div class="stat-box">
                            <span class="number">+500</span>
                            <span class="label">طن سنوياً</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section class="section products-section" id="products">
            <div class="section-header">
                <h2><?= $productsSectionTitle ?></h2>
                <div class="section-divider"></div>
                <p><?= $productsSectionDescription ?></p>
            </div>
            <div class="products-grid" id="productsGrid">
                <!-- Products loaded dynamically -->
            </div>
        </section>

        <!-- Gallery Section -->
        <section class="section products-section" id="gallery">
            <div class="section-header">
                <h2><?= $gallerySectionTitle ?></h2>
                <div class="section-divider"></div>
                <p><?= $gallerySectionDescription ?></p>
            </div>
            <div class="products-grid" id="galleryGrid"></div>
        </section>

        <!-- Features Section -->
        <section class="section" id="features">
            <div class="section-header">
                <h2>لماذا <span>فحم العاصمة؟</span></h2>
                <div class="section-divider"></div>
                <p>نفتخر بتقديم منتجات ذات جودة استثنائية مع خدمة متميزة</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-leaf"></i></div>
                    <h3>100% طبيعي</h3>
                    <p>فحم طبيعي خالٍ تماماً من المواد الكيميائية والإضافات الضارة</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-fire-alt"></i></div>
                    <h3>احتراق طويل</h3>
                    <p>وقت احتراق يتجاوز 5 ساعات مع حرارة متسقة ومستمرة</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-smile"></i></div>
                    <h3>خالٍ من الرائحة</h3>
                    <p>لا ينتج أي روائح كريهة أو دخان مزعج أثناء الاحتراق</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-shipping-fast"></i></div>
                    <h3>توصيل سريع</h3>
                    <p>نوفر خدمة التوصيل لجميع المحافظات في أسرع وقت ممكن</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-award"></i></div>
                    <h3>جودة مضمونة</h3>
                    <p>نضمن جودة منتجاتنا مع إمكانية الاستبدال أو الاسترجاع</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-tags"></i></div>
                    <h3>أسعار تنافسية</h3>
                    <p>أفضل الأسعار في السوق مع خصومات خاصة للكميات الكبيرة</p>
                </div>
            </div>
        </section>

        
        <!-- Articles Section -->
        <section class="section products-section" id="articles">
            <div class="section-header">
                <h2><?= $articlesSectionTitle ?></h2>
                <div class="section-divider"></div>
                <p><?= $articlesSectionDescription ?></p>
            </div>

            <div class="products-grid" id="articlesGrid"></div>
        </section>


        <!-- Contact Section - Redesigned for WhatsApp/Phone -->
        <section class="section contact-section" id="contact">
            <div class="section-header">
                <h2><?= $contactSectionTitle ?></h2>
                <div class="section-divider"></div>
                <p><?= $contactSectionDescription ?></p>
            </div>
            <div class="contact-grid">
                <div class="contact-info">
                    <h3>معلومات <span>الاتصال</span></h3>
                    <div class="contact-details">
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>العنوان</strong>
                                <span><?= $contactAddress ?></span>
                            </div>
                        </div>
                        <div class="contact-item" style="border-color: rgba(37,211,102,0.3);">
                            <i class="fab fa-whatsapp" style="background: rgba(37,211,102,0.15); color: var(--whatsapp);"></i>
                            <div>
                                <strong>واتساب</strong>
                                <a href="<?= $siteWhatsAppUrl ?>" target="_blank" class="phone-display"><?= $siteWhatsAppDisplay ?></a>
                                <span style="display:block; margin-top:5px; font-size:13px;">انقر للتواصل المباشر</span>
                            </div>
                        </div>
                        <div class="contact-item" style="border-color: rgba(52,183,241,0.3);">
                            <i class="fas fa-phone-alt" style="background: rgba(52,183,241,0.15); color: var(--phone);"></i>
                            <div>
                                <strong>اتصال مباشر</strong>
                                <a href="<?= $sitePhoneUrl ?>" class="phone-display"><?= $sitePhoneDisplay ?></a>
                                <span style="display:block; margin-top:5px; font-size:13px;">متاح من السبت للخميس</span>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <strong>ساعات العمل</strong>
                                <span><?= $contactWorkHours ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="quick-contact-box">
                    <h3>اطلب الآن مباشرة</h3>
                    <p>لا حاجة لملء نماذج! تواصل معنا مباشرة</p>

                    <div class="quick-phone">
                        <i class="fas fa-phone-volume"></i>
                        <span class="number"><?= $sitePhoneDisplay ?></span>
                        <span class="label">متاح للاتصال والواتساب</span>
                    </div>

                    <div class="quick-actions">
                        <a href="<?= $siteWhatsAppUrl ?>" target="_blank" class="btn btn-whatsapp">
                            <i class="fab fa-whatsapp"></i> تواصل عبر واتساب
                        </a>
                        <a href="<?= $sitePhoneUrl ?>" class="btn btn-phone">
                            <i class="fas fa-phone-alt"></i> اتصل بنا الآن
                        </a>
                    </div>

                    <div class="working-hours">
                        <h4><i class="far fa-clock" style="margin-left:8px;"></i> مواعيد العمل</h4>
                        <p><?= $contactWorkHours ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo">
                        <div class="logo-icon"><i class="fas fa-fire"></i></div>
                        <div class="logo-text">فحم <span>العاصمة</span></div>
                    </div>
                    <p><?= $footerDescription ?></p>
                    <div class="contact-direct">
                        <a href="<?= $footerWhatsAppUrl ?>" target="_blank" class="whatsapp-footer">
                            <i class="fab fa-whatsapp"></i>
                            <span><?= $footerWhatsAppDisplay ?> (واتساب)</span>
                        </a>
                        <a href="<?= $sitePhoneUrl ?>" class="phone-footer">
                            <i class="fas fa-phone-alt"></i>
                            <span><?= $footerPhoneDisplay ?> (اتصال)</span>
                        </a>
                    </div>
                </div>
                <div class="footer-links">
                    <h4>روابط سريعة</h4>
                    <ul>
                        <li><a href="#home">الرئيسية</a></li>
                        <li><a href="#about">من نحن</a></li>
                        <li><a href="#products">منتجاتنا</a></li>
                        <li><a href="#contact">تواصل معنا</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>منتجاتنا</h4>
                    <ul>
                        <li><a href="#products">فحم الجوافة</a></li>
                        <li><a href="#products">فحم البرتقال</a></li>
                        <li><a href="#products">فحم الليمون</a></li>
                        <li><a href="#products">فحم الزيتون</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>الدعم</h4>
                    <ul>
                        <li><a href="#">الشروط والأحكام</a></li>
                        <li><a href="#">سياسة الخصوصية</a></li>
                        <li><a href="#">الأسئلة الشائعة</a></li>
                        <li><a href="admin.php">لوحة التحكم</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2024 فحم العاصمة. جميع الحقوق محفوظة. | تصميم وتطوير <span style="color:var(--accent)">فريق العاصمة</span></p>
            </div>
        </footer>
    </div>


    <script defer src="assets/js/app.js"></script>
</body>
</html>
