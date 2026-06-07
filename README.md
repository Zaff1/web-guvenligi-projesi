# 🔐 Web Güvenliği ve Güvenli Kodlama Projesi

SQL Injection, XSS ve HTML Injection zafiyetlerini 
göstermek ve güvenli kodlama pratiklerini uygulamak 
amacıyla geliştirilmiş eğitim amaçlı web uygulaması.

## ⚠️ Uyarı
Bu proje bilinçli olarak güvenlik açıkları içermektedir.
Yalnızca eğitim amaçlıdır, canlı ortamda kullanılmaz.

## 🐛 Zafiyetler
| Zafiyet | Dosya | Tür |
|---------|-------|-----|
| SQL Injection | login.php | Authentication Bypass |
| SQL Injection | login.php | Blind SQLi |
| HTML Injection | zafiyetli.php | Phishing |
| Reflected XSS | zafiyetli.php | Cookie Theft |
| Stored XSS | zafiyetli.php | Kalıcı Tehdit |

## 🛡️ Güvenlik Çözümleri
- Prepared Statements (Parametrik Sorgular)
- htmlspecialchars() ile Output Encoding
- Input Sanitization

## 🚀 Kurulum
1. XAMPP'ı başlat (Apache + MySQL)
2. Dosyaları `htdocs/web_proje/` klasörüne kopyala
3. `web_proje.sql`'i phpMyAdmin'den import et
4. `config.php` oluştur ve DB bilgilerini gir
5. `http://localhost/web_proje/login.php` adresine git

## 🧪 Test Kullanıcıları
- **admin** / admin
- **zafer** / zafer

## 🔧 Teknolojiler
![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white)
![HTML](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white)
![CSS](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white)
