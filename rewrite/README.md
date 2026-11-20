# Переписанный сайт Управления социальной политики Липецкой области

## Анализ оригинала
Оригинальный сайт (usp.admlr.lipetsk.ru) был написан на:
- **PHP 5 + фреймворк Yii 1.1.x** (точка входа `index.php` → `protected/config/main.php`, theme front/special для слабовидящих)
- CMS из самодельных модулей: `pages`, `menu`, `iblock`, `user`, `faq`, `polls`, `banner`, `reception`, `settings`, `textblock`, `fotogallery`, `contest`, `filemanager`, `backend`
- MySQL (БД `szn`), Behat-подобные расширения (CKEditor, elFinder, eauth/eoauth, nested-set)
- Отдельные Vue.js-бандлы (webpack chat-виджет), статичный HTML-киоск, портал опеки на Antenna

## Что здесь (переписано на Python 3 + Flask)
- `app/__init__.py` — фабрика приложения
- `app/db.py` — модели SQLAlchemy (перенос таблиц Yii AR: pages, menu_items, iblock*, user, faq, polls*, banner, reception, textblock, settings_params)
- `app/frontend.py` — публичная часть: главная, страницы, новости, FAQ, голосования, обращения, sitemap, киоск
- `app/backend.py` — админка (flask-login): CRUD страниц/меню/новостей/баннеров/текст-блоков/настроек, модерация FAQ, просмотр обращений
- `templates/`, `static/` — Jinja2-шаблоны и CSS
- `seed.py` — демо-данные (логин админки: `admin` / `admin123`)

## Запуск
```bash
pip install -r requirements.txt
python seed.py        # создать БД и демо-данные (SQLite usp.db; MySQL — через DATABASE_URL)
python run.py         # http://127.0.0.1:5000  (админка: /backend/login)
```

## Соответствие маршрутов
| Оригинал (Yii) | Новый (Flask) |
|---|---|
| /pages/default/view?... | /page/<id> |
| /site/news | /news/, /news/<id> |
| /faq | /faq/, /faq/ask |
| голосование on main | POST /poll/vote |
| приёмная | /reception/ |
| /backend/* | /backend/* (CRUD) |
| /kiosk/kiosk.html | /kiosk/ |
| sitemap.xml | /sitemap.xml |
