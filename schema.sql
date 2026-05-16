-- schema.sql
-- انشئ قاعدة بيانات Alasuma ثم استورد هذا المخطط

CREATE DATABASE IF NOT EXISTS `alasuma` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `alasuma`;

CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admins` (`username`, `password`) VALUES
('admin', '$2y$10$9B3v.Fj1hQx7/g7uSC8Seuk7Fy0hsfEYT3Zk8GStqnEM1YERA2bW6');
-- كلمة المرور الافتراضية: admin123

CREATE TABLE IF NOT EXISTS `products` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `type` VARCHAR(100) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `description` TEXT,
  `image` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `articles` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `gallery_images` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(100) NOT NULL,
  `setting_value` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('site_title', 'فحم العاصمة | مصنع الفحم النباتي - دمياط الجديدة'),
('site_name', 'فحم العاصمة'),
('site_name_highlight', 'العاصمة'),
('site_tagline', 'مصنع الفحم النباتي الأفضل في دمياط الجديدة'),
('hero_badge', 'أفضل مصنع فحم في مصر'),
('hero_headline', 'فحم العاصمة النباتي'),
('hero_text', 'أجود أنواع الفحم النباتي المصري بأعلى معايير الجودة العالمية. ننتج الفحم الطبيعي 100% من أجود أخشاب الأشجار المثمرة بخبرة تتجاوز 15 عاماً.'),
('hero_whatsapp_message', 'مرحباً، أود الاستفسار عن منتجات فحم العاصمة'),
('contact_whatsapp_display', '+20 123 456 7890'),
('contact_phone_display', '+20 123 456 7890'),
('contact_whatsapp_url', 'https://wa.me/201234567890'),
('theme_accent', '#c9a84c'),
('theme_whatsapp', '#25D366'),
('about_section_title', 'من نحن'),
('about_section_subtitle', 'مصنع متخصص في إنتاج الفحم النباتي الطبيعي بأعلى معايير الجودة'),
('about_title', 'خبرة 15 عاماً في صناعة الفحم'),
('about_text', 'مصنع فحم العاصمة يقع في قلب المنطقة الصناعية بدمياط الجديدة، مصر. نحن متخصصون في إنتاج الفحم النباتي الطبيعي 100% من أجود أنواع الأخشاب المثمرة مثل الجوافة والبرتقال والليمون والزيتون.'),
('about_text_2', 'نستخدم أحدث التقنيات في عمليات التقطير والتفحيم لضمان منتج نظيف خالٍ من الشوائب والمواد الكيميائية، مما يجعل فحمنا الخيار الأمثل للمطاعم والمقاهي والاستخدام المنزلي.'),
('products_section_title', 'منتجات فحم العاصمة'),
('products_section_description', 'نقدم مجموعة متنوعة من أجود أنواع الفحم النباتي لتلبية جميع احتياجاتك'),
('gallery_section_title', 'معرض الصور'),
('gallery_section_description', 'صور من خط الإنتاج، التعبئة والشحن داخل مصنع الفحم'),
('articles_section_title', 'أحدث المقالات'),
('articles_section_description', 'تعرف على أفضل النصائح والمعلومات عن الفحم النباتي والتصدير'),
('contact_section_title', 'تواصل معنا'),
('contact_section_description', 'تواصل معنا مباشرة عبر الهاتف أو واتساب للطلب والاستفسار'),
('contact_address', 'مصر - دمياط الجديدة\nالمنطقة الصناعية - مصنع فحم العاصمة'),
('contact_work_hours', 'السبت - الخميس: 8:00 ص - 6:00 م\nالجمعة: مغلق'),
('footer_description', 'مصنع فحم العاصمة هو أحد أكبر مصانع الفحم النباتي في مصر. نحن نلتزم بتقديم منتجات عالية الجودة تلبي احتياجات عملائنا في السوق المحلي والدولي.');
