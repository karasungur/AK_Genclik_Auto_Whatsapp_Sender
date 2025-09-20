# Hisar Global Dijital Platformu

Hisar Global distribütörlük ve bayilik ağını yönetmek üzere tasarlanan bu proje, Laravel 12 tabanlı bir çok aşamalı içerik yönetim platformudur. Proje; marka, ürün, mağaza ve şehir bazlı içeriklerin tek merkezden yönetilmesini, dağıtım ve perakende kanallarının aynı çatı altında sunulmasını ve paylaşımlı hosting ortamlarına sorunsuz dağıtımı hedefler.

## Özellikler

- **Türkçe odaklı yapı**: Varsayılan yerelleştirme `tr`, Faker yereli `tr_TR` ve proje kimliği `.env` şablonunda hazır gelir.
- **Bridge mimarisi**: Proje kökündeki `index.php`, tüm istekleri `public/` dizinine yönlendirerek paylaşımlı hosting senaryolarını destekler.
- **Özel yapılandırma**: `config/hisar.php` dosyası üzerinden menü limitleri, mega menü yönlendirmesi, önbellek, marka teması, GA4 ve PWA renkleri yönetilir.
- **Yükleme diski**: `public/uploads` dizini 0775 izinleriyle hazırdır ve `filesystems.php` içerisinde proje varsayılanları tanımlanmıştır.
- **Güvenlik ve oturum yönetimi**: SetSecurityHeaders, SetLocale, CsrfTokenRotate ve AdminGuard middleware zincire eklenmiştir.
- **Laravel ekosistem uyumu**: Composer/NPM komutları, Vite derleme altyapısı ve Artisan test komutları varsayılan olarak yapılandırılmıştır.

## Gereksinimler

- PHP ^8.2 (intl, mbstring, openssl, pdo_mysql/pgsql, gd veya imagick önerilir)
- Composer 2.x
- Node.js 20+ ve npm/yarn (opsiyonel, Vite derlemeleri için)
- MySQL 8+ veya PostgreSQL 14+

## Kurulum (Yerel Geliştirme)

1. Depoyu klonlayın ve dizine girin:
   ```bash
   git clone <repo-url>
   cd AK_Genclik_Auto_Whatsapp_Sender
   ```
2. Bağımlılıkları yükleyin:
   ```bash
   composer install
   npm install # Vite kullanacaksanız
   ```
3. Ortam dosyasını oluşturun ve anahtar üretin:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. `.env` içindeki veritabanı ve uygulama ayarlarını aşağıdaki tabloya göre güncelleyin.
5. Veritabanını hazırlayın (ilerleyen görevlerde migrasyonlar tamamlandığında):
   ```bash
   php artisan migrate --seed
   ```
6. Geliştirme sunucusunu ve derleyicileri başlatın:
   ```bash
   php artisan serve
   npm run dev
   ```

## Ortam Değişkenleri

| Anahtar | Açıklama | Varsayılan |
| --- | --- | --- |
| `APP_NAME` | Uygulama adı | `Hisar` |
| `APP_URL` | Yerel kök adres (bridge sayesinde alt klasör gerekmez) | `http://127.0.0.1` |
| `APP_LOCALE` | Uygulama yereli | `tr` |
| `DB_CONNECTION` | `mysql` veya `pgsql` | `mysql` |
| `MENU_LIMIT_BRANDS` | Mega menüde listelenecek marka sayısı | `10` |
| `MENU_LIMIT_STORES` | Perakende açılır menü limiti | `10` |
| `MENU_LIMIT_PROJECTS` | İnşaat açılır menü limiti | `10` |
| `MEGA_MENU_ORIENTATION` | `vertical` veya `horizontal` | `vertical` |
| `CACHE_TTL_MIN` | Menü/cache verileri için dakika bazlı TTL | `10` |
| `GA4_ID` | Google Analytics 4 ölçüm kimliği | boş |
| `PWA_THEME_COLOR` | Manifest tema rengi | `#ED8B00` |
| `PWA_BG_COLOR` | Manifest arka plan rengi | `#ffffff` |
| `UPLOAD_MAX_KB` | Maksimum yükleme boyutu (KB) | `10240` |
| `UPLOAD_ALLOW_SVG` | SVG yüklemelerini etkinleştirme (true/false) | `false` |

## Proje Yapısı

- `app/Http/Controllers/Front` – Ön yüz için temel controller seti (ileriki görevlerde genişletilecek).
- `app/Http/Middleware` – Güvenlik, yerelleştirme ve yönetici oturumunu yöneten özel middleware'ler.
- `config/hisar.php` – Hisar Global'e özgü yapılandırma anahtarı.
- `public/` – Uygulama kökü, servis çalışanı, manifest ve varlık dosyalarının yer alacağı dizin.
- `resources/` – Blade şablonları, CSS ve JS kaynakları.
- `routes/web.php` – Tüm rotalar controller metodlarına yönlenir; closure kullanılmaz.

## Dağıtım Notları (Paylaşımlı Hosting)

- Sunucu `public/` dizinini kök olarak belirleyemiyorsa, kökteki `index.php` bridge dosyası ile yönlendirme yapılmalıdır.
- Yerel ortamda üretilen `vendor/`, `public/build`, `public/assets`, `public/icons` ve boş `public/uploads` dizinleri ile birlikte yükleyin.
- `storage/app/backups` ve `storage/logs` dizinlerinin yazılabilir olduğundan emin olun.
- `.env` dosyasını üretim değerleriyle manuel olarak güncelleyin; `MAIL_MAILER` için SMTP önerilir, yoksa `log` kullanabilirsiniz.
- Cache veya rota/konfigürasyon önbelleğini temizlemek için `php artisan config:clear` ve `php artisan cache:clear` komutlarını çalıştırın.

## Testler

Projeyi doğrulamak için:
```bash
php artisan test
```

---

İlerleyen görevlerde migrasyonlar, yönetim paneli ve ön yüz bileşenleri tamamlandıkça README güncellenmelidir.
