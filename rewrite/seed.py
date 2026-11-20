"""Заполнение БД демо-данными (аналог install-экшенов Yii-модулей)."""
from app import create_app
from app.db import db, User, Page, MenuItem, IBlock, IBlockElement, Faq, Poll, PollAnswer, Banner, Setting

app = create_app()
with app.app_context():
    if not User.query.first():
        admin = User(login='admin', full_name='Администратор', email='admin@example.com', role='admin')
        admin.set_password('admin123')
        db.session.add(admin)

        db.session.add_all([
            Setting(name='site_name', title='Название сайта', value='Управление социальной политики Липецкой области'),
            Setting(name='address', title='Адрес', value='398000, г. Липецк, пл. Победы, 3'),
            Setting(name='phone', title='Телефон', value='+7 (4742) 22-66-84'),
        ])

        menu_items = ['О управлении', 'Структура', 'Деятельность', 'Документы', 'Контакты']
        for i, t in enumerate(menu_items):
            p = Page(title=t, name=t.lower(), body=f'<p>Раздел «{t}».</p>')
            db.session.add(p)
            db.session.flush()
            db.session.add(MenuItem(title=t, link=p.url, sort=(i + 1) * 10))

        ib = IBlock(code='news', name='Новости')
        db.session.add(ib)
        db.session.flush()
        for j in range(1, 6):
            db.session.add(IBlockElement(iblock_id=ib.id, name=f'Новость {j}',
                                         preview_text='Краткое описание новости…',
                                         detail_text=f'<p>Полный текст новости {j}.</p>'))

        f = Faq(q_author='Иван Петров', q_email='ivan@example.com',
                question='Как оформить субсидию на ЖКУ?', approved=True,
                answer='Обратитесь в отдел соцзащиты по месту жительства.', a_author='Администратор')
        db.session.add(f)

        poll = Poll(title='Опрос', question='Оцените работу управления')
        db.session.add(poll)
        db.session.flush()
        for v in ['Отлично', 'Хорошо', 'Удовлетворительно', 'Плохо']:
            db.session.add(PollAnswer(poll_id=poll.id, title=v))

        db.session.add(Banner(title='Госуслуги', url='https://www.gosuslugi.ru/', position='main'))
        db.session.commit()
        print('Демо-данные добавлены. Логин админки: admin / admin123')
    else:
        print('БД уже заполнена.')
